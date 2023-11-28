<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthActionsRequests\UserLoginRequest;
use App\Http\Requests\AuthActionsRequests\UserRegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Auth\CreateAndUpdateRT;
use App\Services\Websocket\CreateJWTForWebsocket;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class AuthController extends Controller {

    /**
     * Login method
     *
     * @param Request $request
     * @return UserRerource
     */
    public function login(UserLoginRequest $request) : JsonResponse 
    {
        $authParams = $request->only(['email', 'password']);

        if(!$token = auth()->attempt($authParams)) {
            throw new AuthenticationException('Invalid login or password');
        }

        return $this->sendResponseWithCookies($request, $token, auth()->user());
    }

    /**
     * Registration method
     *
     * @param UserRegistrationRequest $request
     * @return JsonResponse
     */
    public function registration(UserRegistrationRequest $request) : JsonResponse 
    {
        $regParams = $request->safe()->only(['name', 'email', 'password']);

        $regParams['password'] = password_hash($regParams['password'], PASSWORD_DEFAULT);

        $newUser = User::create($regParams);

        $token = auth()->login($newUser);

        return $this->sendResponseWithCookies($request, $token, auth()->user());
    }

    /**
     * Get ccurent user instance method
     *
     * @param Request $request
     * @return UserResource|null
     */
    public function currentUser(Request $request): UserResource | null 
    {
        return $request->user() ? new UserResource($request->user()) : null;
    }

    /**
     * Logout method
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request) : JsonResponse 
    {
        auth()->logout(true);

        return $this->sendResponseWithCookies($request, '');
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function token(Request $request) : JsonResponse 
    {
        if (!$user = CreateAndUpdateRT::getUserByRT($request)) {
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
    private function sendResponseWithCookies(Request $request, string $token, User|null $user = null, int|null $TTL = null) : JsonResponse 
    {
        $domain = parse_url($request->header('Origin'))['host'];

        $userResource = $user ? new UserResource($user) : null; 

        return response()
            ->json([
                'data' => $userResource, 
                'expires_on' => time() + 3600,
                'websocket_jwt' => $user ? CreateJWTForWebsocket::createJWT($user) : null
            ])
            ->cookie('token', $token, $TTL ?: config('jwt.ttl'), '/', $domain)
            ->cookie('refresh-token', $user ? CreateAndUpdateRT::createRT($user) : '', 60*24*15, '/', $domain);
    }

}