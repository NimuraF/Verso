<?php

namespace App\Listeners;

use App\Events\NewMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class SendNewMessageToSocketIO implements ShouldQueue
{

    public string $connection = 'redis';

    public function handle(NewMessage $event): void
    {
        Redis::publish('messages', json_encode([
            'chat_id' => $event->message->chat_id,
            'author_id' => $event->message->author_id,
            'message_body' => $event->message->message_body
        ]));
    }
}
