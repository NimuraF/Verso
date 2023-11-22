<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    
    protected User $user;

    public function __construct()
    {
        $this->user = request()->user();
    }

    /**
     * Show user profile
     *
     * @param User $profile
     * @return UserResource
     */
    public function showUserProfile(Request $request, User $profile) : UserResource
    {
        return new UserResource($profile);
    }

}
