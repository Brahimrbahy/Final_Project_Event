@extends('layouts.dashbord')

@section('content')
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <x-dashboard-sidebar role="admin" :current-route="request()->route()->getName()" />

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 lg:ml-0">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                
                <!-- Page Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Organizers Management</h1>
                            <p class="text-gray-600 mt-1">Manage and review all event organizers on your platform</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-sm text-gray-500">
                                Total: <span class="font-semibold text-gray-900">{{ $organizers->total() }}</span> organizers
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Organizers Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    @if($organizers->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Organizer Details
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Company & Website
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Events Created
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Join Date
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50">
                                    @foreach($organizers as $organizer)
                                        <tr class="hover:bg-gray-25 transition-colors duration-200">
                                            <td class="px-6 py-5">
                                                <div class="flex items-center space-x-4">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                                        <span class="text-white font-bold text-sm">
                                                            {{ substr($organizer->name, 0, 2) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-semibold text-gray-900 mb-1">
                                                            {{ $organizer->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                                            </svg>
                                                            {{ $organizer->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 mb-1">
                                                        {{ $organizer->organizerProfile->company_name ?? 'Not specified' }}
                                                    </div>
                                                    @if($organizer->organizerProfile && $organizer->organizerProfile->website)
                                                        <div class="text-sm">
                                                            <a href="{{ $organizer->organizerProfile->website }}" target="_blank" 
                                                               class="inline-flex items-center text-blue-600 hover:text-blue-700 group">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                                </svg>
                                                                Visit Website
                                                                <svg class="w-3 h-3 ml-1 group-hover:translate-x-0.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="text-sm text-gray-400">
                                                            No website provided
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                    {{ $organizer->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    <span class="w-2 h-2 rounded-full mr-2 
                                                        {{ $organizer->is_approved ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                                                    {{ $organizer->is_approved ? 'Approved' : 'Pending Review' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="space-y-1">
                                                    <div class="text-sm font-semibold text-gray-900">
                                                        {{ $organizer->events->count() }} 
                                                        <span class="font-normal text-gray-500">total</span>
                                                    </div>
                                                    @if($organizer->events->where('approved', true)->count() > 0)
                                                        <div class="text-xs text-green-600 font-medium">
                                                            {{ $organizer->events->where('approved', true)->count() }} approved
                                                        </div>
                                                    @endif
                                                    @if($organizer->events->where('approved', false)->count() > 0)
                                                        <div class="text-xs text-yellow-600 font-medium">
                                                            {{ $organizer->events->where('approved', false)->count() }} pending
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $organizer->created_at->format('M d, Y') }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $organizer->created_at->diffForHumans() }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="flex items-center space-x-3">
                                                    @if(!$organizer->is_approved)
                                                        <form action="{{ route('admin.approve-organizer', $organizer) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-sm"
                                                                    onclick="return confirm('Approve this organizer?')">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                Approve
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <button onclick="viewOrganizerDetails({{ $organizer->id }})" 
                                                            class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        View Details
                                                    </button>

                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Enhanced Pagination -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            {{ $organizers->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No organizers found</h3>
                            <p class="text-gray-500 max-w-sm mx-auto">No organizers have registered yet. Once people start signing up as organizers, they'll appear here for review.</p>
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
@endsection