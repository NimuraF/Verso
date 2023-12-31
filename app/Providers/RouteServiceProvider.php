<?php

namespace App\Providers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->setRouteParamsBindings();

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')->group(base_path('routes/api.php'));
        });
    }

    /**
     * Set all route params bindings
     *
     * @return void
     */
    private function setRouteParamsBindings() : void 
    {
        Route::bind('chat', function (string $value) : Chat { 
            return Chat::findOrFail($value); 
        });

        Route::bind('message', function (string $value) : Message {
            return Message::findOrFail($value);
        });

        Route::bind('profile', function (string $value) : User {
            return User::findOrFail($value);
        });
    }
}
