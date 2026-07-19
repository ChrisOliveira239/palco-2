<?php

namespace App\Actions\Auth;

use App\Actions\City\SyncInterestedCities;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\NewAccessToken;

class RegisterUser
{
    public function __construct(
        private SyncInterestedCities $syncInterestedCities,
    ) {}

    public function handle(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'usu_role' => UserRole::User,
            ]);

            $this->syncInterestedCities->handle($user, $data['city_ids']);

            /** @var NewAccessToken $token */
            $token = $user->createToken('api-token');

            return [
                'user' => $user,
                'token' => $token->plainTextToken,
            ];
        });
    }
}