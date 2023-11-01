<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH Routes
|--------------------------------------------------------------------------
|
*/

Route::middleware(['api'])->controller(AuthController::class)->group( function () {

    Route::post('/login', 'login')->middleware(['guest']); //LOGIN METHOD

    Route::get('/currentUser', 'currentUser'); //GET CURRENT USER INSTANCE METHOD

    Route::post('/registration', 'registration')->middleware(['guest']); //REGISTRATION METHOD

    Route::post('/logout', 'logout')->middleware(['auth']); //LOGOUT METHOD

});