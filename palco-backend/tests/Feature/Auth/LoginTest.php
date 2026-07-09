<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_correct_credentials_returns_token(): void
    {
        User::factory()->create([
            'email' => 'christian@example.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'christian@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['user' => ['id', 'name', 'email', 'role'], 'token']);
    }

    public function test_login_with_wrong_password_fails(): void
    {
        User::factory()->create([
            'email' => 'christian@example.com',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'christian@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }

    public function test_login_with_nonexistent_email_fails_with_same_message(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'ghost@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401);
        $response->assertJsonPath('message', 'Credenciais inválidas.');
    }
}