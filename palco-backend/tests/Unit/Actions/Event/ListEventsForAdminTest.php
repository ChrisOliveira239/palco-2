<?php

namespace Tests\Unit\Actions\Event;

use App\Actions\Event\ListEventsForAdmin;
use App\Models\City;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListEventsForAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_returns_events_regardless_of_sessions(): void
    {
        $city = City::factory()->create();
        Event::factory()->create(['eve_city_id' => $city->id]);
        $pastOnly = Event::factory()->create(['eve_city_id' => $city->id]);
        $pastOnly->sessions()->create(['ses_start_at' => now()->subDays(3), 'ses_pricing_type' => 'free']);

        $result = (new ListEventsForAdmin())->handle(null, null, 'active');

        $this->assertCount(2, $result);
    }

    public function test_handle_filters_by_search(): void
    {
        $city = City::factory()->create();
        $match = Event::factory()->create(['eve_city_id' => $city->id, 'eve_title' => 'Show de Rock']);
        Event::factory()->create(['eve_city_id' => $city->id, 'eve_title' => 'Feira de Artesanato']);

        $result = (new ListEventsForAdmin())->handle('Rock', null, 'active');

        $this->assertCount(1, $result);
        $this->assertSame($match->id, $result->first()->id);
    }

    public function test_handle_filters_by_city(): void
    {
        $city = City::factory()->create();
        $otherCity = City::factory()->create();
        $match = Event::factory()->create(['eve_city_id' => $city->id]);
        Event::factory()->create(['eve_city_id' => $otherCity->id]);

        $result = (new ListEventsForAdmin())->handle(null, $city->id, 'active');

        $this->assertCount(1, $result);
        $this->assertSame($match->id, $result->first()->id);
    }

    public function test_handle_status_controls_active_flag_visibility(): void
    {
        $city = City::factory()->create();
        $active = Event::factory()->create(['eve_city_id' => $city->id]);
        $inactive = Event::factory()->create(['eve_city_id' => $city->id]);
        $inactive->delete();

        $onlyActive = (new ListEventsForAdmin())->handle(null, null, 'active');
        $onlyInactive = (new ListEventsForAdmin())->handle(null, null, 'inactive');
        $all = (new ListEventsForAdmin())->handle(null, null, 'all');

        $this->assertSame([$active->id], $onlyActive->pluck('id')->all());
        $this->assertSame([$inactive->id], $onlyInactive->pluck('id')->all());
        $this->assertCount(2, $all);
    }

    public function test_handle_loads_city_and_sessions_count(): void
    {
        $city = City::factory()->create();
        $event = Event::factory()->create(['eve_city_id' => $city->id]);
        $event->sessions()->create(['ses_start_at' => now()->addDays(1), 'ses_pricing_type' => 'free']);

        $result = (new ListEventsForAdmin())->handle(null, null, 'active');

        $this->assertTrue($result->first()->relationLoaded('city'));
        $this->assertSame(1, $result->first()->sessions_count);
    }
}