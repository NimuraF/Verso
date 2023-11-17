<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


/* AUTH ROUTES */
include __DIR__."/auth.php";


/* CHATS ROUTES GROUP */
Route::middleware(['api', 'auth'])->prefix('chats')->controller(ChatController::class)->group( function () {
    Route::get('/', 'allChats');
    Route::get('/{id}', 'getChatMessages');
    Route::post('/{id}', 'sendNewChatMessage');
    Route::post('/connect/{id}', 'addNewChat');
});