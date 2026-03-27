<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'marketplace_item_id',
        'title',
        'external_url',
        'price',
        'quantity',
        'status',
        'last_synced_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'last_synced_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(Revision::class);
    }
}
