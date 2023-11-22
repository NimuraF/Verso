<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\User\ChatActionsController;
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
include __DIR__."/ApiRoutesGroups/AuthRoutesGroup.php";

/* USER ACTIONS GROUP */
include __DIR__."/ApiRoutesGroups/UserActionsRoutesGroup.php";

/* CHAT ACTIONS GROUP */
include __DIR__."/ApiRoutesGroups/ChatActionsRoutesGroup.php";