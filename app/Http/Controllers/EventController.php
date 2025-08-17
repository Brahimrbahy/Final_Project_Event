<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    /**
     * Display a listing of public events
     */
    public function index(Request $request)
    {
        $query = Event::approved()->upcoming()->with('organizer');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter by type (free/paid)
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }

        // Sort options
        $sort = $request->get('sort', 'start_date');
        $direction = $request->get('direction', 'asc');

        switch ($sort) {
            case 'title':
                $query->orderBy('title', $direction);
                break;
            case 'price':
                $query->orderBy('price', $direction);
                break;
            case 'created_at':
                $query->orderBy('created_at', $direction);
                break;
            default:
                $query->orderBy('start_date', $direction);
                break;
        }

        $events = $query->paginate(12)->withQueryString();

        // Get categories for filter dropdown
        $categories = Event::approved()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('events.index', compact('events', 'categories'));
    }

    /**
     * Display the specified event
     */
    public function show(Event $event)
    {
        // Check if user can view this event
        if (Auth::check()) {
            $this->authorize('view', $event);
        } else {
            // Guest users can only view approved events
            if (!$event->approved) {
                abort(404);
            }
        }

        $event->load('organizer.organizerProfile');

        // Get related events (same category, different event)
        $relatedEvents = Event::approved()
            ->upcoming()
            ->where('category', $event->category)
            ->where('id', '!=', $event->id)
            ->take(4)
            ->get();

        return view('events.show', compact('event', 'relatedEvents'));
    }

    /**
     * Show events by category
     */
    public function category($category)
    {
        $events = Event::approved()
            ->upcoming()
            ->byCategory($category)
            ->with('organizer')
            ->orderBy('start_date')
            ->paginate(12);

        return view('events.category', compact('events', 'category'));
    }

    /**
     * Show free events
     */
    public function free()
    {
        $events = Event::approved()
            ->upcoming()
            ->byType('free')
            ->with('organizer')
            ->orderBy('start_date')
            ->paginate(12);

        return view('events.free', compact('events'));
    }

    /**
     * Show paid events
     */
    public function paid()
    {
        $events = Event::approved()
            ->upcoming()
            ->byType('paid')
            ->with('organizer')
            ->orderBy('start_date')
            ->paginate(12);

        return view('events.paid', compact('events'));
    }

    /**
     * Search events (AJAX endpoint)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json([]);
        }

        $events = Event::approved()
            ->upcoming()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('location', 'like', "%{$query}%");
            })
            ->with('organizer')
            ->take(10)
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'location' => $event->location,
                    'start_date' => $event->start_date->format('M d, Y'),
                    'type' => $event->type,
                    'price' => $event->price,
                    'url' => route('events.show', $event),
                ];
            });

        return response()->json($events);
    }

    /**
     * Get events for calendar view
     */
    public function calendar()
    {
        $events = Event::approved()
            ->upcoming()
            ->get()
            ->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date->toISOString(),
                    'url' => route('events.show', $event),
                    'color' => $event->type === 'free' ? '#28a745' : '#007bff',
                ];
            });

        return response()->json($events);
    }

    /**
     * Show home page with featured events
     */
    public function home()
    {
        // Latest 4 events for image carousel (ordered by creation date)
        $latestEvents = Event::approved()
            ->upcoming()
            ->with('organizer')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // All events for the main listing (paginated, ordered by start date)
        $allEvents = Event::approved()
            ->upcoming()
            ->with('organizer')
            ->orderBy('start_date')
            ->paginate(9); // Initial load of 9 events

        $categories = Event::approved()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->take(8);

        return view('welcome', compact('latestEvents', 'allEvents', 'categories'));
    }

    /**
     * Load more events for home page (AJAX endpoint)
     */
    public function loadMoreEvents(Request $request)
    {
        $page = $request->get('page', 1);

        $events = Event::approved()
            ->upcoming()
            ->with('organizer')
            ->orderBy('start_date')
            ->paginate(6, ['*'], 'page', $page); // Load 6 more events per request

        if ($events->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No more events available'
            ]);
        }

        $eventsHtml = '';
        foreach ($events as $event) {
            $eventsHtml .= view('partials.event-card', compact('event'))->render();
        }

        return response()->json([
            'success' => true,
            'html' => $eventsHtml,
            'hasMore' => $events->hasMorePages(),
            'nextPage' => $events->currentPage() + 1
        ]);
    }
}
