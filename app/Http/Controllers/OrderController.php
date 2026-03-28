<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\StripeCheckoutService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use RuntimeException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->where('user_id', $request->user()->id)
            ->with(['product', 'listing'])
            ->latest()
            ->get();

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function checkout(Order $order, Request $request, StripeCheckoutService $stripeCheckoutService)
    {
        $this->authorizeOrder($order, $request);

        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.index')->with('success', 'Order is already paid.');
        }

        try {
            $session = $stripeCheckoutService->createSession(
                $order->loadMissing(['product', 'listing']),
                route('orders.checkout.success', $order, true).'?session_id={CHECKOUT_SESSION_ID}',
                route('orders.index', [], true),
            );
        } catch (RuntimeException $exception) {
            return redirect()->route('orders.index')->with('error', $exception->getMessage());
        }

        $order->update([
            'payment_status' => 'pending',
            'stripe_checkout_session_id' => $session['id'],
        ]);

        return redirect()->away($session['url']);
    }

    public function checkoutSuccess(Order $order, Request $request, StripeCheckoutService $stripeCheckoutService)
    {
        $this->authorizeOrder($order, $request);

        $sessionId = $request->query('session_id');

        if (! is_string($sessionId) || $sessionId === '') {
            return redirect()->route('orders.index')->with('error', 'Missing Stripe checkout session.');
        }

        if ($order->stripe_checkout_session_id !== null && $order->stripe_checkout_session_id !== $sessionId) {
            return redirect()->route('orders.index')->with('error', 'Checkout session does not match this order.');
        }

        try {
            $session = $stripeCheckoutService->retrieveSession($sessionId);
        } catch (RuntimeException $exception) {
            return redirect()->route('orders.index')->with('error', $exception->getMessage());
        }

        $metadata = $session['metadata'] ?? [];
        if (! is_array($metadata)
            || ($metadata['order_id'] ?? '') !== (string) $order->id
            || ($metadata['user_id'] ?? '') !== (string) $order->user_id) {
            return redirect()->route('orders.index')->with('error', 'Checkout session does not match this order.');
        }

        if (($session['payment_status'] ?? null) !== 'paid') {
            return redirect()->route('orders.index')->with('error', 'Payment was not completed.');
        }

        $order->update([
            'payment_status' => 'paid',
            'stripe_checkout_session_id' => $sessionId,
            'stripe_payment_intent_id' => isset($session['payment_intent']) ? (string) $session['payment_intent'] : null,
            'paid_at' => now(),
        ]);

        return redirect()->route('orders.index')->with('success', 'Stripe payment captured successfully.');
    }

    private function authorizeOrder(Order $order, Request $request): void
    {
        abort_unless($order->user_id === $request->user()->id, 403);
    }
}
