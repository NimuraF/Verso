<?php

namespace App\Listeners\Chat;

use App\Events\ChatEvents\NewChatParticipant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class AddNewChatParticipantToSocketIO implements ShouldQueue
{
    public string $connection = 'redis';

    public function handle(NewChatParticipant $event): void
    {
        Redis::publish('rooms', json_encode([
            'action' => 'connect-to-chat',
            'chat' => $event->chat,
            'user' => $event->user
        ]));
    }
}
