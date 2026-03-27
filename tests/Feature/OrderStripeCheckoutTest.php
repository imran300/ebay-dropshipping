<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\StripeCheckoutService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Mockery;
use Tests\TestCase;

class OrderStripeCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_user_can_start_stripe_checkout_for_their_order(): void
    {
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORDER-101',
            'sale_price' => 19.99,
            'quantity' => 1,
            'fulfillment_status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $stripeCheckoutService = Mockery::mock(StripeCheckoutService::class);
        $stripeCheckoutService
            ->shouldReceive('createSession')
            ->once()
            ->andReturn([
                'id' => 'cs_test_123',
                'url' => 'https://checkout.stripe.com/c/pay/cs_test_123',
            ]);

        $this->app->instance(StripeCheckoutService::class, $stripeCheckoutService);

        $this->actingAs($user)
            ->post(route('orders.checkout', $order))
            ->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_123');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'pending',
            'stripe_checkout_session_id' => 'cs_test_123',
        ]);
    }

    public function test_checkout_success_marks_order_as_paid(): void
    {
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORDER-102',
            'sale_price' => 49.50,
            'quantity' => 1,
            'fulfillment_status' => 'pending',
            'payment_status' => 'pending',
            'stripe_checkout_session_id' => 'cs_test_456',
        ]);

        $stripeCheckoutService = Mockery::mock(StripeCheckoutService::class);
        $stripeCheckoutService
            ->shouldReceive('retrieveSession')
            ->once()
            ->with('cs_test_456')
            ->andReturn([
                'id' => 'cs_test_456',
                'payment_status' => 'paid',
                'payment_intent' => 'pi_test_789',
            ]);

        $this->app->instance(StripeCheckoutService::class, $stripeCheckoutService);

        $this->actingAs($user)
            ->get(route('orders.checkout.success', $order).'?session_id=cs_test_456')
            ->assertRedirect(route('orders.index'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
            'stripe_checkout_session_id' => 'cs_test_456',
            'stripe_payment_intent_id' => 'pi_test_789',
        ]);

        $this->assertNotNull($order->fresh()->paid_at);
    }

    public function test_user_cannot_start_checkout_for_another_users_order(): void
    {
        $owner = User::factory()->create();
        $attacker = User::factory()->create();

        $order = Order::create([
            'user_id' => $owner->id,
            'order_number' => 'ORDER-103',
            'sale_price' => 15.00,
            'quantity' => 1,
            'fulfillment_status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $this->actingAs($attacker)
            ->post(route('orders.checkout', $order))
            ->assertForbidden();
    }
}
