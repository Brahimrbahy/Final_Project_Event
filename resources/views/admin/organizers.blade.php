<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Organizers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
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
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Events
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Joined
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
                                                    {{ $organizer->organizerProfile->company_name ?? 'Not specified' }}
                                                </div>
                                                @if($organizer->organizerProfile->website)
                                                    <div class="text-sm text-gray-500">
                                                        <a href="{{ $organizer->organizerProfile->website }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                                            Website
                                                        </a>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $organizer->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $organizer->is_approved ? 'Approved' : 'Pending' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $organizer->events->count() }} events
                                                @if($organizer->events->where('approved', true)->count() > 0)
                                                    <div class="text-xs text-green-600">
                                                        {{ $organizer->events->where('approved', true)->count() }} approved
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $organizer->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    @if(!$organizer->is_approved)
                                                        <form action="{{ route('admin.approve-organizer', $organizer) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="text-green-600 hover:text-green-900"
                                                                    onclick="return confirm('Approve this organizer?')">
                                                                Approve
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <button onclick="viewOrganizerDetails({{ $organizer->id }})" 
                                                            class="text-blue-600 hover:text-blue-900">
                                                        View Details
                                                    </button>
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
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No organizers found</h3>
                            <p class="mt-1 text-sm text-gray-500">No organizers have registered yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewOrganizerDetails(organizerId) {
            // This would open a modal or redirect to a detailed view
            alert('View organizer details for ID: ' + organizerId);
        }
    </script>
</x-app-layout>
