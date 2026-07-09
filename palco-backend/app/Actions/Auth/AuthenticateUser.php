<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthenticateUser
{
    public function handle(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw new AuthenticationException('Credenciais inválidas.');
        }

        $token = $user->createToken('api-token');

        return [
            'user' => $user,
            'token' => $token->plainTextToken,
        ];
    }
}
