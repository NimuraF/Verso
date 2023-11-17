<?php

namespace App\Listeners;

use App\Events\NewChatParticipant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class AddNewChatParticipantToSocketIO
{

    public function __construct()
    {
        
    }

    public function handle(NewChatParticipant $event): void
    {
        Redis::publish('rooms', json_encode([
            'chat' => $event->chat,
            'user' => $event->user
        ]));
    }
}
