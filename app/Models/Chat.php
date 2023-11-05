<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chat extends Model
{
    
    protected $table = 'chats';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'avatar', 'about', 'author_id'];

    /**
     * Messages for current chat instance
     *
     * @return HasMany
     */
    public function messages() : HasMany {
        return $this->hasMany(Message::class, 'chat_id', 'id');
    }

    /**
     * First message on chat ()
     *
     * @return HasOne
     */
    public function last_message() : HasOne {
        return $this->hasOne(Message::class, 'chat_id', 'id');
    }

}
