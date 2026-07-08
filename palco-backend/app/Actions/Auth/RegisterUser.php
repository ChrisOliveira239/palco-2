<?php

namespace App\Actions\Auth;

use App\Enums\UserRole;
use App\Models\User;
use Laravel\Sanctum\NewAccessToken;

class RegisterUser
{
    public function handle(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'usu_role' => UserRole::User,
        ]);

        /** @var NewAccessToken $token */
        $token = $user->createToken('api-token');

        return [
            'user' => $user,
            'token' => $token->plainTextToken,
        ];
    }
}