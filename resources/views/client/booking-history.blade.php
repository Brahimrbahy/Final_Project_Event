@extends('layouts.dashbord')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <x-dashboard-sidebar role="client" :current-route="request()->route()->getName()" />

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 lg:ml-0">
            <x-client-header />

            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                📚 Booking History
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 mt-1">
                                View all your past and current bookings
                            </p>
                        </div>
                        <div class="hidden md:block">
                            <a href="{{ route('events.index') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                🎪 Browse Events
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bookings List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($bookings->count() > 0)
                        <div class="space-y-6">
                            @foreach($bookings as $booking)
                                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                                        <!-- Event Info -->
                                        <div class="flex items-start space-x-4 flex-1">
                                            @if($booking->event->image_path)
                                                <img src="{{ Storage::url($booking->event->image_path) }}" 
                                                     alt="{{ $booking->event->title }}" 
                                                     class="w-20 h-20 object-cover rounded-lg">
                                            @else
                                                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                    <span class="text-white text-xl font-bold">
                                                        {{ substr($booking->event->title, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                            
                                            <div class="flex-1">
                                                <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                                    {{ $booking->event->title }}
                                                </h4>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-600 dark:text-gray-300">
                                                    <div class="flex items-center">
                                                        <span class="mr-2">📅</span>
                                                        {{ $booking->event->start_date->format('F j, Y') }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="mr-2">⏰</span>
                                                        {{ $booking->event->start_date->format('g:i A') }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="mr-2">📍</span>
                                                        {{ $booking->event->location }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="mr-2">👥</span>
                                                        {{ $booking->event->organizer->name }}
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center space-x-4 mt-3">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        🎟️ {{ $booking->quantity }} ticket(s)
                                                    </span>
                                                    
                                                    <span class="text-sm font-mono text-gray-500 dark:text-gray-400">
                                                        {{ $booking->ticket_code }}
                                                    </span>
                                                    
                                                    @if($booking->payment_status === 'paid')
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            ✅ Paid
                                                        </span>
                                                    @elseif($booking->payment_status === 'pending')
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                            ⏳ Pending
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                            ❌ Unpaid
                                                        </span>
                                                    @endif

                                                    @if($booking->event->start_date < now())
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                            🏁 Past Event
                                                        </span>
                                                    @elseif($booking->event->start_date > now())
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                            🔮 Upcoming
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Booking Actions -->
                                        <div class="flex flex-col sm:flex-row gap-2 lg:ml-4">
                                            <a href="{{ route('client.ticket-details', $booking) }}" 
                                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition duration-200">
                                                📋 View Details
                                            </a>
                                            
                                            <a href="{{ route('events.show', $booking->event) }}" 
                                               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition duration-200">
                                                🎪 View Event
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Booking Details -->
                                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-300">Booked on:</span>
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    {{ $booking->created_at->format('M j, Y') }}
                                                </div>
                                            </div>
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-300">Total Paid:</span>
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    @if($booking->total_price > 0)
                                                        ${{ number_format($booking->total_price, 2) }}
                                                    @else
                                                        Free
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-300">Payment Method:</span>
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    @if($booking->payment)
                                                        {{ ucfirst($booking->payment->payment_method ?? 'Card') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                <span class="text-gray-600 dark:text-gray-300">Status:</span>
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    {{ ucfirst($booking->payment_status) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-4xl">📚</span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                No booking history
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">
                                You haven't made any bookings yet. Start exploring events!
                            </p>
                            <a href="{{ route('events.index') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                🎪 Browse Events
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
