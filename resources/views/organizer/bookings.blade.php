@extends('layouts.dashbord')
@section('content')

    <div class="flex h-screen bg-gray-100">
            <x-dashboard-sidebar role="organizer" :current-route="request()->route()->getName()" />

        <div class="p-12 flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 lg:ml-0">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($bookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Event
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Client
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tickets
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Total Price
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Your Revenue
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Booked
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ticket Code
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $booking->event->title }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $booking->event->category }} • 
                                                        {{ $booking->event->start_date->format('M d, Y') }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8">
                                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-xs font-medium text-gray-700">
                                                                {{ substr($booking->client->name, 0, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $booking->client->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $booking->client->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $booking->quantity }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $booking->quantity == 1 ? 'ticket' : 'tickets' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    @if($booking->event->isFree())
                                                        <span class="text-green-600">Free</span>
                                                    @else
                                                        ${{ number_format($booking->total_price, 2) }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    @if($booking->event->isFree())
                                                        <span class="text-gray-500">N/A</span>
                                                    @else
                                                        ${{ number_format($booking->total_price * 0.85, 2) }}
                                                        <div class="text-xs text-gray-500">
                                                            (after 15% fee)
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($booking->payment_status === 'paid')
                                                        bg-green-100 text-green-800
                                                    @elseif($booking->payment_status === 'unpaid')
                                                        bg-yellow-100 text-yellow-800
                                                    @else
                                                        bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($booking->payment_status) }}
                                                </span>
                                                @if($booking->is_used)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        Used: {{ $booking->used_at->format('M d, Y') }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $booking->created_at->format('M d, Y') }}
                                                <div class="text-xs text-gray-400">
                                                    {{ $booking->created_at->format('g:i A') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-mono text-gray-900">
                                                    {{ $booking->ticket_code }}
                                                </div>
                                                @if($booking->isPaid())
                                                    <button onclick="copyToClipboard('{{ $booking->ticket_code }}')" 
                                                            class="text-xs text-blue-600 hover:text-blue-500">
                                                        Copy Code
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Statistics -->
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                            @php
                                $totalTickets = $bookings->sum('quantity');
                                $totalRevenue = $bookings->where('payment_status', 'paid')->sum('total_price');
                                $organizerRevenue = $totalRevenue * 0.85;
                                $paidBookings = $bookings->where('payment_status', 'paid')->count();
                            @endphp
                            
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="text-sm font-medium text-blue-600">Total Tickets Sold</div>
                                <div class="text-2xl font-bold text-blue-900">{{ $totalTickets }}</div>
                            </div>
                            
                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="text-sm font-medium text-green-600">Paid Bookings</div>
                                <div class="text-2xl font-bold text-green-900">{{ $paidBookings }}</div>
                            </div>
                            
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <div class="text-sm font-medium text-purple-600">Total Revenue</div>
                                <div class="text-2xl font-bold text-purple-900">${{ number_format($totalRevenue, 2) }}</div>
                            </div>
                            
                            <div class="bg-yellow-50 p-4 rounded-lg">
                                <div class="text-sm font-medium text-yellow-600">Your Revenue</div>
                                <div class="text-2xl font-bold text-yellow-900">${{ number_format($organizerRevenue, 2) }}</div>
                                <div class="text-xs text-yellow-600">After 15% platform fee</div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 21a2 2 0 01-2-2v-3a1 1 0 011-1h1a1 1 0 011 1v3a2 2 0 01-2 2H5z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings yet</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Bookings will appear here once people start purchasing tickets for your events.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('organizer.events.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Create Your First Event
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show a temporary success message
                const button = event.target;
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                button.classList.add('text-green-600');
                
                setTimeout(function() {
                    button.textContent = originalText;
                    button.classList.remove('text-green-600');
                    button.classList.add('text-blue-600');
                }, 2000);
            });
        }
    </script>
@endsection