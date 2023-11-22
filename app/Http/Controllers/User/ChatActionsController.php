<?php

namespace App\Http\Controllers\User;

use App\Events\ChatEvents\NewChatMessage;
use App\Events\ChatEvents\NewChatParticipant;
use App\Events\ChatEvents\RemoveChatMessage;
use App\Events\ChatEvents\RemoveChatParticipant;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserActionsRequests\NewChatMessageRequest;
use App\Http\Requests\UserActionsRequests\RemoveChatMessageRequest;
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

class ChatActionsController extends Controller
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
        $message = $message->create([
            'chat_id' => $chat->id,
            'author_id' => $this->user->id,
            'message_body' => $request->safe()->only(['message_body'])['message_body']
        ]);

        NewChatMessage::dispatch($message);

        return new MessageResource($message);
    }

    /**
     * Remove message from chat
     *
     * @param RemoveChatMessageRequest $request
     * @param Chat $chat
     * @param Message $message
     * @return JsonResponse
     */
    public function removeMessageInChat(RemoveChatMessageRequest $request, Chat $chat, Message $message) : JsonResponse 
    {
        $status = $message->delete();

        RemoveChatMessage::dispatch($chat, $message);

        return response()->json($status ? 
        ['data' => 'Succesfully deleted message'] 
        : 
        ['error' => 'The message does not exist or has already been deleted']);
    }

}
