<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Models\ProductVariant;

class PrintfulVariantMapping extends Model
{
    protected $fillable = [
        'product_variant_id',
        'printful_catalog_variant_id',
    ];

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
