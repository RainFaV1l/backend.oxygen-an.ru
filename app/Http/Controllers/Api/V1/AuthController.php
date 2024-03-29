<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Resources\UserResource;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:api')->except('login', 'refresh');

    }

    public function login(LoginRequest $request) {

        $credentials = $request->only(['email', 'password']);

        $token = auth('api')->attempt($credentials);

        if(!$token) return response()->json(['error' => 'Неверный логин или пароль'], 401);

        return $this->respondWithToken($token);

    }

    public function user() {

        try {

            return new UserResource(response()->json(auth('api')->user()));

        } catch (TokenExpiredException $e) {

            return response()->json(['error' => 'Token has expired'], 401);

        } catch (TokenInvalidException $e) {

            return response()->json(['error' => 'Token is invalid'], 401);

        } catch (JWTException $e) {

            return response()->json(['error' => 'JWT error'], 401);
        }

    }

    public function logout() {

        try {

            auth('api')->logout();

            return response()->json(['msg' => 'Successfully logged out']);

        } catch (TokenExpiredException $e) {

            return response()->json(['error' => 'Token has expired'], 401);

        } catch (TokenInvalidException $e) {

            return response()->json(['error' => 'Token is invalid'], 401);

        } catch (JWTException $e) {

            return response()->json(['error' => 'JWT error'], 401);
        }

    }

    public function refresh() {

        try {

            config([
                'jwt.blacklist_enabled' => false
            ]);

            return $this->respondWithToken(auth('api')->refresh());

        } catch (TokenExpiredException $e) {

            return response()->json(['error' => 'Token has expired'], 401);

        } catch (TokenInvalidException $e) {

            return response()->json(['error' => 'Token is invalid'], 401);

        } catch (JWTException $e) {

            return response()->json(['error' => 'JWT error'], 401);

        } finally {

            config([
                'jwt.blacklist_enabled' => true
            ]);

        }

    }

    public function respondWithToken($token) {

        return response()->json([
            'access_token' => $token,
            'type' => 'Bearer',
            'expires_in' => \Config::get('jwt.ttl') * 60,
        ]);

    }

}
