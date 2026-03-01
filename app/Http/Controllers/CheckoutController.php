<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Lunar\Actions\Carts\SetShippingOption;
use Lunar\Facades\CartSession;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Models\Country;
use Lunar\Models\ProductVariant;
use Lunar\Shipping\Models\ShippingZone;
use Lunar\Models\Transaction;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;
use UnexpectedValueException;

class CheckoutController extends Controller
{
    public function show(): RedirectResponse|InertiaResponse
    {
        $cart = CartSession::current(calculate: false);

        if (! $cart || $cart->lines->isEmpty()) {
            return redirect('/shop');
        }

        $countries = ShippingZone::whereHas('rates')
            ->with('countries:id,name,iso2')
            ->get()
            ->flatMap(fn ($zone) => $zone->countries)
            ->unique('id')
            ->sortBy('name')
            ->map(fn ($c) => ['code' => $c->iso2, 'name' => $c->name])
            ->values()
            ->all();

        $shippingOptions = session('shipping_options');

        if ($shippingOptions) {
            return Inertia::render('Checkout', [
                'step'            => 'shipping',
                'shippingOptions' => $shippingOptions,
                'countries'       => $countries,
            ]);
        }

        return Inertia::render('Checkout', ['countries' => $countries]);
    }

    public function saveAddress(Request $request): RedirectResponse|InertiaResponse
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

        $options = ShippingManifest::getOptions($cart);

        if ($options->isEmpty()) {
            return back()->withErrors(['checkout' => 'No shipping options are available for your address.']);
        }

        $serialized = $options->map(fn ($option) => [
            'identifier'  => $option->getIdentifier(),
            'name'        => $option->getName(),
            'description' => $option->getDescription() ? strip_tags($option->getDescription()) : null,
            'price'       => $option->price->formatted(),
            'price_value' => $option->price->value,
        ])->values()->all();

        session()->put('shipping_options', $serialized);

        return redirect()->route('checkout');
    }

    public function createSession(Request $request): SymfonyResponse
    {
        $data = $request->validate([
            'shipping_option' => 'required|string',
        ]);

        $cart = CartSession::current(calculate: false);

        if (! $cart || $cart->lines->isEmpty()) {
            return redirect('/shop');
        }

        $option = ShippingManifest::getOption($cart, $data['shipping_option']);

        if (! $option) {
            return back()->withErrors(['checkout' => 'The selected shipping option is no longer available.']);
        }

        app(SetShippingOption::class)->execute($cart, $option);

        session()->forget('shipping_options');

        $cart = CartSession::current();

        Stripe::setApiKey(config('services.stripe.secret'));

        $currency = strtolower($cart->currency->code);

        $lineItems = $cart->lines->map(function ($line) use ($currency) {
            $name = $line->purchasable->product->translateAttribute('name');
            $variantOption = $line->purchasable->getOption();
            if ($variantOption) {
                $name .= ' — ' . $variantOption;
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

        if (($cart->shippingTotal?->value ?? 0) > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => $currency,
                    'product_data' => ['name' => 'Shipping'],
                    'unit_amount'  => $cart->shippingTotal?->value ?? 0,
                ],
                'quantity' => 1,
            ];
        }

        $billingEmail = $cart->billingAddress?->contact_email ?? '';

        $session = CheckoutSession::create([
            'mode'           => 'payment',
            'line_items'     => $lineItems,
            'customer_email' => $billingEmail,
            'metadata'       => ['cart_id' => $cart->id],
            'success_url'    => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'     => route('checkout.cancel'),
        ]);

        return Inertia::location($session->url);
    }

    public function success(Request $request): RedirectResponse|InertiaResponse
    {
        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return redirect('/checkout')->withErrors(['checkout' => 'Missing session ID.']);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = CheckoutSession::retrieve([
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

    public function cancel(): RedirectResponse
    {
        return redirect('/shop');
    }

    public function webhook(Request $request): Response
    {
        $payload = $request->getContent();
        $sig     = $request->header('Stripe-Signature');

        if (! is_string($sig)) {
            return response('Missing signature', 400);
        }

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig,
                config('services.stripe.webhook_secret')
            );
        } catch (SignatureVerificationException) {
            return response('Invalid signature', 400);
        } catch (UnexpectedValueException) {
            return response('Invalid payload', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            /** @var CheckoutSession $session */
            $session = $event->data->object;
            $this->handlePaymentSuccess($session);
        }

        return response('OK', 200);
    }

    private function handlePaymentSuccess(CheckoutSession $session): void
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

        /** @var \Lunar\DataTypes\Price $orderTotal */
        $orderTotal = $order->total;

        $order->transactions()->create([
            'success'   => true,
            'type'      => 'capture',
            'driver'    => 'stripe',
            'amount'    => $orderTotal->value,
            'reference' => $paymentIntentId,
            'status'    => 'succeeded',
            'card_type' => 'card',
            'meta'      => ['stripe_session_id' => $session->id],
        ]);

        foreach ($order->lines as $line) {
            if ($line->purchasable instanceof ProductVariant) {
                $line->purchasable->decrement('stock', $line->quantity);
            }
        }
    }
}
