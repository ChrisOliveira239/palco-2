<?php

namespace Tests\Unit\Actions\City;

use App\Actions\City\ListInterestedCities;
use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListInterestedCitiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_returns_only_cities_of_given_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $city = City::factory()->create();
        $otherCity = City::factory()->create();

        $user->interestedCities()->attach($city->id);
        $otherUser->interestedCities()->attach($otherCity->id);

        $result = (new ListInterestedCities())->handle($user);

        $this->assertCount(1, $result);
        $this->assertSame($city->id, $result->first()->id);
    }
}