<?php

namespace Tests\Feature\Auth;

use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_and_returns_token(): void
    {
        $city = City::factory()->create();

        $response = $this->postJson('/api/register', [
            'name' => 'Christian',
            'email' => 'christian@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'city_ids' => [$city->id],
        ]);

        $response->assertCreated();
        $response->assertJsonStructure(['user' => ['id', 'name', 'email', 'role'], 'token']);
        $response->assertJsonPath('user.role', 'user');
        $this->assertArrayNotHasKey('password', $response->json('user'));

        $this->assertDatabaseHas('users', ['email' => 'christian@example.com']);
    }

    public function test_register_syncs_interested_cities(): void
    {
        $cities = City::factory()->count(2)->create();

        $response = $this->postJson('/api/register', [
            'name' => 'Christian',
            'email' => 'christian@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'city_ids' => $cities->pluck('id')->all(),
        ]);

        $response->assertCreated();

        $user = User::where('email', 'christian@example.com')->firstOrFail();
        $this->assertSame($cities->pluck('id')->sort()->values()->all(), $user->interestedCities->pluck('id')->sort()->values()->all());
    }

    public function test_register_fails_with_empty_city_ids(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Christian',
            'email' => 'christian@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'city_ids' => [],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('city_ids');
    }

    public function test_register_fails_with_duplicate_email(): void
    {
        $city = City::factory()->create();
        User::factory()->create(['email' => 'christian@example.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Christian',
            'email' => 'christian@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'city_ids' => [$city->id],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_register_fails_with_missing_fields(): void
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'password', 'city_ids']);
    }

    public function test_register_fails_when_password_confirmation_does_not_match(): void
    {
        $city = City::factory()->create();

        $response = $this->postJson('/api/register', [
            'name' => 'Christian',
            'email' => 'christian@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
            'city_ids' => [$city->id],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('password');
    }
}