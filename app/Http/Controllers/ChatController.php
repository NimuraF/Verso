<?php

namespace App\Http\Controllers;

use App\Events\NewChatParticipant;
use App\Events\NewMessage;
use App\Http\Requests\NewChatMessageRequest;
use App\Http\Resources\ChatColletion;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Pivot\UsersChats;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ChatController extends Controller
{
    
    protected Chat $chat;
    protected User $user;

    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
        $this->user = auth()->user();
    }

    /**
     * Get all user chats
     *
     * @param Request $request
     * @return ChatColletion
     */
    public function allChats(Request $request) : ResourceCollection 
    {
        $userWithChats = $this->user->with(['chats.last_message' => function (Relation $query) {
            $query->orderBy('id', 'DESC');
        }])->first();

        return ChatResource::collection($userWithChats->chats);
    }

    /**
     * Add new chat
     *
     * @param Request $request
     * @param UsersChats $usersChats
     * @param string $id
     * @return ChatResource
     */
    public function addNewChat(Request $request, UsersChats $usersChats, string $id) : ChatResource
    {
        $chat = $this->chat->findOrFail($id);

        $usersChats->create([
            'chat_id' => $chat->id,
            'user_id' => $this->user->id
        ]);

        NewChatParticipant::dispatch($chat, $this->user);

        return new ChatResource($chat);
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
