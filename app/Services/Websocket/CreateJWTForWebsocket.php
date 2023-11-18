<?php

namespace App\Services\Websocket;

use App\Models\User;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CreateJWTForWebsocket {

    static private string $key = 'websocket-key';

    /**
     * Create special websocket-jwt fro authenticated user
     *
     * @param User $user
     * @return string
     */
    static public function createJWT(User $user) : string 
    {
        $payload = [
            'iss' => 'http://api.verso.ru',
            'expires_on' => time() + 3600 * 24,
            'alg' => 'HS256',
            'user_id' => $user->id
        ];

        return JWT::encode($payload, self::$key, "HS256", null, null);
    }

}