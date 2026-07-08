<?php

namespace App\Http\Controllers;

use App\Actions\Auth\AuthenticateUser;
use App\Actions\Auth\LogoutUser;
use App\Actions\Auth\RegisterUser;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterUser $registerUser)
    {
        $result = $registerUser->handle($request->validated());

        return response()->json([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ], 201);
    }

    public function login(LoginRequest $request, AuthenticateUser $authenticateUser)
    {
        $result = $authenticateUser->handle($request->validated());

        return response()->json([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ]);
    }

    public function logout(Request $request, LogoutUser $logoutUser)
    {
        $logoutUser->handle($request->user());

        return response()->noContent();
    }
}
