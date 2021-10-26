<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class StripeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // 1. Authenticate into Stripe
        // 2. Set up intent
    }
}
