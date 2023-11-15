<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Http\Requests\NewChatMessageRequest;
use App\Http\Resources\ChatColletion;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ChatController extends Controller
{
    
    protected Chat $chat;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Get all user chats
     *
     * @param Request $request
     * @return ChatColletion
     */
    public function allChats(Request $request) : ResourceCollection 
    {
        $userWithChats = $request->user()->with(['chats.last_message' => function (Relation $query) {
            $query->orderBy('id', 'DESC');
        }])->first();

        return ChatResource::collection($userWithChats->chats);
    }

    /**
     * Get chat messages
     *
     * @param Request $request
     * @param string $id
     * @return ResourceCollection
     */
    public function getChatMessages(Request $request, string $id) : ResourceCollection
    {
        return MessageResource::collection($this->chat->findOrFail($id)->messages()->simplePaginate(50));
    }

    /**
     * Send new message into chat
     *
     * @param NewChatMessageRequest $request
     * @param string $id
     * @return MessageResource
     */
    public function sendNewChatMessage(NewChatMessageRequest $request, Message $message, string $chat_id) : MessageResource 
    {
        if ($request->user()->cannot('createMessage', $this->chat->findOrFail($chat_id))) {
            throw new AuthorizationException();
        }

        $message = $message->create([
            'chat_id' => $chat_id,
            'author_id' => request()->user()->id,
            'message_body' => $request->input('message_body')
        ]);

        NewMessage::dispatch($message);

        return new MessageResource($message);
    }

    public function deleteMessage(Request $request) {

    }

}
