<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Lunar\Models\Product;

class ShopController extends Controller
{
    public function list()
    {
        $products = Product::status('published')->get();

//        $galleryImages = [];
//        foreach ($products as $product) {
//
//            foreach($product->images()->get() as $image) {
//                $galleryImages[] = [
//                    'name' => $product->translateAttribute('name') ?? '',
//                    'description' => $product->translateAttribute('description') ?? '',
//                    'path' => Storage::url("$image->id/$image->file_name")
//                ];
//            }
//        }

        return Inertia::render('Shop', [
            'products' => ProductResource::collection($products)
        ]);
    }
}
