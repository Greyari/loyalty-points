@extends('layouts.nav')

@section('title', 'Activity Log')
@section('page_title', 'Activity Log')

@section('content')
<div class="space-y-6 mb-6">
    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold font-poppins mb-0">Log Activity</h2>
    <p class="text-sm sm:text-base lg:text-lg font-light text-gray-500 font-poppins">Track all changes made to the system.</p>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div id="successNotification" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between" role="alert">
    <span class="block sm:inline font-poppins">{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
@endif

@if(session('error'))
<div id="errorNotification" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between" role="alert">
    <span class="block sm:inline font-poppins">{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
@endif

@if(session('info'))
<div id="infoNotification" class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between" role="alert">
    <span class="block sm:inline font-poppins">{{ session('info') }}</span>
    <button onclick="this.parentElement.remove()" class="text-blue-700 hover:text-blue-900">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
@endif

<!-- Statistics Cards -->
@if(isset($statistics))
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <p class="text-sm text-gray-500 font-poppins">Total Logs</p>
        <p class="text-2xl font-semibold text-gray-900 font-poppins">{{ number_format($statistics['total']) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4">
        <p class="text-sm text-gray-500 font-poppins">This Month</p>
        <p class="text-2xl font-semibold text-gray-900 font-poppins">{{ number_format($statistics['this_month']) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4">
        <p class="text-sm text-gray-500 font-poppins">Today</p>
        <p class="text-2xl font-semibold text-gray-900 font-poppins">{{ number_format($statistics['today']) }}</p>
    </div>
</div>
@endif

<div class="bg-white rounded-lg shadow-sm overflow-hidden">

    <!-- Filter Section with Delete Button -->
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row gap-4 mb-4">
            <form method="GET" action="{{ route('log.index') }}" class="flex-1 flex flex-col sm:flex-row gap-4">
                <div class="flex-1 relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by module or user..."
                        class="placeholder-gray-400 w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        onkeydown="if (event.key === 'Enter') this.form.submit()">

                    <button type="submit"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5"
                            fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </button>
                </div>

                <div class="flex gap-2">
                    <select name="action" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Actions</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                        <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                    </select>
                    <select name="module" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Modules</option>
                        <option value="customer" {{ request('module') == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="product" {{ request('module') == 'product' ? 'selected' : '' }}>Product</option>
                        <option value="order" {{ request('module') == 'order' ? 'selected' : '' }}>Order</option>
                        <option value="user" {{ request('module') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="auth" {{ request('module') == 'auth' ? 'selected' : '' }}>Authentication</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Delete Monthly Log Button -->
        <div class="flex justify-end">
            <button onclick="openDeleteLogModal()" class="px-4 py-2 bg-red-600 rounded-lg text-white hover:bg-red-700 transition-colors font-poppins">
                Hapus Log Bulanan
            </button>
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
                                {{ $history->action === 'deleted' ? 'bg-red-100' : '' }}
                                {{ $history->action === 'login' ? 'bg-purple-100' : '' }}
                                {{ $history->action === 'logout' ? 'bg-gray-100' : '' }}">
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
                            @elseif($history->action === 'login')
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            @elseif($history->action === 'logout')
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-base font-medium font-poppins text-gray-900">{{ ucfirst($history->module) }}</h3>
                            <p class="text-sm text-gray-500 font-poppins">{{ $history->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="ml-13">
                        <!-- Action Badge -->
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $history->action === 'created' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $history->action === 'updated' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $history->action === 'deleted' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $history->action === 'login' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $history->action === 'logout' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($history->action) }}
                        </span>

                        <!-- Changes Details -->
                        @if(!empty($history->changes))
                        <div class="mt-3 space-y-3 text-sm font-poppins">
                            @php
                            $before = $history->changes['before'] ?? [];
                            $after = $history->changes['after'] ?? [];
                            $isAuth = $history->module === 'auth';
                            @endphp

                            {{-- Special handling for Auth (Login/Logout) - No Before/After labels --}}
                            @if($isAuth && isset($after['auth']))
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="space-y-0.5 text-xs">
                                    @foreach($after['auth'] as $key => $value)
                                    <p><span class="text-gray-600 font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span> <span class="text-gray-800">{{ $value }}</span></p>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            {{-- Normal Before/After for other modules --}}

                            {{-- Before Section --}}
                            @if(!empty($before))
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <p class="font-semibold text-red-800 mb-2">Before:</p>

                                {{-- Customer/Product/User/Auth data --}}
                                @foreach(['customer', 'product', 'user', 'order', 'auth'] as $entityType)
                                @if(isset($before[$entityType]) && !empty($before[$entityType]))
                                <div class="mb-2">
                                    <p class="font-medium text-gray-700">{{ ucfirst($entityType) }}:</p>
                                    <div class="ml-3 space-y-0.5 text-xs">
                                        @foreach($before[$entityType] as $key => $value)
                                        <p><span class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span> <span class="text-gray-800">{{ is_numeric($value) ? number_format($value, 0, ',', '.') : $value }}</span></p>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @endforeach

                                {{-- Order Items --}}
                                @if(isset($before['items']) && count($before['items']) > 0)
                                <div class="mb-2">
                                    <p class="font-medium text-gray-700">Items:</p>
                                    <div class="ml-3 space-y-1">
                                        @foreach($before['items'] as $item)
                                        <div class="text-xs bg-white p-2 rounded border border-red-100">
                                            <p class="font-medium">{{ $item['product_name'] ?? 'N/A' }} ({{ $item['sku'] ?? 'N/A' }})</p>
                                            <p class="text-gray-600">Qty: {{ $item['qty'] ?? 0 }}, Points: {{ number_format($item['total_points'] ?? 0, 0, ',', '.') }}, Price: {{ number_format($item['total_price'] ?? 0, 0, ',', '.') }}</p>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                {{-- Product Stock Changes --}}
                                @if(isset($before['products']) && count($before['products']) > 0)
                                <div>
                                    <p class="font-medium text-gray-700">Product Stock:</p>
                                    <div class="ml-3 space-y-0.5 text-xs">
                                        @foreach($before['products'] as $productId => $product)
                                        <p>{{ $product['name'] ?? 'N/A' }} ({{ $product['sku'] ?? 'N/A' }}): <span class="font-medium">{{ $product['quantity'] ?? 0 }}</span></p>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif

                            {{-- After Section --}}
                            @if(!empty($after))
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <p class="font-semibold text-green-800 mb-2">After:</p>

                                {{-- Customer/Product/User/Auth data --}}
                                @foreach(['customer', 'product', 'user', 'order', 'auth'] as $entityType)
                                @if(isset($after[$entityType]) && !empty($after[$entityType]))
                                <div class="mb-2">
                                    <p class="font-medium text-gray-700">{{ ucfirst($entityType) }}:</p>
                                    <div class="ml-3 space-y-0.5 text-xs">
                                        @foreach($after[$entityType] as $key => $value)
                                        @php
                                        $beforeValue = $before[$entityType][$key] ?? null;
                                        $hasChanged = isset($before[$entityType]) && $beforeValue != $value;
                                        @endphp
                                        <p>
                                            <span class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                            @if($hasChanged)
                                            <span class="text-red-600 line-through">{{ is_numeric($beforeValue) ? number_format($beforeValue, 0, ',', '.') : $beforeValue }}</span>
                                            <span class="text-green-600 font-medium"> → {{ is_numeric($value) ? number_format($value, 0, ',', '.') : $value }}</span>
                                            @else
                                            <span class="text-gray-800">{{ is_numeric($value) ? number_format($value, 0, ',', '.') : $value }}</span>
                                            @endif
                                        </p>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                @endforeach

                                {{-- Order Items --}}
                                @if(isset($after['items']) && count($after['items']) > 0)
                                <div class="mb-2">
                                    <p class="font-medium text-gray-700">Items:</p>
                                    <div class="ml-3 space-y-1">
                                        @foreach($after['items'] as $item)
                                        <div class="text-xs bg-white p-2 rounded border border-green-100">
                                            <p class="font-medium">{{ $item['product_name'] ?? 'N/A' }} ({{ $item['sku'] ?? 'N/A' }})</p>
                                            <p class="text-gray-600">Qty: {{ $item['qty'] ?? 0 }}, Points: {{ number_format($item['total_points'] ?? 0, 0, ',', '.') }}, Price: {{ number_format($item['total_price'] ?? 0, 0, ',', '.') }}</p>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                {{-- Product Stock Changes --}}
                                @if(isset($after['products']) && count($after['products']) > 0)
                                <div>
                                    <p class="font-medium text-gray-700">Product Stock:</p>
                                    <div class="ml-3 space-y-0.5 text-xs">
                                        @foreach($after['products'] as $productId => $product)
                                        @php
                                        $beforeQty = $before['products'][$productId]['quantity'] ?? null;
                                        $afterQty = $product['quantity'] ?? 0;
                                        @endphp
                                        <p>
                                            {{ $product['name'] ?? 'N/A' }} ({{ $product['sku'] ?? 'N/A' }}):
                                            @if($beforeQty !== null && $beforeQty != $afterQty)
                                            <span class="text-red-600 line-through">{{ $beforeQty }}</span>
                                            <span class="text-green-600 font-medium"> → {{ $afterQty }}</span>
                                            @else
                                            <span class="font-medium">{{ $afterQty }}</span>
                                            @endif
                                        </p>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif

                            @endif
                            {{-- End normal Before/After --}}
                        </div>
                        @endif

                        <p class="text-xs text-gray-400 font-poppins mt-3">By: {{ $history->user->name ?? 'System' }} • IP: {{ $history->ip_address ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-400 font-poppins mt-0.5">Agent: {{ $history->user_agent ?? 'Unknown' }}</p>
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

<!-- Delete Monthly Log Modal -->
<div id="deleteLogModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 m-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold font-poppins text-gray-900">Delete Monthly Log</h2>
            <button onclick="closeDeleteLogModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="deleteLogForm" method="POST" action="{{ route('log.delete.month') }}">
            @csrf
            @method('DELETE')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Choose Month & Year</label>
                <select name="month_year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent font-poppins" required>
                    @foreach ($availableMonths as $item)
                    @php
                    $monthNum = $item->month;
                    $yearNum = $item->year;
                    $dateObj = DateTime::createFromFormat('!m', $monthNum);
                    $monthName = $dateObj->format('F');
                    @endphp
                    <option value="{{ $yearNum }}-{{ $monthNum }}">
                        {{ $monthName }} {{ $yearNum }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                <p class="text-sm text-red-800 font-poppins">
                    <strong>Warning:</strong> Logs that are deleted cannot be recovered.
                </p>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDeleteLogModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors font-poppins">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-poppins">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Open delete modal
    function openDeleteLogModal() {
        const modal = document.getElementById('deleteLogModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Close delete modal
    function closeDeleteLogModal() {
        const modal = document.getElementById('deleteLogModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('deleteLogModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteLogModal();
        }
    });

    // Auto-close modal on successful delete and show notification
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide notifications after 5 seconds
        const notifications = ['successNotification', 'errorNotification', 'infoNotification'];
        notifications.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                setTimeout(() => {
                    element.style.transition = 'opacity 0.3s ease-out';
                    element.style.opacity = '0';
                    setTimeout(() => element.remove(), 300);
                }, 5000);
            }
        });

        // Handle form submission
        const deleteForm = document.getElementById('deleteLogForm');
        if (deleteForm) {
            deleteForm.addEventListener('submit', function(e) {
                // Show loading state on submit button
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin h-5 w-5 text-white inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Deleting...
                `;
            });
        }
    });
</script>
@endpush