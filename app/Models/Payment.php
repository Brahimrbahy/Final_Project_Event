<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'client_id',
        'ticket_id',
        'amount',
        'admin_fee',
        'organizer_amount',
        'stripe_payment_id',
        'stripe_payment_intent_id',
        'status',
        'stripe_response',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'admin_fee' => 'decimal:2',
            'organizer_amount' => 'decimal:2',
            'stripe_response' => 'array',
        ];
    }

    /**
     * Calculate admin fee (15%)
     */
    public static function calculateAdminFee($amount): float
    {
        return round($amount * 0.15, 2);
    }

    /**
     * Calculate organizer amount after fee
     */
    public static function calculateOrganizerAmount($amount): float
    {
        return round($amount - self::calculateAdminFee($amount), 2);
    }

    /**
     * Get the event that owns the payment
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the client that owns the payment
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the ticket for this payment
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Scope for completed payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
