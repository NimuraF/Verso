<?php

namespace App\Services\Authorization;

use App\Models\User;

interface ValidateUserPermission {

    public function validatePermission(User $user, string $permissionName) : bool;

}