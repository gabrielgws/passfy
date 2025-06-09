<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quantity_available',
        'quantity_sold',
        'min_purchase',
        'max_purchase',
        'is_visible',
        'is_active',
        'sort_order',
        'sales_start_at',
        'sales_end_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_visible' => 'boolean',
        'is_active' => 'boolean',
        'sales_start_at' => 'datetime',
        'sales_end_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    protected function quantityRemaining(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->quantity_available - $this->quantity_sold,
        );
    }

    protected function isAvailable(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->is_active || !$this->is_visible) {
                    return false;
                }

                if ($this->quantity_remaining <= 0) {
                    return false;
                }

                $now = now();

                if ($this->sales_start_at && $now->lt($this->sales_start_at)) {
                    return false;
                }

                if ($this->sales_end_at && $now->gt($this->sales_end_at)) {
                    return false;
                }

                return true;
            }
        );
    }

    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => 'R$ ' . number_format($this->price, 2, ',', '.'),
        );
    }
}
