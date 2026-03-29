<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuggyUserSearchDemoTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_reach_the_buggy_search_demo(): void
    {
        User::factory()->count(2)->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('buggy-user-search-demo.index', ['q' => 'a']))
            ->assertOk()
            ->assertJsonStructure([
                'query',
                'users' => [
                    '*' => ['id', 'name', 'email'],
                ],
            ]);
    }
}
