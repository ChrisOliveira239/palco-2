<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'eve_title' => fake()->sentence(3),
            'eve_synopsis' => fake()->paragraph(),
            'eve_type' => fake()->randomElement(['show', 'oficina', 'exposicao']),
            'eve_venue_name' => fake()->company(),
            'eve_city_id' => City::factory(),
            'eve_ticket_url' => null,
            'eve_poster_path' => null,
            'eve_created_by_id' => User::factory()->admin(),
        ];
    }
}
