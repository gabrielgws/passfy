<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'banner_image',
        'gallery_images',
        'location',
        'address',
        'city',
        'state',
        'zip_code',
        'start_date',
        'end_date',
        'service_fee_percentage',
        'status',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'service_fee_percentage' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getTotalTicketsSoldAttribute(): int
    {
        return $this->ticketTypes()->sum('sold');
    }

    public function getTotalRevenueAttribute(): float
    {
        return $this->orders()
            ->where('status', 'paid')
            ->sum('subtotal');
    }
}
