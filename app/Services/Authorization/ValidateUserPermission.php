<?php

namespace App\Services\Authorization;

use App\Models\User;

class ValidateUserPermission {

    /**
     * Validate permission in user roles
     *
     * @param User $user
     * @param string $permission
     * @return boolean
     */
    public function validatePermission(User $user, string $permissionName) : bool
    {
        foreach($user->roles as $role) {
            foreach($role->permissions as $permission) {
                if ($permission->name == $permissionName) {
                    return true;
                }
            }
        }
        return false;
    }

}