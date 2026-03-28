<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class SettingsAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_users_do_not_receive_settings_nav_and_cannot_access_settings(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('can_manage_settings', false)
                ->where('navigation.primary.0.label', 'Overview')
                ->where('navigation.primary.1.label', 'Products')
                ->where('navigation.primary.2.label', 'Listings')
                ->where('navigation.primary.3.label', 'Orders')
                ->missing('navigation.primary.4')
                ->where('navigation.mobile.0.label', 'Overview')
                ->where('navigation.mobile.1.label', 'Products')
                ->where('navigation.mobile.2.label', 'Listings')
                ->where('navigation.mobile.3.label', 'Orders')
                ->missing('navigation.mobile.4')
            );

        $this->actingAs($user)
            ->get(route('settings.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->post(route('settings.store'), [
                'ebay_fee_rate' => 12.95,
                'default_shipping_cost' => 4.99,
                'low_stock_threshold' => 5,
                'min_margin_threshold' => 8.50,
            ])
            ->assertForbidden();
    }

    public function test_admin_users_receive_settings_nav_and_can_access_settings(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('can_manage_settings', true)
                ->where('navigation.primary.4.label', 'Settings')
                ->where('navigation.primary.4.route', 'settings.index')
                ->where('navigation.mobile.4.label', 'Settings')
                ->where('navigation.mobile.4.route', 'settings.index')
            );

        $this->actingAs($admin)
            ->get(route('settings.index'))
            ->assertOk();
    }
}
