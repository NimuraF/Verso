<?php

namespace App\Providers\ServiceProviders;

use App\Services\Authorization\Permissions\DefaultValidator;
use App\Services\Authorization\ValidateUserPermission;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class PermissionValidatorProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton(ValidateUserPermission::class, function (Application $app) : DefaultValidator {
            return new DefaultValidator();
        });
    }
    
}
