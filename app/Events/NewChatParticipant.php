<?php

namespace App\Events;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatParticipant
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;

    public Chat $chat;

    public function __construct(Chat $chat, User $user)
    {
        $this->user = $user;
        $this->chat = $chat;
    }

}
