<?php

namespace App\Http\Middleware\Api;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Auth extends Middleware
{

    /**
     * Action when user is unauthenticated
     *
     * @param Request $request
     * @param array $guards
     * @return void
     */
    protected function unauthenticated(Request $request, array $guards) : void {

        throw new AuthenticationException('You need to log in first');

    }

}