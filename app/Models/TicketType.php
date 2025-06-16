<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quantity',
        'sold',
        'min_per_order',
        'max_per_order',
        'sales_start_date',
        'sales_end_date',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sales_start_date' => 'datetime',
        'sales_end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function getAvailableAttribute(): int
    {
        return $this->quantity - $this->sold;
    }

    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->available <= 0) {
            return false;
        }

        $now = now();

        if ($this->sales_start_date && $now->lt($this->sales_start_date)) {
            return false;
        }

        if ($this->sales_end_date && $now->gt($this->sales_end_date)) {
            return false;
        }

        return true;
    }
}
