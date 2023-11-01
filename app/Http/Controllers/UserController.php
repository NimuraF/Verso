<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function id() {
        
        //var_dump(User::where('id', '=', 1)->first()->chats);
        return User::where('id', '=', 1)->first()->chats;

    }

}
