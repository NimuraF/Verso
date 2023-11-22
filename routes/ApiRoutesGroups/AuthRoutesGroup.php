<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH Routes
|--------------------------------------------------------------------------
|
*/

Route::middleware(['api'])->controller(AuthController::class)->group( function () {
    Route::post('/login', 'login')->middleware(['guest']); //LOGIN METHOD
    Route::post('/registration', 'registration')->middleware(['guest']); //REGISTRATION METHOD
    Route::get('/currentUser', 'currentUser'); //GET CURRENT USER INSTANCE METHOD
    Route::post('/logout', 'logout')->middleware(['auth']); //LOGOUT METHOD
    Route::post('/token', 'token'); //Update tokens pair
});