<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\CustomVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_approved',
        'company_name',
        'contact_phone',
        'company_bio',
        'website',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is organizer
     */
    public function isOrganizer(): bool
    {
        return $this->role === 'organizer';
    }

    /**
     * Check if organizer is approved
     */
    public function isApprovedOrganizer(): bool
    {
        return $this->role === 'organizer' && ($this->status === 'approved' || $this->is_approved);
    }

    /**
     * Check if organizer is pending approval
     */
    public function isPendingOrganizer(): bool
    {
        return $this->role === 'organizer' && $this->status === 'pending';
    }

    /**
     * Check if organizer is rejected
     */
    public function isRejectedOrganizer(): bool
    {
        return $this->role === 'organizer' && $this->status === 'rejected';
    }

    /**
     * Check if user is client
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Get the organizer profile for this user
     */
    public function organizerProfile()
    {
        return $this->hasOne(OrganizerProfile::class);
    }

    /**
     * Get events created by this organizer
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    /**
     * Get tickets purchased by this client
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'client_id');
    }

    /**
     * Get payments made by this client
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'client_id');
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
}
