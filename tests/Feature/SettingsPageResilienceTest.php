<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SettingsPageResilienceTest extends TestCase
{
    use RefreshDatabase;

    public function test_settings_page_can_render_with_missing_settings_prop_shape(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('settings.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Settings/Index')
                ->where('settings.ebay_fee_rate', 0.1295)
                ->where('settings.default_shipping_cost', 0)
                ->where('settings.low_stock_threshold', 5)
                ->where('settings.min_margin_threshold', 0)
            );
    }
}
