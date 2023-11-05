<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatColletion;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    
    /**
     * Get all user chats
     *
     * @param Request $request
     * @return ChatColletion
     */
    public function allChats(Request $request) : ChatColletion {

        $userWithChats = $request->user()->with(['chats.last_message' => function (Relation $query) {
            $query->orderBy('id', 'DESC');
        }])->first();

        return new ChatColletion($userWithChats->chats);

    }

}
