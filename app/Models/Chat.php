<?php

namespace App\Models;

use App\Models\Pivot\UsersChats;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chat extends Model
{
    
    protected $table = 'chats';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'avatar', 'about', 'author_id', 'modified_id'];

    /**
     * Messages for current chat instance
     *
     * @return HasMany
     */
    public function messages() : HasMany 
    {
        return $this->hasMany(Message::class, 'chat_id', 'id')->orderBy('created_at', 'DESC');
    }

    /**
     * Last message on chat
     *
     * @return HasOne
     */
    public function last_message() : HasOne 
    {
        return $this->hasOne(Message::class, 'chat_id', 'id');
    }

    /**
     * Get list of all chat participants
     *
     * @return BelongsToMany
     */
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_chats', 'chat_id', 'user_id', 'id', 'id')->using(UsersChats::class);
    }
}
