<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UsersRoles extends Pivot
{

    protected $table = 'users_roles';

    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'role_id'];

}
