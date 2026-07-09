<?php

namespace Tests\Feature\Events;

use App\Models\City;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_events_with_future_sessions_in_requested_city(): void
    {
        $city = City::factory()->create();
        $event = Event::factory()->create(['eve_city_id' => $city->id]);
        $event->sessions()->create([
            'ses_start_at' => now()->addDays(7),
            'ses_pricing_type' => 'free',
        ]);

        $response = $this->getJson('/api/events?'.http_build_query(['city_ids' => [$city->id]]));

        $response->assertOk();
        $response->assertJsonPath('data.0.id', $event->id);
        $response->assertJsonCount(1, 'data');
    }

    public function test_index_excludes_events_from_other_cities(): void
    {
        $city = City::factory()->create();
        $otherCity = City::factory()->create();
        $event = Event::factory()->create(['eve_city_id' => $otherCity->id]);
        $event->sessions()->create([
            'ses_start_at' => now()->addDays(7),
            'ses_pricing_type' => 'free',
        ]);

        $response = $this->getJson('/api/events?'.http_build_query(['city_ids' => [$city->id]]));

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    public function test_index_excludes_events_with_only_past_sessions(): void
    {
        $city = City::factory()->create();
        $event = Event::factory()->create(['eve_city_id' => $city->id]);
        $event->sessions()->create([
            'ses_start_at' => now()->subDays(7),
            'ses_pricing_type' => 'free',
        ]);

        $response = $this->getJson('/api/events?'.http_build_query(['city_ids' => [$city->id]]));

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    public function test_index_requires_city_ids(): void
    {
        $response = $this->getJson('/api/events');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('city_ids');
    }

    public function test_index_rejects_nonexistent_city_id(): void
    {
        $response = $this->getJson('/api/events?'.http_build_query(['city_ids' => [99999]]));

        $response->assertStatus(422);
    }
}