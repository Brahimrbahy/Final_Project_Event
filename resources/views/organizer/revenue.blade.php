@extends('layouts.dashbord')
@section('content')
    <div class="flex h-screen bg-gray-100">
            <x-dashboard-sidebar role="organizer" :current-route="request()->route()->getName()" />

        <div class="p-12 flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 lg:ml-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Total Revenue Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900">Total Revenue</h3>
                        <p class="text-4xl font-bold text-green-600 mt-2">${{ number_format($total_revenue, 2) }}</p>
                        <p class="text-sm text-gray-500 mt-1">After 15% platform fee</p>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Monthly Revenue Breakdown</h3>
                    
                    @if($monthly_revenue->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Month
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Revenue
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Transactions
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Avg per Transaction
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($monthly_revenue as $revenue)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ DateTime::createFromFormat('!m', $revenue->month)->format('F') }} {{ $revenue->year }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="text-lg font-semibold text-green-600">
                                                    ${{ number_format($revenue->revenue, 2) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($revenue->transactions) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ${{ number_format($revenue->revenue / $revenue->transactions, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Simple Bar Chart Visualization -->
                        <div class="mt-8">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Revenue Trend</h4>
                            <div class="flex items-end space-x-2 h-64">
                                @php
                                    $maxRevenue = $monthly_revenue->max('revenue');
                                @endphp
                                @foreach($monthly_revenue->reverse() as $revenue)
                                    @php
                                        $height = $maxRevenue > 0 ? ($revenue->revenue / $maxRevenue) * 200 : 0;
                                    @endphp
                                    <div class="flex flex-col items-center">
                                        <div class="bg-green-500 rounded-t" 
                                             style="height: {{ $height }}px; width: 40px;"
                                             title="${{ number_format($revenue->revenue, 2) }}">
                                        </div>
                                        <div class="text-xs text-gray-500 mt-2 transform -rotate-45 origin-top-left">
                                            {{ DateTime::createFromFormat('!m', $revenue->month)->format('M') }} {{ $revenue->year }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No revenue data available yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Event Revenue Breakdown -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Revenue by Event</h3>
                    
                    @if($event_revenues->count() > 0)
                        <div class="space-y-4">
                            @foreach($event_revenues as $index => $event)
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
                                                {{ $event->category }} • 
                                                {{ $event->start_date->format('M d, Y') }}
                                            </p>
                                            <p class="text-xs text-gray-400">
                                                {{ $event->tickets_sold }} tickets sold
                                                @if($event->type === 'paid')
                                                    • ${{ number_format($event->price, 2) }} each
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($event->type === 'free')
                                            <p class="text-lg font-semibold text-gray-500">Free Event</p>
                                        @else
                                            <p class="text-lg font-semibold text-green-600">
                                                ${{ number_format($event->total_revenue, 2) }}
                                            </p>
                                            <p class="text-xs text-gray-500">Your share (85%)</p>
                                        @endif
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
                        <h4 class="text-sm font-medium text-gray-500">Platform Fee Paid</h4>
                        <p class="text-2xl font-bold text-orange-600 mt-2">
                            @php
                                $totalGross = $total_revenue / 0.85; // Calculate gross before fee
                                $platformFee = $totalGross - $total_revenue;
                            @endphp
                            ${{ number_format($platformFee, 2) }}
                        </p>
                        <p class="text-xs text-gray-500">15% of gross revenue</p>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Payment Information</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>• Revenue shown is your share after the 15% platform fee</p>
                            <p>• Payments are processed automatically when tickets are purchased</p>
                            <p>• Revenue from free events is $0 as no payment is collected</p>
                            <p>• Contact support for payment schedule and bank transfer details</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
