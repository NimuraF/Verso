<?php

namespace App\Http\Controllers;

use App\Events\NewChatParticipant;
use App\Events\NewMessage;
use App\Events\RemoveChatParticipant;
use App\Http\Requests\ChatSearchRequest;
use App\Http\Requests\NewChatMessageRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Pivot\UsersChats;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ChatController extends Controller
{
    
    protected Chat $chat;
    protected User|null $user;

    public function __construct(Chat $chat)
    {
        $this->user = request()->user();
        $this->chat = $chat;
    }

    /**
     * Chat searching method
     *
     * @param ChatSearchRequest $request
     * @return ResourceCollection
     */
    public function chatsSearch(ChatSearchRequest $request) : ResourceCollection
    {
        $chats = $this->chat->where([['modified_id', 'LIKE', '%'.$request->input('chat_modified_id').'%']])->limit(10)->get();

        return ChatResource::collection($chats);
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

    public function deleteMessage(Request $request) {

    }

}
