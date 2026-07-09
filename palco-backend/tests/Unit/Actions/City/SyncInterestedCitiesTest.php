<?php

namespace Tests\Unit\Actions\City;

use App\Actions\City\SyncInterestedCities;
use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncInterestedCitiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_replaces_previous_set(): void
    {
        $user = User::factory()->create();
        [$c1, $c2, $c3] = City::factory()->count(3)->create();

        (new SyncInterestedCities())->handle($user, [$c1->id, $c2->id]);
        $result = (new SyncInterestedCities())->handle($user, [$c2->id, $c3->id]);

        $this->assertCount(2, $result);
        $this->assertDatabaseMissing('city_user', ['int_user_id' => $user->id, 'int_city_id' => $c1->id]);
        $this->assertDatabaseHas('city_user', ['int_user_id' => $user->id, 'int_city_id' => $c2->id]);
        $this->assertDatabaseHas('city_user', ['int_user_id' => $user->id, 'int_city_id' => $c3->id]);
    }
}