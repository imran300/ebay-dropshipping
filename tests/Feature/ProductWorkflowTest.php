<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_view_update_and_delete_products(): void
    {
        $user = User::factory()->create();

        $createResponse = $this->actingAs($user)->post(route('products.store'), [
            'title' => 'Wireless Keyboard',
            'sku' => 'KB-001',
            'category' => 'Accessories',
            'supplier_name' => 'North Supply',
            'source_url' => 'https://example.com/keyboard',
            'cost' => 22.50,
            'target_price' => 49.99,
            'stock_quantity' => 8,
            'listing_status' => 'draft',
            'notes' => 'High margin starter product.',
        ]);

        $createResponse->assertRedirect(route('products.index'));

        $product = Product::query()
            ->where('user_id', $user->id)
            ->where('title', 'Wireless Keyboard')
            ->firstOrFail();

        $this->actingAs($user)
            ->get(route('products.index'))
            ->assertOk()
            ->assertSee('Wireless Keyboard');

        $this->actingAs($user)->put(route('products.update', $product), [
            'title' => 'Wireless Keyboard Pro',
            'sku' => 'KB-001',
            'category' => 'Accessories',
            'supplier_name' => 'North Supply',
            'source_url' => 'https://example.com/keyboard',
            'cost' => 24.00,
            'target_price' => 54.99,
            'stock_quantity' => 6,
            'listing_status' => 'ready',
            'notes' => 'Updated margin target.',
        ])->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'title' => 'Wireless Keyboard Pro',
            'listing_status' => 'ready',
        ]);

        $this->actingAs($user)
            ->delete(route('products.destroy', $product))
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
