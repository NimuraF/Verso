<?php

namespace App\Models;

use App\Models\Pivot\UsersChats;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chat extends Model
{
    
    protected $table = 'chats';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'avatar', 'about', 'author_id', 'modified_id', 'close'];

    protected $attributes = [
        'avatar' => null,
        'modified_id' => null,
        'about' => null
    ];

    protected $casts = [
        'close' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | LOCAL SCOPES EVENTS
    |--------------------------------------------------------------------------
    */
    public function scopeOpen(Builder $query) : Builder
    {
        return $query->where([['close', '=', 0]]);
    }

    public function scopeModifiedIdSearch(Builder $query, string $mofiedId) : Builder
    {
        return $query->where([['modified_id', 'LIKE', '%'.$mofiedId.'%']]);
    }




    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function messages() : HasMany 
    {
        return $this->hasMany(Message::class, 'chat_id', 'id')->orderBy('created_at', 'DESC');
    }

    public function last_message() : HasOne 
    {
        return $this->hasOne(Message::class, 'chat_id', 'id');
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_chats', 'chat_id', 'user_id', 'id', 'id')->using(UsersChats::class);
    }
    
}
