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
                                🎫 My Tickets
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 mt-1">
                                View and manage all your event tickets
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

            <!-- Tickets List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($tickets->count() > 0)
                        <div class="space-y-6">
                            @foreach($tickets as $ticket)
                                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                                        <!-- Event Info -->
                                        <div class="flex items-start space-x-4 flex-1">
                                            @if($ticket->event->image_path)
                                                <img src="{{ Storage::url($ticket->event->image_path) }}" 
                                                     alt="{{ $ticket->event->title }}" 
                                                     class="w-20 h-20 object-cover rounded-lg">
                                            @else
                                                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                    <span class="text-white text-xl font-bold">
                                                        {{ substr($ticket->event->title, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                            
                                            <div class="flex-1">
                                                <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                                    {{ $ticket->event->title }}
                                                </h4>
                                                
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-600 dark:text-gray-300">
                                                    <div class="flex items-center">
                                                        <span class="mr-2">📅</span>
                                                        {{ $ticket->event->start_date->format('F j, Y') }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="mr-2">⏰</span>
                                                        {{ $ticket->event->start_date->format('g:i A') }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="mr-2">📍</span>
                                                        {{ $ticket->event->location }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="mr-2">👥</span>
                                                        {{ $ticket->event->organizer->name }}
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center space-x-4 mt-3">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        🎟️ {{ $ticket->quantity }} ticket(s)
                                                    </span>
                                                    
                                                    <span class="text-sm font-mono text-gray-500 dark:text-gray-400">
                                                        {{ $ticket->ticket_code }}
                                                    </span>
                                                    
                                                    @if($ticket->payment_status === 'paid')
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            ✅ Confirmed
                                                        </span>
                                                    @elseif($ticket->payment_status === 'pending')
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                            ⏳ Pending Payment
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                            ❌ Unpaid
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Ticket Actions -->
                                        <div class="flex flex-col sm:flex-row gap-2 lg:ml-4">
                                            <a href="{{ route('client.ticket-details', $ticket) }}" 
                                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition duration-200">
                                                📋 View Details
                                            </a>
                                            
                                            @if($ticket->payment_status === 'paid')
                                                <button onclick="window.open('{{ route('client.download-ticket', $ticket) }}', '_blank')" 
                                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                                    📥 Download
                                                </button>
                                            @endif
                                            
                                            @if($ticket->event->start_date > now() && !$ticket->is_used)
                                                <form action="{{ route('client.cancel-booking', $ticket) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            onclick="return confirm('Are you sure you want to cancel this ticket? This action cannot be undone.')"
                                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                                        🗑️ Cancel
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Price Info -->
                                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-600 dark:text-gray-300">Total Paid:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">
                                                @if($ticket->total_price > 0)
                                                    ${{ number_format($ticket->total_price, 2) }}
                                                @else
                                                    Free
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center text-sm mt-1">
                                            <span class="text-gray-600 dark:text-gray-300">Booked on:</span>
                                            <span class="text-gray-900 dark:text-white">
                                                {{ $ticket->created_at->format('M j, Y \a\t g:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $tickets->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-4xl">🎫</span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                No tickets yet
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">
                                You haven't booked any tickets yet. Start exploring amazing events!
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
