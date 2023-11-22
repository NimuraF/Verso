<?php

namespace App\Events\ChatEvents;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RemoveChatMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Chat $chat;
    protected Message $message;

    public function __construct(Chat $chat, Message $message)
    {
        $this->chat = $chat;
        $this->message = $message;
    }

}
