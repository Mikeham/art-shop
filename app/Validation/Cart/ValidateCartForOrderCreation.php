<?php

namespace App\Validation\Cart;

use Illuminate\Support\Facades\Validator;
use Lunar\Validation\BaseValidator;

/**
 * Validates a cart is ready to become an order.
 * Identical to Lunar's built-in validator but without the shipping-option
 * requirement, since we collect payment and shipping via Stripe Checkout.
 */
class ValidateCartForOrderCreation extends BaseValidator
{
    public function validate(): bool
    {
        $cart = $this->parameters['cart'];

        if ($cart->completedOrder) {
            return $this->fail('cart', __('lunar::exceptions.carts.order_exists'));
        }

        if (! $cart->billingAddress) {
            return $this->fail('cart', __('lunar::exceptions.carts.billing_missing'));
        }

        $billingValidator = Validator::make(
            $cart->billingAddress->toArray(),
            [
                'country_id' => 'required',
                'first_name' => 'required',
                'line_one'   => 'required',
                'city'       => 'required',
                'postcode'   => 'required',
            ]
        );

        if ($billingValidator->fails()) {
            return $this->fail('cart', $billingValidator->errors()->getMessages());
        }

        return $this->pass();
    }
}
