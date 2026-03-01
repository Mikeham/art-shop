<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Lunar\Models\Product;

class HomeController extends Controller
{
    public function index(): Response
    {
        $featured = Product::status('published')
            ->with(['thumbnail', 'defaultUrl', 'variants.prices.currency'])
            ->take(3)
            ->get()
            ->map(fn ($product) => [
                'id'        => $product->id,
                'name'      => $product->translateAttribute('name') ?? '',
                'thumbnail' => $product->getThumbnailImage(),
                'slug'      => $product->defaultUrl?->slug,
                'price'     => $product->variants->first()?->prices->first()?->price->formatted ?? '',
                'in_stock'  => $product->variants->some(fn ($v) => $v->canBeFulfilledAtQuantity(1)),
            ]);

        return Inertia::render('Home', [
            'featured' => $featured,
        ]);
    }
}
