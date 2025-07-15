<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'short_description',
        'image',
        'banner',
        'location',
        'address',
        'city',
        'state',
        'zip_code',
        'latitude',
        'longitude',
        'start_date',
        'end_date',
        'sale_start_date',
        'sale_end_date',
        'max_tickets',
        'sold_tickets',
        'maintenance_fee_percentage',
        'status',
        'is_featured',
        'is_free',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'sale_start_date' => 'datetime',
        'sale_end_date' => 'datetime',
        'maintenance_fee_percentage' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_free' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function activeTickets(): HasMany
    {
        return $this->tickets()->where('is_active', true);
    }

    public function getAvailableTicketsAttribute(): int
    {
        return $this->max_tickets ? $this->max_tickets - $this->sold_tickets : 0;
    }

    public function getIsSoldOutAttribute(): bool
    {
        return $this->max_tickets && $this->sold_tickets >= $this->max_tickets;
    }

    public function getIsOnSaleAttribute(): bool
    {
        $now = now();
        $saleStarted = !$this->sale_start_date || $this->sale_start_date <= $now;
        $saleNotEnded = !$this->sale_end_date || $this->sale_end_date > $now;

        return $this->status === 'published' && $saleStarted && $saleNotEnded && !$this->is_sold_out;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeByLocation($query, string $city, string $state = null)
    {
        $query->where('city', $city);
        if ($state) {
            $query->where('state', $state);
        }
        return $query;
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::url($this->image);
    }

    public function hasImage(): bool
    {
        return !empty($this->image);
    }

    public function getActiveTicketsAttribute()
    {
        return $this->tickets()->active()->get();
    }

    public function getAvailableTicketsCollectionAttribute()
    {
        return $this->tickets()->available()->get();
    }

    public function hasActiveTickets(): bool
    {
        return $this->activeTickets()->count() > 0;
    }

    public function getTotalTicketsSoldAttribute(): int
    {
        return $this->tickets()->sum('sold_quantity');
    }
}
