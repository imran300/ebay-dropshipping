<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrameworkMisuseDemoAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_users_are_forbidden(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('framework-misuse-demo.index'))
            ->assertForbidden();
    }

    public function test_admin_users_can_access_the_demo_flow(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('framework-misuse-demo.index'))
            ->assertOk();
    }
}
