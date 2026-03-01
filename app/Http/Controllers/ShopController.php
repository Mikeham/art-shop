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
            ->with(['defaultUrl', 'thumbnail', 'media', 'variants.prices.currency', 'variants.values.option'])
            ->firstOrFail();

        return Inertia::render('ProductShow', [
            'product' => [
                'id'          => $product->id,
                'name'        => $product->translateAttribute('name') ?? '',
                'description' => $product->translateAttribute('description') ?? '',
                'image'       => $product->getThumbnailImage(),
                'images'      => $product->media->map(fn ($m) => $m->getUrl('medium'))->values(),
                'variants'    => $product->variants->map(fn ($variant) => [
                    'id'       => $variant->id,
                    'sku'      => $variant->sku,
                    'price'    => $variant->prices->first()?->price->formatted ?? '',
                    'in_stock' => $variant->canBeFulfilledAtQuantity(1),
                    'options'  => $variant->values->map(fn ($v) => [
                        'option' => $v->option->translate('name'),
                        'value'  => $v->translate('name'),
                    ])->values(),
                ])->values(),
            ],
        ]);
    }
}
