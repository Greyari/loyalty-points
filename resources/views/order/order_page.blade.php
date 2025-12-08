@extends('layouts.nav')

@section('title', 'Orders')
@section('page_title', 'Orders')

@section('content')
<div class="space-y-6 mb-6">
    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold font-poppins mb-0">Orders Management</h2>
    <p class="text-sm sm:text-base lg:text-lg font-light text-gray-500 font-poppins">Manage customer orders with multiple products.</p>
</div>

<x-data-tables
    :headers="['Order ID', 'Date', 'Customer', 'Items Count', 'Total Items', 'Total Points']"
    :rows="$orders"
    onAdd="true"
    onEdit="true"
    onDelete="true"
    onView="true" />

<!-- Create Modal -->
<x-modal id="orderCreateModal" title="Create New Order" size="lg" submitText="Save Order">
    <form id="createForm" class="space-y-4">

        <!-- Customer Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">
                Customer <span class="text-red-500">*</span>
            </label>
            <select name="customer_id" id="customer_select"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                required>
                <option value="">Select customer</option>
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Notes -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Notes (Optional)</label>
            <textarea name="notes" id="notes"
                class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                rows="2" placeholder="Add order notes..."></textarea>
        </div>

        <!-- Items Section -->
        <div>
            <div class="flex justify-between items-center mb-3">
                <label class="block text-sm font-medium text-gray-700 font-poppins">
                    Products <span class="text-red-500">*</span>
                </label>
                <button type="button" id="addItemBtn"
                    class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Product
                </button>
            </div>

            <div id="itemsContainer" class="space-y-3">
                <!-- Items will be added here dynamically -->
            </div>
        </div>

        <!-- Summary -->
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 font-poppins">Total Items (QTY)</p>
                    <p class="text-2xl font-semibold text-gray-900 font-poppins" id="summaryTotalItems">0</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-poppins">Total Points</p>
                    <p class="text-2xl font-semibold text-blue-600 font-poppins" id="summaryTotalPoints">0</p>
                </div>
            </div>
        </div>

    </form>
</x-modal>

<!-- View Modal -->
<x-modal id="orderViewModal" title="Order Details" size="lg" :showSubmit="false">
    <div id="orderViewContent" class="space-y-4">

    </div>
</x-modal>

<!-- Edit Modal -->
<x-modal id="orderEditModal" title="Edit Order" size="lg" submitText="Update Order" submitButtonClass="bg-green-600 hover:bg-green-700 text-white">
    <form id="editForm" class="space-y-4">
        <input type="hidden" name="id" id="edit_id">

        <!-- Order ID (Read Only) -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Order ID</label>
            <input type="text" id="edit_order_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100"
                readonly>
        </div>

        <!-- Customer Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">
                Customer <span class="text-red-500">*</span>
            </label>
            <select name="customer_id" id="edit_customer_select"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                required>
                <option value="">Select customer</option>
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Notes -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Notes</label>
            <textarea name="notes" id="edit_notes"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                rows="2"></textarea>
        </div>

        <!-- Items Section -->
        <div>
            <div class="flex justify-between items-center mb-3">
                <label class="block text-sm font-medium text-gray-700 font-poppins">Products</label>
                <button type="button" id="editAddItemBtn"
                    class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Product
                </button>
            </div>

            <div id="editItemsContainer" class="space-y-3">
                <!-- Items will be loaded here -->
            </div>
        </div>

        <!-- Summary -->
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 font-poppins">Total Items (Qty)</p>
                    <p class="text-2xl font-semibold text-gray-900 font-poppins" id="editSummaryTotalItems">0</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-poppins">Total Points</p>
                    <p class="text-2xl font-semibold text-blue-600 font-poppins" id="editSummaryTotalPoints">0</p>
                </div>
            </div>
        </div>
    </form>
</x-modal>

<!-- Delete Confirmation Modal -->
<x-modal id="orderDeleteModal" title="Confirm Delete" size="sm" submitText="Delete" submitButtonClass="bg-red-600 hover:bg-red-700 text-white">
    <div class="text-center py-4">
        <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <p class="text-gray-700 font-poppins mb-2">Are you sure you want to delete this order?</p>
        <p class="text-sm text-gray-600 font-poppins font-semibold" id="delete_order_id"></p>
        <p class="text-sm text-gray-500 mt-3 font-poppins">This action cannot be undone and will restore product stock.</p>
    </div>
    <input type="hidden" id="delete_id">
</x-modal>

@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    // Pass products data to JavaScript
    window.productsData = @json($products);
</script>
<script src="{{ asset('js/order.js') }}"></script>
@endpush

<style>
    /* Select2 Styling */
    .select2-container--default .select2-selection--single {
        height: 42px;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        background-color: #ffffff;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px;
        padding-left: 12px;
        color: #111827;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
        right: 8px;
    }

    .select2-dropdown {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 8px 12px;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #3b82f6 !important;
        color: #ffffff !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #374151;
        box-shadow: 0 0 0 2px rgba(55, 65, 81, 0.1);
    }

    /* Item Row Animation */
    .item-row {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

@endsection