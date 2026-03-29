<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuggyUserReportDemoTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_reach_the_buggy_report_demo(): void
    {
        User::factory()->count(3)->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('buggy-user-report-demo.index'))
            ->assertOk()
            ->assertJsonStructure([
                'users' => [
                    '*' => ['id', 'name', 'peer_count'],
                ],
            ]);
    }
}
