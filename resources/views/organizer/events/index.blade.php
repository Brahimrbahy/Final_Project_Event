@extends('layouts.dashbord')
@section('content')
    <div class="flex h-screen bg-gray-100">
            <x-dashboard-sidebar role="organizer" :current-route="request()->route()->getName()" />

        <div class="p-12 flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 lg:ml-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($events->count() > 0)
                        <div class="grid gap-6">
                            @foreach($events as $event)
                                <div class="border border-gray-200 rounded-lg p-6">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4 mb-4">
                                                @if($event->image_path)
                                                    <img src="{{ Storage::url($event->image_path) }}" 
                                                         alt="{{ $event->title }}" 
                                                         class="w-20 h-20 object-cover rounded-lg">
                                                @else
                                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $event->category }} • 
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                            {{ $event->type === 'free' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                            {{ ucfirst($event->type) }}
                                                            @if($event->type === 'paid')
                                                                - ${{ number_format($event->price, 2) }}
                                                            @endif
                                                        </span>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                                                            {{ $event->approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                            {{ $event->approved ? 'Approved' : 'Pending Approval' }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <p class="text-sm text-gray-600 mb-2">
                                                        <strong>Date:</strong>
                                                        {{ $event->start_date->format('M d, Y \a\t g:i A') }}
                                                    </p>
                                                    <p class="text-sm text-gray-600 mb-2">
                                                        <strong>Location:</strong> {{ $event->location }}
                                                    </p>
                                                    @if($event->max_tickets)
                                                        <p class="text-sm text-gray-600">
                                                            <strong>Tickets:</strong> {{ $event->tickets_sold }} / {{ number_format($event->max_tickets) }} sold
                                                        </p>
                                                    @else
                                                        <p class="text-sm text-gray-600">
                                                            <strong>Tickets Sold:</strong> {{ $event->tickets_sold }}
                                                        </p>
                                                    @endif
                                                </div>

                                                <div>
                                                    <p class="text-sm text-gray-600 mb-2">
                                                        <strong>Created:</strong> {{ $event->created_at->format('M d, Y') }}
                                                    </p>
                                                    @if($event->isPaid())
                                                        <p class="text-sm text-gray-600 mb-2">
                                                            <strong>Revenue:</strong> ${{ number_format($event->tickets_sold * $event->price * 0.85, 2) }}
                                                            <span class="text-xs text-gray-500">(after 15% fee)</span>
                                                        </p>
                                                    @endif
                                                    <p class="text-sm text-gray-600">
                                                        <strong>Status:</strong>
                                                        @if($event->start_date > now())
                                                            <span class="text-green-600">Upcoming</span>
                                                        @else
                                                            <span class="text-gray-600">Completed</span>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <p class="text-sm text-gray-600">{{ Str::limit($event->description, 200) }}</p>
                                            </div>
                                        </div>

                                        <div class="ml-6 flex flex-col space-y-2">
                                            <!-- Edit Button -->
                                            <a href="{{ route('organizer.events.edit', $event) }}" 
                                               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-center">
                                                Edit Event
                                            </a>
                                            
                                            @if($event->approved)
                                                <!-- View Public Button -->
                                                <a href="{{ route('events.show', $event) }}" 
                                                   class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-center">
                                                    View Public
                                                </a>
                                            @endif

                                            @if($event->tickets_sold > 0)
                                                <!-- View Bookings Button -->
                                                <a href="{{ route('organizer.bookings') }}?event={{ $event->id }}" 
                                                   class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-center">
                                                    View Bookings
                                                </a>
                                            @endif

                                            <!-- Delete Button -->
                                            <form action="{{ route('organizer.events.destroy', $event) }}" method="POST" class="w-full">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded"
                                                        onclick="return confirm('Are you sure you want to delete this event? This action cannot be undone.')">
                                                    Delete Event
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $events->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No events created yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating your first event.</p>
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
</div>  
@endsection
