<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Payment;
use App\Helpers\DatabaseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizerController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'role:organizer', 'approved']);
    }

    /**
     * Show the organizer dashboard
     */
    public function dashboard()
    {
        $organizer = Auth::user();

        $stats = [
            'total_events' => $organizer->events()->count(),
            'approved_events' => $organizer->events()->approved()->count(),
            'pending_events' => $organizer->events()->where('approved', false)->count(),
            'total_tickets_sold' => Ticket::whereIn('event_id', $organizer->events()->pluck('id'))->paid()->count(),
            'total_revenue' => Payment::whereIn('event_id', $organizer->events()->pluck('id'))
                ->completed()
                ->sum('organizer_amount'),
        ];

        $recent_events = $organizer->events()
            ->latest()
            ->take(5)
            ->get();

        $recent_bookings = Ticket::whereIn('event_id', $organizer->events()->pluck('id'))
            ->with(['event', 'client'])
            ->latest()
            ->take(10)
            ->get();

        return view('organizer.dashboard', compact('stats', 'recent_events', 'recent_bookings'));
    }

    /**
     * Show pending approval page
     */
    public function pendingApproval()
    {
        return view('organizer.pending-approval');
    }

    /**
     * Check approval status (AJAX endpoint)
     */
    public function checkApprovalStatus()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'organizer') {
            return response()->json([
                'approved' => false,
                'error' => 'Invalid user',
                'debug' => 'User not found or not organizer'
            ]);
        }

        // Refresh user data from database to get latest approval status
        $user->refresh();

        return response()->json([
            'approved' => (bool) $user->is_approved,
            'redirect_url' => $user->is_approved ? route('organizer.dashboard') : null,
            'debug' => [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'is_approved' => $user->is_approved,
                'role' => $user->role,
                'timestamp' => now()->toISOString()
            ]
        ]);
    }

    /**
     * Display a listing of events
     */
    public function events()
    {
        $events = Auth::user()->events()
            ->latest()
            ->paginate(10);

        return view('organizer.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event
     */
    public function createEvent()
    {
        $this->authorize('create', Event::class);

        return view('organizer.events.create');
    }

    /**
     * Store a newly created event
     */
    public function storeEvent(Request $request)
    {
        $this->authorize('create', Event::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'type' => 'required|in:free,paid',
            'price' => 'required_if:type,paid|nullable|numeric|min:0',
            'max_tickets' => 'nullable|integer|min:1',
            'start_date' => 'required|date|after:now',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'terms_conditions' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        $event = Event::create([
            'organizer_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'type' => $validated['type'],
            'price' => $validated['type'] === 'paid' ? $validated['price'] : null,
            'max_tickets' => $validated['max_tickets'],
            'start_date' => $validated['start_date'],
            'location' => $validated['location'],
            'address' => $validated['address'],
            'image_path' => $imagePath,
            'terms_conditions' => $validated['terms_conditions'],
            'approved' => false, // Requires admin approval
        ]);

        return redirect()->route('organizer.events')
            ->with('success', 'Event created successfully! It will be visible once approved by admin.');
    }

    /**
     * Show the form for editing an event
     */
    public function editEvent(Event $event)
    {
        $this->authorize('update', $event);

        return view('organizer.events.edit', compact('event'));
    }

    /**
     * Update the specified event
     */
    public function updateEvent(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'type' => 'required|in:free,paid',
            'price' => 'required_if:type,paid|nullable|numeric|min:0',
            'max_tickets' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'terms_conditions' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('events', 'public');
        }

        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'type' => $validated['type'],
            'price' => $validated['type'] === 'paid' ? $validated['price'] : null,
            'max_tickets' => $validated['max_tickets'],
            'start_date' => $validated['start_date'],
            'location' => $validated['location'],
            'address' => $validated['address'],
            'image_path' => $validated['image_path'] ?? $event->image_path,
            'terms_conditions' => $validated['terms_conditions'],
        ]);

        return redirect()->route('organizer.events')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event
     */
    public function destroyEvent(Event $event)
    {
        $this->authorize('delete', $event);

        // Delete image if exists
        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }

        $event->delete();

        return redirect()->route('organizer.events')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * Show bookings for organizer's events
     */
    public function bookings()
    {
        $organizer = Auth::user();

        $bookings = Ticket::whereIn('event_id', $organizer->events()->pluck('id'))
            ->with(['event', 'client'])
            ->latest()
            ->paginate(15);

        return view('organizer.bookings', compact('bookings'));
    }

    /**
     * Show revenue report
     */
    public function revenue()
    {
        $organizer = Auth::user();

        $total_revenue = Payment::whereIn('event_id', $organizer->events()->pluck('id'))
            ->completed()
            ->sum('organizer_amount');

        $monthly_revenue = Payment::whereIn('event_id', $organizer->events()->pluck('id'))
            ->completed()
            ->select(DatabaseHelper::getMonthlyRevenueSelect('organizer_amount'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $event_revenues = $organizer->events()
            ->with(['payments' => function($query) {
                $query->completed();
            }])
            ->get()
            ->map(function($event) {
                $event->total_revenue = $event->payments->sum('organizer_amount');
                return $event;
            })
            ->sortByDesc('total_revenue');

        return view('organizer.revenue', compact('total_revenue', 'monthly_revenue', 'event_revenues'));
    }
}
