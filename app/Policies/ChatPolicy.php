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
    protected UsersChats $usersChatsRelation;

    public function __construct(ValidateUserPermission $permissionValidator, UsersChats $relation)
    {
        $this->permissionValidator = $permissionValidator;
        $this->usersChatsRelation = $relation;
    }

    /**
     * Checking if the user can view any information about this chat
     *
     * @param User $user
     * @param Chat $chat
     * @return boolean
     */
    public function getChatInfo(User $user, Chat $chat) : bool
    {
        if ($chat->close) 
        {
            if (!$this->isUserChatParticipant($user, $chat))
            {
                return false;
            }
        }
        return true;
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
        if($this->permissionValidator->validatePermission($user, 'connect-to-chat')) 
        {
            if (!$this->isUserChatParticipant($user, $chat))
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Checking if the user can create new chats
     *
     * @param User $user
     * @param Chat $chat
     * @return boolean
     */
    public function createNewChat(User $user) : bool
    {
        if ($this->permissionValidator->validatePermission($user, 'create-new-chat'))
        {
            return true;
        }
        return false;
    }

    /**
     * Checking whether the current user can update the transmitted chat
     *
     * @param User $user
     * @param Chat $chat
     * @return boolean
     */
    public function updateExistingChat(User $user, Chat $chat) : bool
    {
        if 
        (
            $user->id === $chat->author_id
            || $this->usersChatsRelation->where
        ) 
        {
            return true;
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
        if ($this->permissionValidator->validatePermission($user, 'create-message')) 
        {
            if($this->isUserChatParticipant($user, $chat)) 
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
        if($user->id === $chat->author_id || $user->id === $message->author_id) 
        {
            return true;
        }

        return false;
    }

    /**
     * INTERNAL METHOD
     * Checking if the user is a chat participant
     *
     * @param User $user
     * @param Chat $chat
     * @return UsersChats|null
     */
    private function isUserChatParticipant(User $user, Chat $chat) : UsersChats|null 
    {
        return $this->usersChatsRelation->where([['user_id', '=', $user->id], ['chat_id', '=', $chat->id]])->first();
    }

}
