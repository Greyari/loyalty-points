@extends('layouts.nav')

@section('title', 'History Product')
@section('page_title', 'History')

@section('content')
<div class="space-y-6 mb-6">
    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold font-poppins mb-0">Log Activity</h2>
    <p class="text-sm sm:text-base lg:text-lg font-light text-gray-500 font-poppins">Track all changes made to the system.</p>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <!-- Filter Section -->
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <form method="" action="">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search product or SKU..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="flex gap-2">
                    <select name="action" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                    <select name="period" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Time</option>
                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>This Month</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Statistics Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 sm:p-6 bg-gray-50">
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <p class="text-sm text-gray-500 font-poppins mb-1">Total Changes</p>
            <p class="text-2xl font-semibold font-poppins text-gray-900">{{ $statistics['total'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <p class="text-sm text-gray-500 font-poppins mb-1">This Month</p>
            <p class="text-2xl font-semibold font-poppins text-blue-600">{{ $statistics['this_month'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <p class="text-sm text-gray-500 font-poppins mb-1">Today</p>
            <p class="text-2xl font-semibold font-poppins text-green-600">{{ $statistics['today'] ?? 0 }}</p>
        </div>
    </div>

    <!-- History List -->
    <div class="divide-y divide-gray-200">
        @forelse($histories as $history)
        <div class="p-4 sm:p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <!-- Action Icon -->
                        <div class="w-10 h-10 rounded-full flex items-center justify-center 
                                {{ $history->action === 'created' ? 'bg-green-100' : '' }}
                                {{ $history->action === 'updated' ? 'bg-blue-100' : '' }}
                                {{ $history->action === 'deleted' ? 'bg-red-100' : '' }}">
                            @if($history->action === 'created')
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            @elseif($history->action === 'updated')
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            @elseif($history->action === 'deleted')
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-base font-medium font-poppins text-gray-900">{{ $history->product_name }}</h3>
                            <p class="text-sm text-gray-500 font-poppins">{{ $history->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="ml-13">
                        <!-- Action Badge -->
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $history->action === 'created' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $history->action === 'updated' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $history->action === 'deleted' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($history->action) }}
                        </span>

                        <!-- Action Details -->
                        @if($history->action === 'created')
                        <p class="text-sm text-gray-600 font-poppins mt-2">
                            <span class="font-medium">SKU:</span> {{ $history->sku }} •
                            <span class="font-medium">Qty:</span> {{ $history->quantity }} •
                            <span class="font-medium">Price:</span> Rp {{ number_format($history->price, 0, ',', '.') }} •
                            <span class="font-medium">Points:</span> {{ $history->point_unit }}
                        </p>
                        @elseif($history->action === 'deleted')
                        <p class="text-sm text-gray-600 font-poppins mt-2">
                            <span class="font-medium">SKU:</span> {{ $history->sku }} •
                            <span class="font-medium">Last Qty:</span> {{ $history->quantity }} •
                            <span class="font-medium">Last Price:</span> Rp {{ number_format($history->price, 0, ',', '.') }} •
                            <span class="font-medium">Points:</span> {{ $history->point_unit }}
                        </p>
                        @elseif($history->action === 'updated' && !empty($history->changes))
                        <div class="mt-2 space-y-1">
                            @foreach($history->changes as $field => $change)
                            <div class="text-sm font-poppins">
                                <span class="font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $field)) }}:</span>
                                @if($field === 'product_name')
                                <span class="text-red-600 line-through ml-1 block mt-1">{{ $change['old'] }}</span>
                                <span class="text-green-600 block mt-1">→ {{ $change['new'] }}</span>
                                @elseif(in_array($field, ['price']))
                                <span class="text-red-600 line-through ml-1">Rp {{ number_format($change['old'], 0, ',', '.') }}</span>
                                <span class="text-green-600 ml-1">→ Rp {{ number_format($change['new'], 0, ',', '.') }}</span>
                                @else
                                <span class="text-red-600 line-through ml-1">{{ $change['old'] }}</span>
                                <span class="text-green-600 ml-1">→ {{ $change['new'] }}</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <p class="text-xs text-gray-400 font-poppins mt-1">By: {{ $history->user_name ?? 'System' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 font-poppins">No history found</h3>
            <p class="mt-1 text-sm text-gray-500 font-poppins">Try adjusting your search or filter criteria.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($histories->hasPages())
    <div class="p-4 sm:p-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700 font-poppins">
                Showing <span class="font-medium">{{ $histories->firstItem() }}</span> to
                <span class="font-medium">{{ $histories->lastItem() }}</span> of
                <span class="font-medium">{{ $histories->total() }}</span> results
            </div>
            <div class="flex gap-2">
                @if($histories->onFirstPage())
                <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm font-poppins opacity-50 cursor-not-allowed" disabled>
                    Previous
                </button>
                @else
                <a href="{{ $histories->previousPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded-lg text-sm font-poppins hover:bg-gray-50">
                    Previous
                </a>
                @endif

                @foreach($histories->getUrlRange(1, $histories->lastPage()) as $page => $url)
                @if($page == $histories->currentPage())
                <button class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm font-poppins">{{ $page }}</button>
                @else
                <a href="{{ $url }}" class="px-3 py-1 border border-gray-300 rounded-lg text-sm font-poppins hover:bg-gray-50">{{ $page }}</a>
                @endif
                @endforeach

                @if($histories->hasMorePages())
                <a href="{{ $histories->nextPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded-lg text-sm font-poppins hover:bg-gray-50">
                    Next
                </a>
                @else
                <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm font-poppins opacity-50 cursor-not-allowed" disabled>
                    Next
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection