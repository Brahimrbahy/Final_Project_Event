@extends('layouts.dashbord')
@section('content')
    <div class="flex h-screen bg-gray-50">
        <x-dashboard-sidebar role="admin" :current-route="request()->route()->getName()" />

        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 lg:ml-0">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                
                <!-- Page Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Events Management</h1>
                            <p class="text-gray-600 mt-1">Manage and review all events on your platform</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-sm text-gray-500">
                                Total: <span class="font-semibold text-gray-900">{{ $events->total() }}</span> events
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Events Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    @if($events->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Event Details
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Organizer
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Type & Price
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Event Date
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Tickets
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-50">
                                    @foreach($events as $event)
                                        <tr class="hover:bg-gray-25 transition-colors duration-200">
                                            <td class="px-6 py-5">
                                                <div class="flex items-center space-x-4">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-semibold text-gray-900 mb-1">
                                                            {{ $event->title }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            <span class="inline-flex items-center">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                                </svg>
                                                                {{ $event->category }}
                                                            </span>
                                                            <span class="mx-2">•</span>
                                                            <span class="inline-flex items-center">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                </svg>
                                                                {{ $event->location }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                                                        <span class="text-white font-semibold text-sm">{{ substr($event->organizer->name, 0, 1) }}</span>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $event->organizer->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $event->organizer->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="space-y-1">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                        {{ $event->type === 'free' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                        {{ ucfirst($event->type) }} Event
                                                    </span>
                                                    @if($event->type === 'paid')
                                                        <div class="text-sm font-semibold text-gray-900">
                                                            ${{ number_format($event->price, 2) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                    {{ $event->approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    <span class="w-2 h-2 rounded-full mr-2 
                                                        {{ $event->approved ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                                                    {{ $event->approved ? 'Approved' : 'Pending Review' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $event->start_date->format('M d, Y') }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $event->start_date->format('g:i A') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $event->tickets_sold }}
                                                    @if($event->max_tickets)
                                                        <span class="text-gray-400">/ {{ $event->max_tickets }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    @if($event->max_tickets)
                                                        {{ round(($event->tickets_sold / $event->max_tickets) * 100, 1) }}% sold
                                                    @else
                                                        tickets sold
                                                    @endif
                                                </div>
                                                @if($event->max_tickets)
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                                                        <div class="bg-blue-500 h-1.5 rounded-full" 
                                                             style="width: {{ min(($event->tickets_sold / $event->max_tickets) * 100, 100) }}%"></div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="flex items-center space-x-3">
                                                    @if(!$event->approved)
                                                        <form action="{{ route('admin.approve-event', $event) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-sm"
                                                                    onclick="return confirm('Approve this event?')">
                                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                Approve
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <a href="{{ route('events.show', $event) }}" 
                                                       class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        View
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Enhanced Pagination -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            {{ $events->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No events found</h3>
                            <p class="text-gray-500 max-w-sm mx-auto">No events have been created yet. Events will appear here once organizers start creating them.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection