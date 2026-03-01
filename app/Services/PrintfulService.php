<?php

namespace App\Services;

use App\Models\PrintfulVariantMapping;
use Illuminate\Support\Facades\Http;
use Lunar\Models\Order;

class PrintfulService
{
    private const BASE_URL = 'https://api.printful.com/v2/';

    public function __construct(private string $apiKey) {}

    public function createOrder(Order $order): ?int
    {
        $shippingAddress = $order->shippingAddress;

        $variantIds = $order->productLines->pluck('purchasable_id');

        $mappings = PrintfulVariantMapping::whereIn('product_variant_id', $variantIds)
            ->get()
            ->keyBy('product_variant_id');

        if ($mappings->isEmpty()) {
            return null;
        }

        $items = $order->productLines
            ->filter(fn ($line) => $mappings->has($line->purchasable_id))
            ->map(fn ($line) => [
                'catalog_variant_id' => $mappings[$line->purchasable_id]->printful_catalog_variant_id,
                'quantity'           => $line->quantity,
                'source'             => 'catalog',
            ])
            ->values()
            ->all();

        $payload = [
            'recipient' => [
                'name'         => trim($shippingAddress->first_name . ' ' . $shippingAddress->last_name),
                'address1'     => $shippingAddress->line_one,
                'city'         => $shippingAddress->city,
                'zip'          => $shippingAddress->postcode,
                'country_code' => $shippingAddress->country->iso2,
                'email'        => $shippingAddress->contact_email,
                'phone'        => $shippingAddress->contact_phone,
            ],
            'items' => $items,
        ];

        $createResponse = Http::withToken($this->apiKey)
            ->post(self::BASE_URL . 'orders', $payload);

        if ($createResponse->failed()) {
            throw new \RuntimeException(
                'Printful createOrder failed: ' . $createResponse->body()
            );
        }

        $printfulOrderId = $createResponse->json('data.id');

        $confirmResponse = Http::withToken($this->apiKey)
            ->post(self::BASE_URL . "orders/{$printfulOrderId}/confirm");

        if ($confirmResponse->failed()) {
            throw new \RuntimeException(
                'Printful confirmOrder failed: ' . $confirmResponse->body()
            );
        }

        return $printfulOrderId;
    }
}
