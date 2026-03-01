<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Lunar\Models\Product;

class GalleryController extends Controller
{
    public function index(): Response
    {
        $products = Product::status('published')->with(['thumbnail', 'media'])->get();

        $gallery = $products->map(function ($product) {
            $images = $product->media->map(fn ($m) => $m->getUrl('medium'))->values()->all();

            return [
                'id'          => $product->id,
                'name'        => $product->translateAttribute('name') ?? '',
                'description' => $product->translateAttribute('description') ?? '',
                'thumbnail'   => $product->getThumbnailImage(),
                'images'      => $images,
            ];
        })->filter(fn ($p) => ! empty($p['images']))->values();

        return Inertia::render('Gallery', [
            'products' => $gallery,
        ]);
    }
}
