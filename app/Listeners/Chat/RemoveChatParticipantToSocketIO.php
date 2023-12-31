<?php

namespace App\Listeners\Chat;

use App\Events\ChatEvents\RemoveChatParticipant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class RemoveChatParticipantToSocketIO implements ShouldQueue
{
    public string $connection = 'redis';

    public function handle(RemoveChatParticipant $event): void
    {
        Redis::publish('rooms', json_encode([
            'action' => 'disconnect-from-chat',
            'chat' => $event->chat,
            'user' => $event->user
        ]));
    }

}
