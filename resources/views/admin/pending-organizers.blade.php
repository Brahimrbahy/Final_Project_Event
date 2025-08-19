@extends('layouts.dashbord')
@section('content')
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

                    @if($organizers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Organizer
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Company
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Contact
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Registered
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($organizers as $organizer)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700">
                                                                {{ substr($organizer->name, 0, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $organizer->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $organizer->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $organizer->company_name ?? 'Not specified' }}
                                                </div>
                                                @if($organizer->website)
                                                    <div class="text-sm text-gray-500">
                                                        <a href="{{ $organizer->website }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                                            {{ $organizer->website }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $organizer->contact_phone ?? 'Not specified' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    Status: <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        {{ ucfirst($organizer->status) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $organizer->created_at->format('M d, Y') }}
                                                <div class="text-xs text-gray-400">
                                                    {{ $organizer->created_at->diffForHumans() }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <!-- View Details Button -->
                                                    <button onclick="showOrganizerDetails({{ $organizer->id }})" 
                                                            class="text-blue-600 hover:text-blue-900">
                                                        View Details
                                                    </button>
                                                    
                                                    <!-- Approve Button -->
                                                    <form action="{{ route('admin.approve-organizer', $organizer) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs"
                                                                onclick="return confirm('Are you sure you want to approve this organizer?')">
                                                            Approve
                                                        </button>
                                                    </form>
                                                    
                                                    <!-- Reject Button -->
                                                    <form action="{{ route('admin.reject-organizer', $organizer) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs"
                                                                onclick="return confirm('Are you sure you want to reject this organizer? This action cannot be undone.')">
                                                            Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Hidden details row -->
                                        <tr id="details-{{ $organizer->id }}" class="hidden">
                                            <td colspan="5" class="px-6 py-4 bg-gray-50">
                                                <div class="text-sm text-gray-900">
                                                    <h4 class="font-medium mb-2">Bio:</h4>
                                                    <p class="mb-4">{{ $organizer->organizerProfile->bio ?? 'No bio provided' }}</p>
                                                    
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div>
                                                            <h5 class="font-medium">Contact Information:</h5>
                                                            <p>Email: {{ $organizer->email }}</p>
                                                            <p>Phone: {{ $organizer->organizerProfile->phone ?? 'Not provided' }}</p>
                                                            <p>Contact Info: {{ $organizer->organizerProfile->contact_info ?? 'Not provided' }}</p>
                                                        </div>
                                                        <div>
                                                            <h5 class="font-medium">Company Details:</h5>
                                                            <p>Company: {{ $organizer->organizerProfile->company_name ?? 'Not provided' }}</p>
                                                            <p>Website: {{ $organizer->organizerProfile->website ?? 'Not provided' }}</p>
                                                            <p>Registered: {{ $organizer->created_at->format('F d, Y \a\t g:i A') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $organizers->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No pending organizers</h3>
                            <p class="mt-1 text-sm text-gray-500">All organizer applications have been processed.</p>
                        </div>
                    @endif
                </div>
            </div>
            </div>
        </div>
    </div>

    <script>
        function showOrganizerDetails(organizerId) {
            const detailsRow = document.getElementById('details-' + organizerId);
            if (detailsRow.classList.contains('hidden')) {
                detailsRow.classList.remove('hidden');
            } else {
                detailsRow.classList.add('hidden');
            }
        }
    </script>
    @endsection