<x-app-layout>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <x-dashboard-sidebar role="admin" :current-route="request()->route()->getName()" />

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 lg:ml-0">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
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
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                                                    <p class="text-sm text-gray-500">
                                                        by {{ $event->organizer->name }} • 
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                            {{ $event->type === 'free' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                            {{ ucfirst($event->type) }}
                                                            @if($event->type === 'paid')
                                                                - ${{ number_format($event->price, 2) }}
                                                            @endif
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                <div>
                                                    <h4 class="font-medium text-gray-900 mb-2">Event Details</h4>
                                                    <p class="text-sm text-gray-600 mb-2">
                                                        <strong>Category:</strong> {{ $event->category }}
                                                    </p>
                                                    <p class="text-sm text-gray-600 mb-2">
                                                        <strong>Location:</strong> {{ $event->location }}
                                                        @if($event->address)
                                                            <br><span class="text-gray-500">{{ $event->address }}</span>
                                                        @endif
                                                    </p>
                                                    <p class="text-sm text-gray-600 mb-2">
                                                        <strong>Date:</strong>
                                                        {{ $event->start_date->format('M d, Y \a\t g:i A') }}
                                                    </p>
                                                    @if($event->max_tickets)
                                                        <p class="text-sm text-gray-600">
                                                            <strong>Max Tickets:</strong> {{ number_format($event->max_tickets) }}
                                                        </p>
                                                    @endif
                                                </div>

                                                <div>
                                                    <h4 class="font-medium text-gray-900 mb-2">Organizer Info</h4>
                                                    <p class="text-sm text-gray-600 mb-1">
                                                        <strong>Name:</strong> {{ $event->organizer->name }}
                                                    </p>
                                                    <p class="text-sm text-gray-600 mb-1">
                                                        <strong>Email:</strong> {{ $event->organizer->email }}
                                                    </p>
                                                    @if($event->organizer->organizerProfile)
                                                        <p class="text-sm text-gray-600 mb-1">
                                                            <strong>Company:</strong> {{ $event->organizer->organizerProfile->company_name ?? 'Not specified' }}
                                                        </p>
                                                    @endif
                                                    <p class="text-sm text-gray-600">
                                                        <strong>Submitted:</strong> {{ $event->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <h4 class="font-medium text-gray-900 mb-2">Description</h4>
                                                <p class="text-sm text-gray-600">{{ $event->description }}</p>
                                            </div>

                                            @if($event->terms_conditions)
                                                <div class="mb-4">
                                                    <h4 class="font-medium text-gray-900 mb-2">Terms & Conditions</h4>
                                                    <p class="text-sm text-gray-600">{{ $event->terms_conditions }}</p>
                                                </div>
                                            @endif

                                            @if($event->image_path)
                                                <div class="mb-4">
                                                    <h4 class="font-medium text-gray-900 mb-2">Event Image</h4>
                                                    <img src="{{ Storage::url($event->image_path) }}" 
                                                         alt="{{ $event->title }}" 
                                                         class="w-full max-w-md h-48 object-cover rounded-lg">
                                                </div>
                                            @endif
                                        </div>

                                        <div class="ml-6 flex flex-col space-y-2">
                                            <!-- Approve Button -->
                                            <form action="{{ route('admin.approve-event', $event) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-medium"
                                                        onclick="return confirm('Are you sure you want to approve this event?')">
                                                    Approve Event
                                                </button>
                                            </form>
                                            
                                            <!-- Reject Button -->
                                            <form action="{{ route('admin.reject-event', $event) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded font-medium"
                                                        onclick="return confirm('Are you sure you want to reject this event? This action cannot be undone.')">
                                                    Reject Event
                                                </button>
                                            </form>

                                            <!-- View Organizer Profile -->
                                            <a href="#" 
                                               onclick="showOrganizerModal({{ $event->organizer->id }})"
                                               class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded font-medium text-center">
                                                View Organizer
                                            </a>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No pending events</h3>
                            <p class="mt-1 text-sm text-gray-500">All event submissions have been processed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function showOrganizerModal(organizerId) {
            // This would open a modal with organizer details
            // For now, we'll just alert
            alert('Organizer details modal would open here for organizer ID: ' + organizerId);
        }
    </script>
</x-app-layout>
