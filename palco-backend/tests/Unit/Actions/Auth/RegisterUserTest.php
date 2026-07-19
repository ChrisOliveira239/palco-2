<?php

namespace Tests\Unit\Actions\Auth;

use App\Actions\Auth\RegisterUser;
use App\Enums\UserRole;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_creates_user_with_hashed_password_and_default_role(): void
    {
        $city = City::factory()->create();

        $result = app(RegisterUser::class)->handle([
            'name' => 'Christian',
            'email' => 'christian@example.com',
            'password' => 'password123',
            'city_ids' => [$city->id],
        ]);

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertNotEmpty($result['token']);

        $user = $result['user'];
        $this->assertSame(UserRole::User, $user->usu_role);
        $this->assertTrue(Hash::check('password123', $user->password));
    }
}