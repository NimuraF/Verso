<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatActionsRequests\ChatInfoRequest;
use App\Http\Requests\ChatActionsRequests\ChatSearchRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\User;
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
     * Get chat info
     *
     * @param Request $request
     * @param Chat $chat
     * @return ChatResource
     */
    public function getChatInfo(ChatInfoRequest $request, Chat $chat) : ChatResource
    {
        return new ChatResource($chat);
    }

    /**
     * Get chat messages
     *
     * @param Request $request
     * @param string $id
     * @return ResourceCollection
     */
    public function getChatMessages(ChatInfoRequest $request, Chat $chat) : ResourceCollection
    {
        return MessageResource::collection($chat->messages()->simplePaginate(50));
    }

    //public function updateChatInfo()

}
