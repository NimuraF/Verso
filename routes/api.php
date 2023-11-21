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


Route::middleware(['auth'])->prefix('users')->controller(UserController::class)->group(function () {

    /* CHATS ACTIONS */
    Route::prefix('chats')->group(function () {
        Route::get('/', 'getAllUserChats'); //Get list of all chats for current authenticated user
        Route::post('/{chat}', 'connectToChat')->whereNumber(['chat']); //Connect to chat current authenticated user
        //Route::delete('/{chat_id}'); //Leave from chat current authenticated user
    });

});

Route::middleware(['auth'])->prefix('chats')->controller(ChatController::class)->group(function () {

});

//Route::get('/{chat_id}', 'getChatInfo')->whereNumber(['chat_id']);

// Route::middleware(['auth'])->prefix('chats')->controller(UserController::class)->group(function () {
//     Route::get('/', 'getAllUserChats');
//     Route::get('/{chat_id}', 'getChatInfo')->whereNumber(['chat_id']);
//     Route::post('/{chat_id}', 'sendNewChatMessage')->whereNumber(['chat_id']);
//     Route::post('/connect/{chat_id}', 'connectToChat');
//     Route::delete('/disconnect/{id}', 'removeChat');
// });

Route::get('/search', [ChatController::class, 'chatsSearch']);