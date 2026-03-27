<?php

namespace App\Services;

use App\Models\Order;
use RuntimeException;
use Stripe\StripeClient;

class StripeCheckoutService
{
    public function createSession(Order $order, string $successUrl, string $cancelUrl): array
    {
        $client = $this->client();

        $session = $client->checkout->sessions->create([
            'mode' => 'payment',
            'line_items' => [[
                'quantity' => max((int) $order->quantity, 1),
                'price_data' => [
                    'currency' => config('services.stripe.currency', 'usd'),
                    'unit_amount' => (int) round((float) $order->sale_price * 100),
                    'product_data' => [
                        'name' => $order->product?->title
                            ?? $order->listing?->title
                            ?? ($order->order_number ? "Order {$order->order_number}" : "Order #{$order->id}"),
                    ],
                ],
            ]],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'order_id' => (string) $order->id,
                'user_id' => (string) $order->user_id,
            ],
        ]);

        return [
            'id' => $session->id,
            'url' => $session->url,
        ];
    }

    public function retrieveSession(string $sessionId): array
    {
        $session = $this->client()->checkout->sessions->retrieve($sessionId);

        return [
            'id' => $session->id,
            'payment_status' => $session->payment_status,
            'payment_intent' => $session->payment_intent,
        ];
    }

    private function client(): StripeClient
    {
        $secret = config('services.stripe.secret');

        if (! is_string($secret) || $secret === '') {
            throw new RuntimeException('Missing Stripe secret key configuration.');
        }

        return new StripeClient($secret);
    }
}
