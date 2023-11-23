<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Pivot\UsersChats;
use App\Models\User;
use App\Services\Authorization\ValidateUserPermission;

class ChatPolicy
{

    protected ValidateUserPermission $permissionValidator;

    public function __construct(ValidateUserPermission $permissionValidator)
    {
        $this->permissionValidator = $permissionValidator;
    }

    /**
     * Checking if the user can join the chat
     *
     * @param User $user
     * @param Chat $chat
     * @return boolean
     */
    public function connectToChat(User $user, Chat $chat) : bool
    {
        if($this->permissionValidator->vallidatePermission($user, 'connect-to-chat')) 
        {
            if (!UsersChats::where([['user_id', '=', $user->id], ['chat_id', '=', $chat->id]])->first())
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Checking whether the user can write messages in this chat
     *
     * @param User $user
     * @param Chat $chat
     * @return boolean
     */
    public function createMessage(User $user, Chat $chat) : bool
    {
        if ($this->permissionValidator->vallidatePermission($user, 'create-message')) 
        {
            if(UsersChats::where([['chat_id', '=', $chat->id], ['user_id', '=', $user->id]])->first()) 
            {
                return true;
            } 
        }

        return false;
    }

    /**
     * Checking if the user can delete the current message
     *
     * @param User $user
     * @param Chat $chat
     * @param Message $message
     * @return boolean
     */
    public function deleteMessage(User $user, Chat $chat, Message $message) : bool
    {
        if($user->id === $chat->author_id || $user->id === $message->author_id) {
            return true;
        }

        return false;
    }

}
