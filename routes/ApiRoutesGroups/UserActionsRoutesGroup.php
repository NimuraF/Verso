<?php

use App\Http\Controllers\User\ChatActionsController;
use App\Http\Controllers\User\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('users')->group(function () {

    /* CHATS ACTIONS */
    Route::prefix('chats')->controller(ChatActionsController::class)->group(function () {
        Route::get('/', 'getAllUserChats'); //Get list of all chats for current authenticated user
        Route::post('/{chat}', 'connectToChat')->whereNumber(['chat']); //Connect to chat current authenticated user
        Route::delete('/{chat}', 'disconnectFromChat')->whereNumber(['chat']); //Leave from chat current authenticated user
        Route::post('/{chat}/send', 'sendNewMessageInChat')->whereNumber(['chat']); //Send new message into chat
        Route::delete('/{chat}/{message}', 'removeMessageInChat')->whereNumber(['chat', 'message']); //Send new message into chat
    });

    /* PROFILE ACTIONS */
    Route::controller(UserProfileController::class)->group(function() {
        Route::get('/{profile}', 'showUserProfile')->whereNumber(['user']);
    });

});