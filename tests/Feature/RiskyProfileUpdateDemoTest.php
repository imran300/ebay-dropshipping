<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiskyProfileUpdateDemoTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_demo_allows_mass_assignment_of_admin_flag(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->patch(route('profile.risky-update-demo'), [
                'name' => 'Updated Name',
                'is_admin' => true,
            ])
            ->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'is_admin' => true,
        ]);
    }
}
