<?php

use App\Http\Controllers\User\ChatActionsController;
use App\Http\Controllers\User\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth'])->prefix('users')->group(function () {

    /* CHATS ACTIONS */
    Route::prefix('chats')->controller(ChatActionsController::class)->group(function () {
        Route::get('/', 'getAllUserChats'); //Get list of all chats for current authenticated user
        Route::post('/{chat}', 'connectToChat')->whereNumber(['chat']); //Connect to chat current authenticated user
        Route::delete('/{chat}', 'disconnectFromChat')->whereNumber(['chat']); //Leave from chat current authenticated user
        Route::post('/{chat}/send', 'sendNewMessageInChat')->whereNumber(['chat']); //Send new message in chat
        Route::delete('/{chat}/{message}', 'removeMessageInChat')->whereNumber(['chat', 'message']); //Remove message in chat
    });

    /* PROFILE ACTIONS */
    Route::controller(UserProfileController::class)->group(function() {
        Route::get('/{profile}', 'showUserProfile')->whereNumber(['user']);
    });

});