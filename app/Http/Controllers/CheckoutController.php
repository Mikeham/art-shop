<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Lunar\Facades\CartSession;
use Lunar\Models\Cart;
use Lunar\Models\Country;
use Lunar\Models\Transaction;
use Stripe\Exception\SignatureVerificationException;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = CartSession::current(calculate: false);

        if (! $cart || $cart->lines->isEmpty()) {
            return redirect('/shop');
        }

        return Inertia::render('Checkout');
    }

    public function createSession(Request $request)
    {
        $data = $request->validate([
            'email'      => 'required|email',
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'phone'      => 'required|string|max:30',
            'line_one'   => 'required|string',
            'city'       => 'required|string',
            'postcode'   => 'required|string',
            'country'    => 'required|string|size:2',
        ]);

        $cart = CartSession::current(calculate: false);

        if (! $cart || $cart->lines->isEmpty()) {
            return redirect('/shop');
        }

        $country = Country::where('iso2', strtoupper($data['country']))->firstOrFail();

        $addressData = [
            'first_name'    => $data['first_name'],
            'last_name'     => $data['last_name'],
            'contact_phone' => $data['phone'],
            'line_one'      => $data['line_one'],
            'city'          => $data['city'],
            'postcode'      => $data['postcode'],
            'country_id'    => $country->id,
            'contact_email' => $data['email'],
        ];

        $cart->addAddress($addressData, 'billing');
        $cart->addAddress($addressData, 'shipping');

        $cart = CartSession::current();

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $currency = strtolower($cart->currency->code);

        $lineItems = $cart->lines->map(function ($line) use ($currency) {
            $name = $line->purchasable->product->translateAttribute('name');
            $option = $line->purchasable->getOption();
            if ($option) {
                $name .= ' — ' . $option;
            }

            return [
                'price_data' => [
                    'currency'     => $currency,
                    'product_data' => ['name' => $name],
                    'unit_amount'  => $line->unitPrice->value,
                ],
                'quantity' => $line->quantity,
            ];
        })->values()->all();

        $session = \Stripe\Checkout\Session::create([
            'mode'           => 'payment',
            'line_items'     => $lineItems,
            'customer_email' => $data['email'],
            'metadata'       => ['cart_id' => $cart->id],
            'success_url'    => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'     => route('checkout.cancel'),
        ]);

        return Inertia::location($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return redirect('/checkout')->withErrors(['checkout' => 'Missing session ID.']);
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::retrieve([
            'id'     => $sessionId,
            'expand' => ['payment_intent'],
        ]);

        if ($session->payment_status !== 'paid') {
            return redirect('/checkout')->withErrors(['checkout' => 'Payment not completed.']);
        }

        $this->handlePaymentSuccess($session);

        $paymentIntentId = is_string($session->payment_intent)
            ? $session->payment_intent
            : $session->payment_intent->id;

        $transaction = Transaction::where('reference', $paymentIntentId)->first();

        if (! $transaction) {
            return redirect('/shop');
        }

        $order = $transaction->order;

        return Inertia::render('OrderSuccess', [
            'order' => [
                'reference' => $order->reference,
                'total'     => $order->total->formatted(),
            ],
        ]);
    }

    public function cancel()
    {
        return redirect('/shop');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig     = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig,
                config('services.stripe.webhook_secret')
            );
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $this->handlePaymentSuccess($event->data->object);
        }

        return response('OK', 200);
    }

    private function handlePaymentSuccess(\Stripe\Checkout\Session $session): void
    {
        $paymentIntentId = is_string($session->payment_intent)
            ? $session->payment_intent
            : $session->payment_intent->id;

        if (Transaction::where('reference', $paymentIntentId)->exists()) {
            return;
        }

        $cart = Cart::find($session->metadata->cart_id);

        if (! $cart) {
            return;
        }

        CartSession::use($cart);

        $order = CartSession::createOrder(forget: true);

        $order->update([
            'status'    => 'payment-received',
            'placed_at' => now(),
        ]);

        $order->transactions()->create([
            'success'   => true,
            'type'      => 'capture',
            'driver'    => 'stripe',
            'amount'    => $order->total->value,
            'reference' => $paymentIntentId,
            'status'    => 'succeeded',
            'card_type' => 'card',
            'meta'      => ['stripe_session_id' => $session->id],
        ]);

        foreach ($order->lines as $line) {
            if ($line->purchasable instanceof \Lunar\Models\ProductVariant) {
                $line->purchasable->decrement('stock', $line->quantity);
            }
        }
    }
}
