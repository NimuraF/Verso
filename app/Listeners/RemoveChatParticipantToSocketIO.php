<?php

namespace App\Listeners;

use App\Events\RemoveChatParticipant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class RemoveChatParticipantToSocketIO implements ShouldQueue
{
    public string $connection = 'redis';

    public function handle(RemoveChatParticipant $event): void
    {
        Redis::publish('rooms', json_encode([
            'action' => 'remove_user',
            'chat' => $event->chat,
            'user' => $event->user
        ]));
    }

}
