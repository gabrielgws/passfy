<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quantity',
        'sold_quantity',
        'min_per_order',
        'max_per_order',
        'sale_start_date',
        'sale_end_date',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_start_date' => 'datetime',
        'sale_end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getAvailableQuantityAttribute(): int
    {
        return $this->quantity - $this->sold_quantity;
    }

    public function getIsSoldOutAttribute(): bool
    {
        return $this->available_quantity <= 0;
    }

    public function getIsOnSaleAttribute(): bool
    {
        $now = now();
        $saleStarted = !$this->sale_start_date || $this->sale_start_date <= $now;
        $saleNotEnded = !$this->sale_end_date || $this->sale_end_date > $now;

        return $this->is_active && $saleStarted && $saleNotEnded && !$this->is_sold_out;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
            ->whereRaw('quantity > sold_quantity');
    }
}
