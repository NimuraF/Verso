<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {

        /*
        |--------------------------------------------------------------------------
        | CLIENT ERRORS
        |--------------------------------------------------------------------------
        */

        /* AUTH ERROR WITH 401 RESPONSE*/
        $this->renderable(function (AuthenticationException $error) {
            return response()->json([
                'error' => $error->getMessage() ?: 'You are not authenticated'
            ], 401);
        });

        /* AUTHORIZE ERROR WITH 403 RESPONSE */
        $this->renderable(function (AuthorizationException $error) {
            return response()->json([
                'error' => $error->getMessage() ?: 'You do not have the rights to perform this action'
            ], 403);
        });




        /*
        |--------------------------------------------------------------------------
        | SERVER ERRORS
        |--------------------------------------------------------------------------
        */

        /* SERVICE UNAVAILABLE ERROR WITH 503 RESPONSE */
        $this->renderable(function (ServiceUnavailableHttpException $error) {
            return response()->json([
                'error' => $error->getMessage() ?: 'Service unavailable, please try again later'
            ], 503);
        });




        /* ANOTHER ERRORS */
        $this->renderable(function (Throwable $error) {
            return response()->json(['error' => $error->getMessage()]);
        });


        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
