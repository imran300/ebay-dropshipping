<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'sku',
        'category',
        'supplier_name',
        'source_url',
        'cost',
        'target_price',
        'stock_quantity',
        'listing_status',
        'notes',
        'last_sold_at',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'target_price' => 'decimal:2',
            'last_sold_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getMarginPerUnitAttribute(): float
    {
        return round(((float) $this->target_price) - ((float) $this->cost), 2);
    }

    public function getPotentialProfitAttribute(): float
    {
        return round($this->margin_per_unit * (int) $this->stock_quantity, 2);
    }

    public function getInventoryValueAttribute(): float
    {
        return round(((float) $this->cost) * (int) $this->stock_quantity, 2);
    }
}
