<?php

namespace App\Providers;

use App\PaymentTypes\StripePayment;
use Illuminate\Support\ServiceProvider;
use Lunar\Admin\Support\Facades\LunarPanel;
use Lunar\Shipping\ShippingPlugin;
use Lunar\Facades\Payments;
use Lunar\Facades\Telemetry;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        LunarPanel::panel(fn ($panel) => $panel->plugin(ShippingPlugin::make()))
            ->register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Telemetry::optOut();

        Payments::extend('stripe', fn () => app(StripePayment::class));
    }
}
