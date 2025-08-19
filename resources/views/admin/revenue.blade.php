@extends('layouts.dashbord')

@section('content')
<div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <x-dashboard-sidebar role="admin" :current-route="request()->route()->getName()" />

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 lg:ml-0">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6">
            <!-- Total Revenue Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900">Total Admin Revenue (15% Fee)</h3>
                        <p class="text-4xl font-bold text-green-600 mt-2">${{ number_format($total_revenue, 2) }}</p>
                        <p class="text-sm text-gray-500 mt-1">Collected from all ticket sales</p>
                    </div>
                </div>
            </div>

           
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Top Revenue Generating Events</h3>
                    
                    @if($top_events->count() > 0)
                        <div class="space-y-4">
                            @foreach($top_events as $index => $event)
                                @php
                                    $eventRevenue = $event->payments->sum('admin_fee');
                                @endphp
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full 
                                                {{ $index < 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $index + 1 }}
                                            </span>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $event->title }}</h4>
                                            <p class="text-sm text-gray-500">
                                                by {{ $event->organizer->name }} • 
                                                {{ $event->start_date->format('M d, Y') }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ $event->payments->count() }} transactions
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-green-600">
                                            ${{ number_format($eventRevenue, 2) }}
                                        </p>
                                        <p class="text-xs text-gray-500">Admin Fee</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No events with revenue yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Revenue Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <h4 class="text-sm font-medium text-gray-500">Average Monthly Revenue</h4>
                        <p class="text-2xl font-bold text-blue-600 mt-2">
                            @if($monthly_revenue->count() > 0)
                                ${{ number_format($monthly_revenue->avg('revenue'), 2) }}
                            @else
                                $0.00
                            @endif
                        </p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <h4 class="text-sm font-medium text-gray-500">Total Transactions</h4>
                        <p class="text-2xl font-bold text-purple-600 mt-2">
                            {{ $monthly_revenue->sum('transactions') }}
                        </p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <h4 class="text-sm font-medium text-gray-500">Average Fee per Transaction</h4>
                        <p class="text-2xl font-bold text-orange-600 mt-2">
                            @if($monthly_revenue->sum('transactions') > 0)
                                ${{ number_format($total_revenue / $monthly_revenue->sum('transactions'), 2) }}
                            @else
                                $0.00
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection