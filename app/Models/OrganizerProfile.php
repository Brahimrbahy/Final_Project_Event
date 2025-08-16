<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'company_name',
        'contact_info',
        'phone',
        'website',
    ];

    /**
     * Get the user that owns the organizer profile
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get events created by this organizer
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id', 'user_id');
    }
}
