<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'client_id',
        'quantity',
        'total_price',
        'payment_status',
        'transaction_id',
        'stripe_session_id',
        'payment_id',
        'ticket_code',
        'is_used',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'is_used' => 'boolean',
            'used_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (!$ticket->ticket_code) {
                $ticket->ticket_code = 'TKT-' . strtoupper(Str::random(10));
            }
        });
    }

    /**
     * Get the event that owns the ticket
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the client that owns the ticket
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the payment for this ticket
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Check if ticket is paid
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if ticket is used
     */
    public function isUsed(): bool
    {
        return $this->is_used;
    }

    /**
     * Mark ticket as used
     */
    public function markAsUsed()
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
        ]);
    }

    /**
     * Scope for paid tickets
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope for unused tickets
     */
    public function scopeUnused($query)
    {
        return $query->where('is_used', false);
    }
}
