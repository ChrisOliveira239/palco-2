<?php

namespace Tests\Feature\UserCities;

use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_syncs_interested_cities(): void
    {
        $user = User::factory()->create();
        [$c1, $c2] = City::factory()->count(2)->create();
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/user/cities', ['city_ids' => [$c1->id, $c2->id]]);

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $this->assertDatabaseHas('city_user', ['int_user_id' => $user->id, 'int_city_id' => $c1->id]);
        $this->assertDatabaseHas('city_user', ['int_user_id' => $user->id, 'int_city_id' => $c2->id]);
    }

    public function test_second_sync_replaces_previous_set(): void
    {
        $user = User::factory()->create();
        [$c1, $c2, $c3] = City::factory()->count(3)->create();
        Sanctum::actingAs($user);

        $this->putJson('/api/user/cities', ['city_ids' => [$c1->id, $c2->id]])->assertOk();
        $response = $this->putJson('/api/user/cities', ['city_ids' => [$c2->id, $c3->id]]);

        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $this->assertDatabaseMissing('city_user', ['int_user_id' => $user->id, 'int_city_id' => $c1->id]);
        $this->assertDatabaseHas('city_user', ['int_user_id' => $user->id, 'int_city_id' => $c2->id]);
        $this->assertDatabaseHas('city_user', ['int_user_id' => $user->id, 'int_city_id' => $c3->id]);
    }

    public function test_unauthenticated_cannot_sync_interested_cities(): void
    {
        $city = City::factory()->create();

        $response = $this->putJson('/api/user/cities', ['city_ids' => [$city->id]]);

        $response->assertStatus(401);
    }

    public function test_missing_city_ids_fails(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/user/cities', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['city_ids']);
    }

    public function test_empty_city_ids_fails(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/user/cities', ['city_ids' => []]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['city_ids']);
    }

    public function test_duplicate_city_ids_fails(): void
    {
        $user = User::factory()->create();
        $city = City::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/user/cities', ['city_ids' => [$city->id, $city->id]]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['city_ids.1']);
    }

    public function test_nonexistent_city_id_fails(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson('/api/user/cities', ['city_ids' => [999999]]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['city_ids.0']);
    }
}
