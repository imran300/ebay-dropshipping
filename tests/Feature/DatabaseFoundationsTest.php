<?php

namespace Tests\Feature;

use App\Models\Listing;
use App\Models\Order;
use App\Models\Product;
use App\Models\Revision;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseFoundationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_one_tables_and_columns_exist(): void
    {
        $this->assertTrue(Schema::hasTable('revisions'));
        $this->assertTrue(Schema::hasColumns('revisions', [
            'listing_id',
            'field',
            'old_value',
            'new_value',
            'created_at',
        ]));

        $this->assertTrue(Schema::hasTable('settings'));
        $this->assertTrue(Schema::hasColumns('settings', [
            'user_id',
            'key',
            'value',
        ]));

        $this->assertTrue(Schema::hasColumn('orders', 'delivered_at'));
    }

    public function test_listing_delete_cascades_revisions_and_user_has_settings(): void
    {
        $user = User::factory()->create();

        $product = Product::create([
            'user_id' => $user->id,
            'title' => 'Desk Lamp',
            'cost' => 10.00,
            'target_price' => 24.99,
            'stock_quantity' => 3,
        ]);

        $listing = Listing::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'title' => 'Desk Lamp Listing',
            'price' => 24.99,
            'status' => 'draft',
        ]);

        $revision = Revision::create([
            'listing_id' => $listing->id,
            'field' => 'title',
            'old_value' => 'Old',
            'new_value' => 'New',
        ]);

        Setting::create([
            'user_id' => $user->id,
            'key' => 'low_stock_threshold',
            'value' => '5',
        ]);

        $this->assertCount(1, $listing->revisions);
        $this->assertCount(1, $user->settings);

        $listing->delete();

        $this->assertDatabaseMissing('revisions', [
            'id' => $revision->id,
        ]);
    }

    public function test_order_delivered_at_is_fillable_and_casted_to_datetime(): void
    {
        $user = User::factory()->create();
        $deliveredAt = now()->subDay();

        $order = Order::create([
            'user_id' => $user->id,
            'delivered_at' => $deliveredAt->toDateTimeString(),
        ]);

        $order->refresh();

        $this->assertInstanceOf(Carbon::class, $order->delivered_at);
        $this->assertSame(
            $deliveredAt->toDateTimeString(),
            $order->delivered_at->toDateTimeString()
        );
    }
}
