<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Lunar\Exceptions\Carts\CartException;
use Lunar\Facades\CartSession;
use Lunar\Models\ProductVariant;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $data = $request->validate([
            'variant_id' => 'required|integer|exists:lunar_product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::find($data['variant_id']);

        $existingQty = 0;
        if ($cart = CartSession::current(calculate: false)) {
            $existingQty = $cart->lines
                ->where('purchasable_type', $variant->getMorphClass())
                ->where('purchasable_id', $variant->id)
                ->sum('quantity');
        }

        if (! $variant->canBeFulfilledAtQuantity($existingQty + $data['quantity'])) {
            return back()->withErrors(['cart' => 'Item is not available at this quantity.']);
        }

        try {
            CartSession::add($variant, $data['quantity']);
        } catch (CartException $e) {
            return back()->withErrors(['cart' => $e->getMessage()]);
        }

        return back();
    }

    public function remove(int $cartLineId)
    {
        CartSession::remove($cartLineId);

        return back();
    }

    public function update(Request $request, int $cartLineId)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            CartSession::updateLine($cartLineId, $data['quantity']);
        } catch (CartException $e) {
            return back()->withErrors(['cart' => $e->getMessage()]);
        }

        return back();
    }
}
