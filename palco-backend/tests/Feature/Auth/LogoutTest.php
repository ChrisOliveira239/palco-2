<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout_revokes_current_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $response = $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->postJson('/api/logout');

        $response->assertNoContent();
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $token->accessToken->id]);

        // Sanctum's RequestGuard caches the resolved user for the guard instance's
        // lifetime; without forgetting it, this second request would reuse that
        // cache instead of re-validating the (now deleted) token.
        $this->app['auth']->forgetGuards();

        $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->getJson('/api/user')
            ->assertStatus(401);
    }

    public function test_logout_without_token_fails(): void
    {
        $this->postJson('/api/logout')->assertStatus(401);
    }
}