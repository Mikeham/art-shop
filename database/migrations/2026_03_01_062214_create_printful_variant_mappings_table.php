<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('printful_variant_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')
                  ->unique()
                  ->constrained('lunar_product_variants')
                  ->cascadeOnDelete();
            $table->string('printful_catalog_variant_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('printful_variant_mappings');
    }
};
