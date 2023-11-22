<?php

namespace App\Models;

use App\Models\Pivot\RolesPermissions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{

    protected $table = 'permissions';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'about'];
    
    /**
     * MANY-TO-MANY RELATIONSHIP PERMISSIONS-ROLES
     *
     * @return BelongsToMany
     */
    public function roles() : BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'roles_permissions', 'permission_name', 'role_id', 'name', 'id')->using(RolesPermissions::class);
    }

}
