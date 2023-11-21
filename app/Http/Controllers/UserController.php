<?php

namespace App\Http\Controllers;

use App\Events\NewChatParticipant;
use App\Events\NewMessage;
use App\Events\RemoveChatParticipant;
use App\Http\Requests\UserActionsRequests\NewChatMessageRequest;
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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    
    protected User|null $user;
    protected UsersChats $usersChats;
    
    public function __construct(UsersChats $usersChats)
    {
        $this->user = request()->user();
        $this->usersChats = $usersChats;
    }

    /**
     * Get all current authenticated user chats
     *
     * @return ResourceCollection
     */
    public function getAllUserChats(Request $request) : ResourceCollection
    {
        $userWithChats = $this->user->with(['chats.last_message' => function (Relation $query) {
            $query->orderBy('id', 'DESC');
        }])->first();

        return ChatResource::collection($userWithChats->chats);
    }

    /**
     * Connect current authenticated user to chat
     *
     * @param ConnectToChatRequest $request
     * @param UsersChats $usersChats
     * @return ChatResource
     */
    public function connectToChat(Request $request, Chat $chat) : ChatResource
    {
        $status = $this->usersChats->where([['user_id', '=', $this->user->id], ['chat_id', '=', $chat->id]])->first();

        if ($status) { throw new AuthorizationException('You are already in this chat'); }

        $this->usersChats->create(['user_id' => $this->user->id, 'chat_id' => $chat->id]);

        NewChatParticipant::dispatch($chat, $this->user);

        return new ChatResource($chat);
    }

    /**
     * Disconnect current authenticated user from chat
     *
     * @param Request $request
     * @param Chat $chat
     * @return JsonResponse
     */
    public function disconnectFromChat(Request $request, Chat $chat) : JsonResponse
    {
        $status = $this->usersChats->where([['user_id', '=', $this->user->id], ['chat_id', '=', $chat->id]])->delete();

        RemoveChatParticipant::dispatch($chat, $this->user);

        return response()->json($status ? ['data' => 'Succesfully disconnected'] : ['error' => 'You are not in this chat']);
    }

    /**
     * Create new message
     *
     * @param NewChatMessageRequest $request
     * @param Chat $chat
     * @param Message $message
     * @return MessageResource
     */
    public function sendNewMessageInChat(NewChatMessageRequest $request, Chat $chat, Message $message) : MessageResource
    {
        $message->create([
            'chat_id' => $chat->id,
            'author_id' => $this->user->id,
            'message_body' => $request->safe()->only(['message_body'])
        ]);

        NewMessage::dispatch($message);

        return new MessageResource($message);
    }

}
