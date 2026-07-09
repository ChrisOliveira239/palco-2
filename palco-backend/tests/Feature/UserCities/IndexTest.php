<?php

namespace Tests\Feature\UserCities;

use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_interested_cities_of_authenticated_user(): void
    {
        $user = User::factory()->create();
        $city = City::factory()->create();
        $user->interestedCities()->attach($city->id);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user/cities');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $city->id);
    }

    public function test_does_not_return_cities_interested_by_other_users(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $city = City::factory()->create();
        $otherUser->interestedCities()->attach($city->id);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user/cities');

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    public function test_unauthenticated_cannot_list_interested_cities(): void
    {
        $response = $this->getJson('/api/user/cities');

        $response->assertStatus(401);
    }
}