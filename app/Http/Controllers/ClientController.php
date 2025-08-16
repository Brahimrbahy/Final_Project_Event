<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\TicketConfirmation;

class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'role:client']);
    }

    /**
     * Show client dashboard
     */
    public function dashboard()
    {
        $client = Auth::user();
        
        $stats = [
            'total_tickets' => $client->tickets()->count(),
            'upcoming_events' => $client->tickets()
                ->whereHas('event', function($query) {
                    $query->where('start_date', '>', now());
                })
                ->count(),
            'total_spent' => $client->payments()->completed()->sum('amount'),
            'events_attended' => $client->tickets()
                ->whereHas('event', function($query) {
                    $query->where('end_date', '<', now());
                })
                ->count(),
        ];

        $upcoming_tickets = $client->tickets()
            ->with(['event', 'payment'])
            ->whereHas('event', function($query) {
                $query->where('start_date', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recent_tickets = $client->tickets()
            ->with(['event', 'payment'])
            ->latest()
            ->take(10)
            ->get();

        return view('client.dashboard', compact('stats', 'upcoming_tickets', 'recent_tickets'));
    }

    /**
     * Show all client tickets
     */
    public function tickets()
    {
        $tickets = Auth::user()->tickets()
            ->with(['event', 'payment'])
            ->latest()
            ->paginate(15);

        return view('client.tickets', compact('tickets'));
    }

    /**
     * Show ticket details
     */
    public function showTicket(Ticket $ticket)
    {
        // Ensure the ticket belongs to the authenticated client
        if ($ticket->client_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load(['event.organizer', 'payment']);

        return view('client.ticket-details', compact('ticket'));
    }

    /**
     * Show booking form for an event
     */
    public function bookingForm(Event $event)
    {
        $this->authorize('book', $event);

        if (!$event->hasAvailableTickets()) {
            return redirect()->back()->with('error', 'Sorry, this event is sold out.');
        }

        return view('client.booking-form', compact('event'));
    }

    /**
     * Process ticket booking
     */
    public function processBooking(Request $request, Event $event)
    {
        $this->authorize('book', $event);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $quantity = $validated['quantity'];

        // Check availability
        if (!$event->hasAvailableTickets() || $event->available_tickets < $quantity) {
            return redirect()->back()->with('error', 'Not enough tickets available.');
        }

        $totalPrice = $event->isFree() ? 0 : ($event->price * $quantity);

        // Create ticket record
        $ticket = Ticket::create([
            'event_id' => $event->id,
            'client_id' => Auth::id(),
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'payment_status' => $event->isFree() ? 'paid' : 'unpaid',
        ]);

        // Update tickets sold count
        $event->increment('tickets_sold', $quantity);

        if ($event->isFree()) {
            // For free events, mark as paid immediately
            $ticket->update(['payment_status' => 'paid']);

            // Send confirmation email
            try {
                Mail::to(Auth::user())->send(new TicketConfirmation($ticket));
                Log::info("Free ticket confirmation email sent for ticket ID: {$ticket->id}");
            } catch (\Exception $e) {
                Log::error("Failed to send free ticket confirmation email for ticket ID {$ticket->id}: " . $e->getMessage());
            }

            return redirect()->route('client.ticket-details', $ticket)
                ->with('success', 'Your free tickets have been booked successfully! Check your email for confirmation.');
        } else {
            // For paid events, redirect to Stripe Checkout
            return redirect()->route('payment.checkout', $ticket);
        }
    }

    /**
     * Show booking history
     */
    public function bookingHistory()
    {
        $bookings = Auth::user()->tickets()
            ->with(['event', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('client.booking-history', compact('bookings'));
    }

    /**
     * Download ticket as PDF
     */
    public function downloadTicket(Ticket $ticket)
    {
        // Ensure the ticket belongs to the authenticated client
        if ($ticket->client_id !== Auth::id()) {
            abort(403);
        }

        if (!$ticket->isPaid()) {
            return redirect()->back()->with('error', 'Ticket must be paid before downloading.');
        }

        $ticket->load(['event.organizer', 'payment']);

        // Generate PDF (implement with a PDF library like DomPDF)
        // For now, return a view that can be printed
        return view('client.ticket-pdf', compact('ticket'));
    }

    /**
     * Cancel a ticket booking
     */
    public function cancelBooking(Ticket $ticket)
    {
        // Ensure the ticket belongs to the authenticated client
        if ($ticket->client_id !== Auth::id()) {
            abort(403);
        }

        // Check if cancellation is allowed (e.g., event hasn't started)
        if ($ticket->event->start_date <= now()) {
            return redirect()->back()->with('error', 'Cannot cancel tickets for events that have already started.');
        }

        if ($ticket->is_used) {
            return redirect()->back()->with('error', 'Cannot cancel used tickets.');
        }

        // Process refund if paid
        if ($ticket->isPaid() && $ticket->payment) {
            // Implement refund logic with Stripe
            // For now, just mark as refunded
            $ticket->payment->update(['status' => 'refunded']);
        }

        // Update event tickets sold count
        $ticket->event->decrement('tickets_sold', $ticket->quantity);

        // Delete the ticket
        $ticket->delete();

        return redirect()->route('client.tickets')
            ->with('success', 'Ticket booking cancelled successfully.');
    }

    /**
     * Show client profile
     */
    public function profile()
    {
        $client = Auth::user();
        return view('client.profile', compact('client'));
    }

    /**
     * Update client profile
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        Auth::user()->update($validated);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Show events the client might be interested in
     */
    public function recommendations()
    {
        $client = Auth::user();
        
        // Get categories from client's previous bookings
        $bookedCategories = $client->tickets()
            ->with('event')
            ->get()
            ->pluck('event.category')
            ->unique()
            ->filter();

        $recommendedEvents = collect();

        if ($bookedCategories->isNotEmpty()) {
            // Recommend events from similar categories
            $recommendedEvents = Event::approved()
                ->upcoming()
                ->whereIn('category', $bookedCategories)
                ->whereNotIn('id', $client->tickets()->pluck('event_id'))
                ->with('organizer')
                ->take(12)
                ->get();
        }

        // If no recommendations or not enough, add popular events
        if ($recommendedEvents->count() < 6) {
            $popularEvents = Event::approved()
                ->upcoming()
                ->whereNotIn('id', $client->tickets()->pluck('event_id'))
                ->orderBy('tickets_sold', 'desc')
                ->with('organizer')
                ->take(12 - $recommendedEvents->count())
                ->get();

            $recommendedEvents = $recommendedEvents->merge($popularEvents);
        }

        return view('client.recommendations', compact('recommendedEvents'));
    }
}
