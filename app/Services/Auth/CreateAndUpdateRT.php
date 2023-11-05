<?php

namespace App\Services\Auth;

use App\Models\RefreshTokens;
use App\Models\User;
use Illuminate\Http\Request;

class CreateAndUpdateRT {

    /**
     * Get the current user based on his refresh token
     *
     * @param Request $request
     * @return User|null
     */
    static public function getUserByRT(Request $request) : User|null {

        $rt = $request->cookie('refresh-token', null);

        return $rt ? self::validateRT($rt) : null; 

    }

    /**
     * Validate current token, remove o and get user instance associated with the current token
     *
     * @param string $rt
     * @return User|null
     */
    static public function validateRT(string $rt) : User|null {

        $rtEntity = RefreshTokens::where('token', '=', $rt)->first();

        $user = $rtEntity ? User::find($rtEntity->user_id) : null;

        if($rtEntity) {

            $rtEntity->delete();

        }

        return $user;

    }

    /**
     * Create and set new RT token for given user instance
     *
     * @param User $user
     * @return string
     */
    static public function createRT(User $user) : string {

        $new_rt = hash("SHA256", $user.time());

        while(RefreshTokens::where('token', '=', $new_rt)->first()) {

            $new_rt = hash("SHA256", $user.time());

        }

        RefreshTokens::create([
            'user_id' => $user->id,
            'token' => $new_rt
        ]);

        return $new_rt;

    }

}