<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Resources\User as UserResource;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //

    public function register(UserRegisterRequest $request) {
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => bcrypt($request->password),
        ]);

        if (!$token = JWTAuth::attempt($request->only(['email', 'password']))) {
            return abort(401);
        };

        return (new UserResource($request->user()))->additional([
            'meta' => [
                'token' => $token,
            ],
        ]);
    }


    public function login(UserLoginRequest $request){

        if (!$token = JWTAuth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'errors' => [
                  'email' => ['Sorry we cant find you with those details'],
                ],
            ],422);
        };

        return (new UserResource($request->user()))->additional([
            'meta' => [
                'token' => $token,
            ],
        ]);

    }

    public function user(Request $request){
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }
        return new UserResource($user);
    }

    public function logout(){
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

}
