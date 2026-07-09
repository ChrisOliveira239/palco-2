<?php

namespace Tests\Unit\Actions\Auth;

use App\Actions\Auth\AuthenticateUser;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_returns_token_for_correct_credentials(): void
    {
        User::factory()->create([
            'email' => 'christian@example.com',
            'password' => 'password123',
        ]);

        $result = (new AuthenticateUser())->handle([
            'email' => 'christian@example.com',
            'password' => 'password123',
        ]);

        $this->assertArrayHasKey('user', $result);
        $this->assertNotEmpty($result['token']);
    }

    public function test_handle_throws_authentication_exception_for_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'christian@example.com',
            'password' => 'password123',
        ]);

        $this->expectException(AuthenticationException::class);

        (new AuthenticateUser())->handle([
            'email' => 'christian@example.com',
            'password' => 'wrong-password',
        ]);
    }

    public function test_handle_throws_authentication_exception_for_nonexistent_email(): void
    {
        $this->expectException(AuthenticationException::class);

        (new AuthenticateUser())->handle([
            'email' => 'ghost@example.com',
            'password' => 'password123',
        ]);
    }
}