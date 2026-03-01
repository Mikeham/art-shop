<?php

namespace App\Providers;

use App\Lunar\Extensions\ManageVariantIdentifiersExtension;
use App\Lunar\Pages\ManageVariantIdentifiers as AppManageVariantIdentifiers;
use App\PaymentTypes\StripePayment;
use App\Services\PrintfulService;
use Illuminate\Support\ServiceProvider;
use Lunar\Admin\Support\Facades\LunarPanel;
use Lunar\Facades\Payments;
use Lunar\Facades\Telemetry;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        LunarPanel::register();

        LunarPanel::extensions([
            \App\Lunar\Pages\ManageVariantIdentifiers::class => [
                ManageVariantIdentifiersExtension::class,
            ],
        ]);

        $this->app->bind(
            \Lunar\Admin\Filament\Resources\ProductVariantResource\Pages\ManageVariantIdentifiers::class,
            AppManageVariantIdentifiers::class,
        );

        $this->app->singleton(PrintfulService::class, fn () =>
            new PrintfulService(config('services.printful.key'))
        );
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
