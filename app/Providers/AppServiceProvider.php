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
            return new StripeClient('sk_test_51PVCa82LSxhftJEam6p0Npc4iMggfZdpR6aeVDjmncI9nKQPxocVn2Am2F9uoXF2Q7cy4lr8DbQF6cUpO2Gkp8Qd00Yu5e5aN8');
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
