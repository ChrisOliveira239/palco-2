<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<City>
 */
class CityFactory extends Factory
{
    protected $model = City::class;

    public function definition(): array
    {
        return [
            'cid_name' => fake()->unique()->city(),
            'cid_state' => fake()->randomElement(['SP', 'RJ', 'MG', 'BA', 'PR', 'RS']),
        ];
    }
}