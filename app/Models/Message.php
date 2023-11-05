<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    
    protected $table = 'chats_messages';

    protected $primaryKey = 'id';

    protected $fillable = ['chat_id', 'author_id', 'message_body'];

}
