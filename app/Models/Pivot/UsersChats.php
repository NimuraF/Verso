<?php

namespace App\Models\Pivot;

use App\Models\Message;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UsersChats extends Pivot
{

    protected $table = 'users_chats';

    protected $primaryKey = 'id';

    protected $fillable = ['chat_id', 'user_id', 'chat_role'];

}
