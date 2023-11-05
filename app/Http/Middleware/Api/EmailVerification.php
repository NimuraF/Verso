<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if
        (
            $request->user() instanceof MustVerifyEmail
            && !$request->user()->hasVerifiedEmail()

        ) 
        {
            throw new AuthorizationException('Please confirm your email. The letter has been sent to your mailbox!');
        };

        return $next($request);
    }
}
