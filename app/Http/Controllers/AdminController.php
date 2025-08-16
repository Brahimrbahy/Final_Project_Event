<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Ticket;
use App\Helpers\DatabaseHelper;
use App\Mail\OrganizerApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Show the admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'pending_organizers' => User::where('role', 'organizer')
                ->where('status', 'pending')
                ->count(),
            'pending_events' => Event::where('approved', false)->count(),
            'total_revenue' => Payment::completed()->sum('admin_fee'),
            'total_events' => Event::approved()->count(),
            'total_organizers' => User::where('role', 'organizer')
                ->where('status', 'approved')
                ->count(),
            'total_tickets_sold' => Ticket::paid()->count(),
        ];

        $recent_organizers = User::where('role', 'organizer')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recent_events = Event::where('approved', false)
            ->with('organizer')
            ->latest()
            ->take(5)
            ->get();

        $monthly_revenue = Payment::completed()
            ->select(DatabaseHelper::getMonthlyRevenueSelect('admin_fee'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recent_organizers',
            'recent_events',
            'monthly_revenue'
        ));
    }

    /**
     * Show pending organizer approvals
     */
    public function pendingOrganizers()
    {
        $organizers = User::where('role', 'organizer')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pending-organizers', compact('organizers'));
    }

    /**
     * Approve an organizer
     */
    public function approveOrganizer(User $user)
    {
        // Validate that the user is a pending organizer
        if ($user->role !== 'organizer' || $user->status !== 'pending') {
            return redirect()->back()->with('error', 'Invalid organizer or already processed.');
        }

        // Update approval status
        $user->update([
            'status' => 'approved',
            'is_approved' => true // Keep for backward compatibility
        ]);

        // Send approval email
        try {
            Mail::to($user)->send(new OrganizerApproved($user));
            $emailStatus = 'Approval email sent successfully.';
        } catch (\Exception $e) {
            Log::error('Failed to send organizer approval email: ' . $e->getMessage());
            $emailStatus = 'Approved, but email notification failed.';
        }

        return redirect()->back()->with('success', "Organizer '{$user->name}' approved successfully. {$emailStatus}");
    }

    /**
     * Reject an organizer
     */
    public function rejectOrganizer(User $user)
    {
        // Validate that the user is a pending organizer
        if ($user->role !== 'organizer' || $user->status !== 'pending') {
            return redirect()->back()->with('error', 'Invalid organizer or already processed.');
        }

        // Update status to rejected instead of deleting
        $user->update(['status' => 'rejected']);

        return redirect()->back()->with('success', "Organizer '{$user->name}' has been rejected.");
    }

    /**
     * Show pending event approvals
     */
    public function pendingEvents()
    {
        $events = Event::where('approved', false)
            ->with('organizer')
            ->paginate(10);

        return view('admin.pending-events', compact('events'));
    }

    /**
     * Approve an event
     */
    public function approveEvent(Event $event)
    {
        $this->authorize('approve', $event);

        $event->update(['approved' => true]);

        return redirect()->back()->with('success', 'Event approved successfully.');
    }

    /**
     * Reject an event
     */
    public function rejectEvent(Event $event)
    {
        $this->authorize('approve', $event);

        $event->delete(); // Or you could set a rejected status

        return redirect()->back()->with('success', 'Event rejected and removed.');
    }

    /**
     * Show all organizers
     */
    public function organizers()
    {
        $organizers = User::where('role', 'organizer')
            ->with('organizerProfile')
            ->paginate(15);

        return view('admin.organizers', compact('organizers'));
    }

    /**
     * Show all events
     */
    public function events()
    {
        $events = Event::with('organizer')
            ->paginate(15);

        return view('admin.events', compact('events'));
    }

    /**
     * Show revenue reports
     */
    public function revenue()
    {
        $total_revenue = Payment::completed()->sum('admin_fee');
        $monthly_revenue = Payment::completed()
            ->select(DatabaseHelper::getMonthlyRevenueSelect('admin_fee'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $top_events = Event::select('events.*')
            ->join('payments', 'events.id', '=', 'payments.event_id')
            ->where('payments.status', 'completed')
            ->groupBy('events.id')
            ->orderByRaw('SUM(payments.admin_fee) DESC')
            ->with('organizer')
            ->take(10)
            ->get();

        return view('admin.revenue', compact(
            'total_revenue',
            'monthly_revenue',
            'top_events'
        ));
    }
}
