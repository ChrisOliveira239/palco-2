<?php

namespace Tests\Unit\Actions\Event;

use App\Actions\Event\ListEvents;
use App\Models\City;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListEventsTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_filters_by_city_and_excludes_past_only_sessions(): void
    {
        $city = City::factory()->create();
        $otherCity = City::factory()->create();

        $matching = Event::factory()->create(['eve_city_id' => $city->id]);
        $matching->sessions()->create(['ses_start_at' => now()->addDays(3), 'ses_pricing_type' => 'free']);

        $wrongCity = Event::factory()->create(['eve_city_id' => $otherCity->id]);
        $wrongCity->sessions()->create(['ses_start_at' => now()->addDays(3), 'ses_pricing_type' => 'free']);

        $pastOnly = Event::factory()->create(['eve_city_id' => $city->id]);
        $pastOnly->sessions()->create(['ses_start_at' => now()->subDays(3), 'ses_pricing_type' => 'free']);

        $result = (new ListEvents())->handle([$city->id]);

        $this->assertCount(1, $result);
        $this->assertSame($matching->id, $result->first()->id);
    }

    public function test_handle_orders_by_next_session_at_ascending(): void
    {
        $city = City::factory()->create();

        $later = Event::factory()->create(['eve_city_id' => $city->id]);
        $later->sessions()->create(['ses_start_at' => now()->addDays(10), 'ses_pricing_type' => 'free']);

        $sooner = Event::factory()->create(['eve_city_id' => $city->id]);
        $sooner->sessions()->create(['ses_start_at' => now()->addDays(2), 'ses_pricing_type' => 'free']);

        $result = (new ListEvents())->handle([$city->id]);

        $this->assertSame($sooner->id, $result->first()->id);
        $this->assertSame($later->id, $result->last()->id);
    }
}
