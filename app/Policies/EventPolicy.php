<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view events
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        // Admins can view all events
        if ($user->isAdmin()) {
            return true;
        }

        // Organizers can view their own events
        if ($user->isOrganizer() && $event->organizer_id === $user->id) {
            return true;
        }

        // Clients can only view approved events
        return $event->approved;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isOrganizer() && $user->is_approved;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        // Admins can update any event
        if ($user->isAdmin()) {
            return true;
        }

        // Organizers can only update their own events
        return $user->isOrganizer() && $event->organizer_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        // Admins can delete any event
        if ($user->isAdmin()) {
            return true;
        }

        // Organizers can only delete their own events
        return $user->isOrganizer() && $event->organizer_id === $user->id;
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, Event $event): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can book tickets for the event.
     */
    public function book(User $user, Event $event): bool
    {
        return $user->isClient() && $event->approved && $event->hasAvailableTickets();
    }
}
