<?php

namespace App\Providers;

use App\Services\ResultService;
use Illuminate\Support\ServiceProvider;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        /*
        nakonec nepoužito, ale ponechano pro inspiraci
        $this->app->bind(ResultService::class, function ($app) {

            if ($app->request->eventId == null) {
                throw new \Exception('Není uvedeno Id závodu.');
            }

            if (!is_numeric($app->request->eventId)) {
                throw new \InvalidArgumentException('Id závodu musí být číslo.');
            }

            return new ResultService($app->request->eventId);
        });*/

        $this->app->singleton(StripeClient::class, function ($app) {
            return new StripeClient('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');
        });


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
