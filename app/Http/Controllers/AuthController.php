<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Auth\CreateAndUpdateRT;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class AuthController extends Controller {

    /**
     * Login method
     *
     * @param Request $request
     * @return UserRerource
     */
    public function login(Request $request) : JsonResponse {

        $authParams = $request->only(['email', 'password']);

        if(!$token = auth()->attempt($authParams)) {

            throw new AuthenticationException('Invalid login or password');

        }

        Redis::publish('message', 'HEELO FROM PHP BY'.$request->input('email'));

        return $this->sendResponseWithCookies($request, $token, auth()->user());

    }

    /**
     * Registration method
     *
     * @param UserRegistrationRequest $request
     * @return JsonResponse
     */
    public function registration(UserRegistrationRequest $request) : JsonResponse {

        $regParams = $request->only(['name', 'email', 'password']);

        $passwordHash = password_hash($regParams['password'], PASSWORD_DEFAULT);

        $newUser = User::create([
            'name' => $regParams['name'], 
            'email' => $regParams['email'], 
            'password' => $passwordHash
        ]);

        if(!$newUser) {

            throw new ServiceUnavailableHttpException();

        }

        $token = auth()->login($newUser);

        return $this->sendResponseWithCookies($request, $token, auth()->user());

    }

    /**
     * Get ccurent user instance method
     *
     * @param Request $request
     * @return UserResource|null
     */
    public function currentUser(Request $request): UserResource | null {

        return $request->user() ? new UserResource($request->user()) : null;

    }

    /**
     * Logout method
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request) : JsonResponse {

        auth()->logout(true);

        return $this->sendResponseWithCookies($request, '');

    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function token(Request $request) : JsonResponse {

        $user = CreateAndUpdateRT::getUserByRT($request);

        if (!$user) {

            throw new AuthenticationException('Ivalid token, please, try again');

        }

        $jwt = auth()->login($user);

        return $this->sendResponseWithCookies($request, $jwt, $user);

    }

    /**
     * Setting jwt-token in cookies
     *
     * @param Request $request
     * @param string $token
     * @param User|null|null $user
     * @param integer|null|null $TTL
     * @return JsonResponse
     */
    private function sendResponseWithCookies(Request $request, string $token, User|null $user = null, int|null $TTL = null) : JsonResponse {

        $domain = parse_url($request->header('Origin'))['host'];

        $userResource = $user ? new UserResource($user) : null; 

        return response()
            ->json(['data' => $userResource, 'expires_on' => 1])
            ->cookie('token', $token, $TTL ?: config('jwt.ttl'), '/', $domain)
            ->cookie('refresh-token', $user ? CreateAndUpdateRT::createRT($user) : '', 60*24*15, '/', $domain);
        
    }

}