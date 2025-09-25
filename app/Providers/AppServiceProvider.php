<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $origins = \App\Models\Project::pluck('client_url')->filter()->toArray();
        // config(['cors.allowed_origins' => $origins]);
    }
}
