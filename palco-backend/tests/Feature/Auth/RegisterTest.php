<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_and_returns_token(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Christian',
            'email' => 'christian@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertCreated();
        $response->assertJsonStructure(['user' => ['id', 'name', 'email', 'role'], 'token']);
        $response->assertJsonPath('user.role', 'user');
        $this->assertArrayNotHasKey('password', $response->json('user'));

        $this->assertDatabaseHas('users', ['email' => 'christian@example.com']);
    }

    public function test_register_fails_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'christian@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Christian',
            'email' => 'christian@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_register_fails_with_missing_fields(): void
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_register_fails_when_password_confirmation_does_not_match(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Christian',
            'email' => 'christian@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }
}