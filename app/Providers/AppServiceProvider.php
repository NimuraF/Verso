<?php

namespace App\Providers;

use App\Models\Chat;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //$this->app->makeWith(Chat::class, ['id' => request()->])
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
