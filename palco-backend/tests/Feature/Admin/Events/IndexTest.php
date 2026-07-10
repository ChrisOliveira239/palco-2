<?php

namespace Tests\Feature\Admin\Events;

use App\Models\City;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_lists_all_events_without_city_ids_or_session_filter(): void
    {
        $admin = User::factory()->admin()->create();
        $city = City::factory()->create();
        $event = Event::factory()->create(['eve_city_id' => $city->id]);
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/events');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $event->id);
        $response->assertJsonPath('data.0.active', true);
    }

    public function test_non_admin_cannot_list_admin_events(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/admin/events');

        $response->assertStatus(403);
    }

    public function test_unauthenticated_cannot_list_admin_events(): void
    {
        $response = $this->getJson('/api/admin/events');

        $response->assertStatus(401);
    }

    public function test_filters_by_search(): void
    {
        $admin = User::factory()->admin()->create();
        $city = City::factory()->create();
        $matching = Event::factory()->create(['eve_city_id' => $city->id, 'eve_title' => 'Show de Rock']);
        Event::factory()->create(['eve_city_id' => $city->id, 'eve_title' => 'Feira de Artesanato']);
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/events?'.http_build_query(['search' => 'Rock']));

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $matching->id);
    }
}