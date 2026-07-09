<?php

namespace Database\Factories;

use App\Enums\PricingType;
use App\Models\Event;
use App\Models\EventSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EventSession>
 */
class EventSessionFactory extends Factory
{
    protected $model = EventSession::class;

    public function definition(): array
    {
        return [
            'ses_event_id' => Event::factory(),
            'ses_start_at' => now()->addDays(7),
            'ses_end_at' => null,
            'ses_pricing_type' => PricingType::Free,
            'ses_price' => null,
            'ses_capacity' => null,
        ];
    }

    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'ses_start_at' => now()->subDays(7),
        ]);
    }
}