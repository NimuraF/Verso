<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RolesPermissions extends Pivot
{
    
    protected $table = 'roles_permissions';

    protected $primaryKey = 'id';

    protected $fillable = ['role_id', 'permission_id'];

}
