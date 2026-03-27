<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'listing_id',
        'product_id',
        'order_number',
        'buyer_name',
        'sale_price',
        'quantity',
        'fulfillment_status',
        'payment_status',
        'stripe_checkout_session_id',
        'stripe_payment_intent_id',
        'paid_at',
        'ordered_at',
        'shipped_at',
        'delivered_at',
        'tracking_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'sale_price' => 'decimal:2',
            'paid_at' => 'datetime',
            'ordered_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
