<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public routes
Route::get('/', [EventController::class, 'home'])->name('welcome');
Route::get('/home', [EventController::class, 'home'])->name('home');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/category/{category}', [EventController::class, 'category'])->name('events.category');
Route::get('/events/type/free', [EventController::class, 'free'])->name('events.free');
Route::get('/events/type/paid', [EventController::class, 'paid'])->name('events.paid');
Route::get('/api/events/search', [EventController::class, 'search'])->name('events.search');
Route::get('/api/events/calendar', [EventController::class, 'calendar'])->name('events.calendar');
Route::get('/api/events/load-more', [EventController::class, 'loadMoreEvents'])->name('events.load-more');

// Ticket purchase routes (for authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/events/{event}/tickets', [PaymentController::class, 'selectQuantity'])->name('tickets.select-quantity');
    Route::post('/events/{event}/purchase', [PaymentController::class, 'purchaseTickets'])->name('tickets.purchase');
    Route::get('/payment/{ticket}/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/{ticket}/create-intent', [PaymentController::class, 'createPaymentIntent'])->name('payment.create-intent');
    Route::post('/payment/{ticket}/confirm', [PaymentController::class, 'confirmPayment'])->name('payment.confirm');
    Route::get('/payment/{ticket}/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/{ticket}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

// Dashboard route - redirect based on role
Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }

    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'organizer':
            if (!$user->is_approved) {
                return redirect()->route('organizer.pending-approval');
            }
            return redirect()->route('organizer.dashboard');
        case 'client':
            return redirect()->route('client.dashboard');
        default:
            return view('dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/organizers/pending', [AdminController::class, 'pendingOrganizers'])->name('pending-organizers');
    Route::post('/organizers/{user}/approve', [AdminController::class, 'approveOrganizer'])->name('approve-organizer');
    Route::delete('/organizers/{user}/reject', [AdminController::class, 'rejectOrganizer'])->name('reject-organizer');
    Route::get('/events/pending', [AdminController::class, 'pendingEvents'])->name('pending-events');
    Route::post('/events/{event}/approve', [AdminController::class, 'approveEvent'])->name('approve-event');
    Route::delete('/events/{event}/reject', [AdminController::class, 'rejectEvent'])->name('reject-event');
    Route::get('/organizers', [AdminController::class, 'organizers'])->name('organizers');
    Route::get('/events', [AdminController::class, 'events'])->name('events');
    Route::get('/revenue', [AdminController::class, 'revenue'])->name('revenue');
});

// Organizer waiting page (for pending organizers)
Route::middleware(['auth'])->group(function () {
    Route::get('/organizer/waiting', function () {
        if (Auth::user()->role !== 'organizer' || Auth::user()->status !== 'pending') {
            return redirect()->route('dashboard');
        }
        return view('organizer.waiting');
    })->name('organizer.waiting');
});

// Organizer routes (for all organizers, approved or not)
Route::middleware(['auth', 'role:organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/pending-approval', [OrganizerController::class, 'pendingApproval'])->name('pending-approval');
    Route::get('/check-approval-status', [OrganizerController::class, 'checkApprovalStatus'])->name('check-approval-status');
});

// Organizer routes (only for approved organizers)
Route::middleware(['auth', 'role:organizer', 'approved'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/dashboard', [OrganizerController::class, 'dashboard'])->name('dashboard');
    Route::get('/events', [OrganizerController::class, 'events'])->name('events');
    Route::get('/events/create', [OrganizerController::class, 'createEvent'])->name('events.create');
    Route::post('/events', [OrganizerController::class, 'storeEvent'])->name('events.store');
    Route::get('/events/{event}/edit', [OrganizerController::class, 'editEvent'])->name('events.edit');
    Route::put('/events/{event}', [OrganizerController::class, 'updateEvent'])->name('events.update');
    Route::delete('/events/{event}', [OrganizerController::class, 'destroyEvent'])->name('events.destroy');
    Route::get('/bookings', [OrganizerController::class, 'bookings'])->name('bookings');
    Route::get('/revenue', [OrganizerController::class, 'revenue'])->name('revenue');
});

// Client routes
Route::middleware(['auth', 'verified', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/tickets', [ClientController::class, 'tickets'])->name('tickets');
    Route::get('/tickets/{ticket}', [ClientController::class, 'showTicket'])->name('ticket-details');
    Route::get('/events/{event}/book', [ClientController::class, 'bookingForm'])->name('booking-form');
    Route::post('/events/{event}/book', [ClientController::class, 'processBooking'])->name('process-booking');
    Route::get('/booking-history', [ClientController::class, 'bookingHistory'])->name('booking-history');
    Route::get('/tickets/{ticket}/download', [ClientController::class, 'downloadTicket'])->name('download-ticket');
    Route::delete('/tickets/{ticket}/cancel', [ClientController::class, 'cancelBooking'])->name('cancel-booking');
    Route::get('/profile', [ClientController::class, 'profile'])->name('profile');
    Route::put('/profile', [ClientController::class, 'updateProfile'])->name('update-profile');
    Route::get('/recommendations', [ClientController::class, 'recommendations'])->name('recommendations');
});

// Payment routes
Route::middleware('auth')->prefix('payment')->name('payment.')->group(function () {
    Route::get('/checkout/{ticket}', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/create-intent/{ticket}', [PaymentController::class, 'createPaymentIntent'])->name('create-intent');
    Route::post('/confirm/{ticket}', [PaymentController::class, 'confirmPayment'])->name('confirm');
    Route::get('/success/{ticket}', [PaymentController::class, 'success'])->name('success');
    Route::get('/cancel/{ticket}', [PaymentController::class, 'cancel'])->name('cancel');
    Route::post('/refund/{payment}', [PaymentController::class, 'refund'])->name('refund');
});

// Stripe webhook (no auth required)
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');

// Test route to check admin user
Route::get('/test-admin', function () {
    try {
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            return response()->json([
                'message' => 'Admin user found!',
                'admin' => [
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'role' => $admin->role,
                    'is_approved' => $admin->is_approved,
                ]
            ]);
        } else {
            return response()->json(['message' => 'No admin user found']);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

// Test route to check if controllers work
Route::get('/test-controller', function () {
    try {
        $controller = new \App\Http\Controllers\AdminController();
        return response()->json(['message' => 'AdminController instantiated successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

// Test route to check SQLite date functions
Route::get('/test-sqlite', function () {
    try {
        $helper = new \App\Helpers\DatabaseHelper();
        $monthFunc = $helper::monthFunction();
        $yearFunc = $helper::yearFunction();

        // Test a simple query
        $result = \Illuminate\Support\Facades\DB::select("SELECT {$monthFunc} as month, {$yearFunc} as year, datetime('now') as now");

        return response()->json([
            'message' => 'SQLite date functions working',
            'month_function' => $monthFunc,
            'year_function' => $yearFunc,
            'test_result' => $result
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
