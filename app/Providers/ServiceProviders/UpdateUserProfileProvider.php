<?php

namespace App\Providers\ServiceProviders;

use App\Services\User\Profile\UpdateUserProfile;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class UpdateUserProfileProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton(UpdateUserProfile::class, function (Application $app) : UpdateUserProfile {
            return new UpdateUserProfile();
        });
    }

}
