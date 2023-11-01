<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UsersChats extends Pivot
{

    protected $table = 'users_chats';

    protected $primaryKey = 'id';

    protected $fillable = [
        'first_user_id',
        'second_user_id',
        'name'
    ];


}
