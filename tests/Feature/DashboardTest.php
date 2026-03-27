<?php

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_operational_metrics(): void
    {
        $user = User::factory()->create();

        $product = Product::create([
            'user_id' => $user->id,
            'title' => 'Gaming Mouse',
            'sku' => 'MOUSE-001',
            'category' => 'Accessories',
            'supplier_name' => 'Fast Supply',
            'source_url' => 'https://example.com/mouse',
            'cost' => 15.00,
            'target_price' => 34.99,
            'stock_quantity' => 4,
            'listing_status' => 'active',
        ]);

        $listing = Listing::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'title' => 'Gaming Mouse Listing',
            'price' => 34.99,
            'quantity' => 4,
            'status' => 'active',
        ]);

        Order::create([
            'user_id' => $user->id,
            'listing_id' => $listing->id,
            'product_id' => $product->id,
            'order_number' => 'ORDER-1001',
            'buyer_name' => 'Alex Buyer',
            'sale_price' => 34.99,
            'quantity' => 1,
            'fulfillment_status' => 'pending',
            'ordered_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard')
                ->where('stats.products', 1)
                ->where('stats.active_listings', 1)
                ->where('stats.pending_orders', 1)
                ->where('stats.low_stock', 1)
                ->has('recent_products', 1)
                ->has('recent_listings', 1)
                ->has('recent_orders', 1)
            );
    }
}
