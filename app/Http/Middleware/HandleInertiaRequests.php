<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Lunar\Facades\CartSession;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'cart'  => $this->getCartData(),
            'flash' => [
                'success' => $request->session()->get('success'),
            ],
        ];
    }

    private function getCartData(): ?array
    {
        try {
            $cart = CartSession::current(calculate: false);

            if (! $cart) {
                return null;
            }

            $cart->calculate();

            $lines = $cart->lines->map(function ($line) {
                $purchasable = $line->purchasable;

                return [
                    'id' => $line->id,
                    'quantity' => $line->quantity,
                    'unit_price' => $line->unitPrice?->formatted(),
                    'sub_total' => $line->subTotal?->formatted(),
                    'product_name' => $purchasable?->product?->translateAttribute('name'),
                    'variant_label' => $purchasable?->getOption(),
                    'image' => $purchasable?->getThumbnailImage(),
                ];
            })->values()->all();

            return [
                'total' => $cart->total?->formatted(),
                'item_count' => $cart->lines->sum('quantity'),
                'lines' => $lines,
            ];
        } catch (\Throwable) {
            return null;
        }
    }
}
