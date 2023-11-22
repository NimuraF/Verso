<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Chat\ChatController;

Route::middleware(['auth'])->prefix('chats')->controller(ChatController::class)->group(function () {
    Route::get('/{chat}', 'getChatMessages')->whereNumber(['chat']); //Get chat messages
});

Route::get('/search', [ChatController::class, 'chatsSearch']);