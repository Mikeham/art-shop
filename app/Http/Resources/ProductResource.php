<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->translateAttribute('name') ?? '',
            'description' => $this->translateAttribute('description') ?? '',
            'brand' => $this->brand->name,
            'product_type' => $this->productType->name,
            'prices' => $this->prices()->get(),
            'variants' => $this->variants()->get(),
            'variantsv' => $this->variants()->first()->getOptions(),
        ];
    }
}
