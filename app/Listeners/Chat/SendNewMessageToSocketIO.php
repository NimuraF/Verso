<?php

namespace App\Listeners\Chat;

use App\Events\ChatEvents\NewChatMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class SendNewMessageToSocketIO implements ShouldQueue
{

    public string $connection = 'redis';

    public function handle(NewChatMessage $event) : void
    {
        Redis::publish('messages', json_encode([
            'chat_id' => $event->message->chat_id,
            'author_id' => $event->message->author_id,
            'message_body' => $event->message->message_body,
            'created_at' => $event->message->created_at
        ]));
    }
}
