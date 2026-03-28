<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuggyAnnouncementDemoTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_reach_the_buggy_announcement_demo(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('buggy-announcement-demo.index', [
                'message' => '<strong>announcement</strong>',
            ]))
            ->assertOk();
    }
}
