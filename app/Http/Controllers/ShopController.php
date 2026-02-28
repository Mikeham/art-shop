<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Inertia\Inertia;
use Lunar\Models\Product;

class ShopController extends Controller
{
    public function list()
    {
        $products = Product::status('published')
            ->with(['defaultUrl', 'thumbnail', 'variants.prices.currency'])
            ->get();

        return Inertia::render('Shop', [
            'products' => ProductResource::collection($products)->resolve()
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::whereHas('urls', fn ($q) => $q->where('slug', $slug)->where('default', true))
            ->with(['defaultUrl', 'thumbnail', 'variants.prices.currency'])
            ->firstOrFail();

        return Inertia::render('ProductShow', [
            'product' => new ProductResource($product)
        ]);
    }
}
