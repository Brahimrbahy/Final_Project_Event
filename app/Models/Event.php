<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'category',
        'type',
        'price',
        'max_tickets',
        'tickets_sold',
        'start_date',
        'location',
        'address',
        'approved',
        'image_path',
        'terms_conditions',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'approved' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    /**
     * Get the organizer that owns the event
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Get tickets for this event
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get payments for this event
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if event is free
     */
    public function isFree(): bool
    {
        return $this->type === 'free';
    }

    /**
     * Check if event is paid
     */
    public function isPaid(): bool
    {
        return $this->type === 'paid';
    }

    /**
     * Get available tickets count
     */
    public function getAvailableTicketsAttribute(): int
    {
        if (!$this->max_tickets) {
            return PHP_INT_MAX; // Unlimited
        }
        return max(0, $this->max_tickets - $this->tickets_sold);
    }

    /**
     * Check if event has available tickets
     */
    public function hasAvailableTickets(): bool
    {
        return $this->available_tickets > 0;
    }

    /**
     * Scope for approved events
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    /**
     * Scope for upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    /**
     * Scope for events by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for events by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}
