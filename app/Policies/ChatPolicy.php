<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Pivot\UsersChats;
use App\Models\User;

class ChatPolicy
{

    /**
     * Checking whether the user can write messages in this chat
     *
     * @param User $user
     * @param Chat $chat
     * @return boolean
     */
    public function createMessage(User $user, Chat $chat) : bool
    {
        $isParticipant = UsersChats::where([['chat_id', '=', $chat->id], ['user_id', '=', $user->id]])->first();

        return $isParticipant ? true : false;
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
