<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuggyUserExportDemoTest extends TestCase
{
    use RefreshDatabase;

    public function test_any_authenticated_user_can_access_the_user_export_demo(): void
    {
        User::factory()->count(2)->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('buggy-user-export-demo.index'))
            ->assertOk()
            ->assertJsonStructure([
                'users' => [
                    '*' => ['id', 'name', 'email', 'is_admin'],
                ],
            ]);
    }
}
