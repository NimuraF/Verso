<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
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

        return $this->sendResponseWithCookies($request, $token, new UserResource(auth()->user()));

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

        $user = auth()->user();

        return $this->sendResponseWithCookies($request, $token ?: '', (new UserResource($user)));

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
     * Setting jwt-token in cookies
     *
     * @param UserResource $user
     * @param string $token
     * @return JsonResponse
     */
    private function sendResponseWithCookies(Request $request, string $token, UserResource|null $user = null, int|null $TTL = null, int|null $rTTL = null) : JsonResponse {

        $domain = $request->header('Origin');

        $response = $user ? $user->response() : response()->json();

        return $response
            ->cookie('token', $token, $TTL ?: config('jwt.ttl'), '/', $domain);
        
    }

}