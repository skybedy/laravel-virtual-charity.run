<?php

namespace App\Providers;

use App\Services\ResultService;
use Illuminate\Support\ServiceProvider;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
