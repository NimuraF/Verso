<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserActionsRequests\Profile\UpdateUserProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Files\Save\SaveNewFile;
use App\Services\User\Profile\UpdateUserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    
    protected User|null $user;
    protected UpdateUserProfile $updater;

    public function __construct(UpdateUserProfile $updater)
    {
        $this->user = request()->user();
        $this->updater = $updater;
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

    /**
     * Update current authenticated user profile
     *
     * @param UpdateUserProfileRequest $request
     * @return UserResource
     */
    public function updateUserProfile(UpdateUserProfileRequest $request) : UserResource
    {
        if ($request->file('avatar')) 
        { 
            $this->updater->updateUserAvatar($this->user, $request->file('avatar')); 
        }
        $this->user->fill($request->safe()->except(['avatar']))->save();
        return new UserResource($this->user);
    }

}
