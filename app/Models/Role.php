<?php

namespace App\Models;

use App\Models\Pivot\UsersRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{

    protected $table = 'roles';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'about'];

    /**
     * MANY-TO-MANY RELATIONSHIP ROLES-USERS
     *
     * @return BelongsToMany
     */
    public function users() : BelongsToMany {
        return $this->belongsToMany(User::class, 'users_roles', 'role_id', 'user_id', 'id', 'id')->using(UsersRoles::class);
    }

}
