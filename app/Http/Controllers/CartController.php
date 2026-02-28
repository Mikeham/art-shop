<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        CartSession::add($variant, $data['quantity']);

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

        CartSession::updateLine($cartLineId, $data['quantity']);

        return back();
    }
}
