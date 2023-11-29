<?php

namespace App\Services\User\Profile;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateUserProfile {

    /**
     * Update user profile avatar
     *
     * @param User $user
     * @param UploadedFile $file
     * @return string
     */
    public function updateUserAvatar(User $user, UploadedFile $file) : void
    {
        $oldavatarPath = $user->avatar;

        $path = Storage::putFile('avatars', $file);

        $user->avatar = $path;

        if ($oldavatarPath) 
        {
            Storage::delete($oldavatarPath);
        }
    }

}