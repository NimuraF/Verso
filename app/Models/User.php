<?php

namespace App\Models;

use App\Models\Pivot\UsersChats;
use App\Models\Pivot\UsersRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $with = ['roles'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * MANY-TO-MANY RELATIONSHIP USERS-ROLES
     *
     * @return BelongsToMany
     */
    public function roles() : BelongsToMany 
    {
        return $this->belongsToMany(Role::class, 'users_roles', 'user_id', 'role_id', 'id', 'id')->using(UsersRoles::class);
    }

    /**
     * Chats, initiated by current user instance
     *
     * @return BelongsToMany
     */
    public function chats() : BelongsToMany
    {
        return $this->belongsToMany(Chat::class, 'users_chats', 'user_id', 'chat_id', 'id', 'id')->using(UsersChats::class)->as('chats');
    }

}
