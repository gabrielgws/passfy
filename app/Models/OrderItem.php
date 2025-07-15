<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'ticket_id',
        'ticket_name',
        'unit_price',
        'quantity',
        'total_price',
        'qr_code',
        'ticket_code',
        'status',
        'used_at',
        'validated_by',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'used_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orderItem) {
            if (empty($orderItem->qr_code)) {
                $orderItem->qr_code = 'QR-' . strtoupper(Str::random(12));
            }
            if (empty($orderItem->ticket_code)) {
                $orderItem->ticket_code = 'TIX-' . strtoupper(Str::random(10));
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function validatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function getIsUsedAttribute(): bool
    {
        return $this->status === 'used';
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    public function getIsCancelledAttribute(): bool
    {
        return $this->status === 'cancelled';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUsed($query)
    {
        return $query->where('status', 'used');
    }

    public function scopeByQrCode($query, string $qrCode)
    {
        return $query->where('qr_code', $qrCode);
    }

    public function scopeByTicketCode($query, string $ticketCode)
    {
        return $query->where('ticket_code', $ticketCode);
    }
}
