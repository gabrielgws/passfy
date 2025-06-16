<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'order_id',
        'ticket_type_id',
        'qr_code',
        'attendee_name',
        'attendee_email',
        'attendee_document',
        'status',
        'validated_at',
        'validated_by',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ticket->ticket_number = 'TKT-' . strtoupper(Str::random(10));
            $ticket->qr_code = Str::uuid()->toString();
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function validate(User $user): bool
    {
        if ($this->status !== 'valid') {
            return false;
        }

        $this->update([
            'status' => 'used',
            'validated_at' => now(),
            'validated_by' => $user->id,
        ]);

        return true;
    }
}
