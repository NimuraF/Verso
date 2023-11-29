<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Chat\ChatController;

Route::middleware(['auth'])->prefix('chats')->controller(ChatController::class)->group(function () {
    Route::post('/', 'createNewChat'); //Create new chat
    Route::get('/{chat}', 'getChatInfo')->whereNumber(['chat']); //Get chat info
    Route::put('/{chat}', 'updateChatInfo')->whereNumber(['chat']); //Update chat entity
    Route::get('/{chat}/messages', 'getChatMessages')->whereNumber(['chat']); //Get chat messages
});

Route::get('/search', [ChatController::class, 'chatsSearch']);