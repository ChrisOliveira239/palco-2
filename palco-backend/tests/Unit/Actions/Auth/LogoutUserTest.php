<?php

namespace Tests\Unit\Actions\Auth;

use App\Actions\Auth\LogoutUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_deletes_only_the_current_token(): void
    {
        $user = User::factory()->create();
        $currentToken = $user->createToken('current');
        $otherToken = $user->createToken('other');

        $user->withAccessToken($currentToken->accessToken);

        (new LogoutUser())->handle($user);

        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $currentToken->accessToken->id]);
        $this->assertDatabaseHas('personal_access_tokens', ['id' => $otherToken->accessToken->id]);
    }
}