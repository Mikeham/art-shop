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
        $price = $this->variants->first()?->prices->first();

        return [
            'id' => $this->id,
            'name' => $this->translateAttribute('name') ?? '',
            'image' => $this->getThumbnailImage(),
            'price' => $price?->price->formatted ?? '',
            'slug' => $this->defaultUrl?->slug,
        ];
    }
}
