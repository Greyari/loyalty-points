@extends('layouts.nav')

@section('title', 'Point Transactions')
@section('page_title', 'Point Transactions')

@section('content')
<div class="space-y-6 mb-6">
    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold font-poppins mb-0">Point Transactions</h2>
    <p class="text-sm sm:text-base lg:text-lg  font-light text-gray-500 font-poppins">Manage customer point transactions.</p>
</div>

<x-data-tables
    :headers="['Order ID', 'Date', 'Customer', 'Product', 'SKU', 'Qty', 'Points/Unit', 'Total Points']"
    :rows="$transactions"
    onAdd="true"
    onEdit="true"
    onDelete="true" />

<!-- Create Modal -->
<x-modal id="transactionCreateModal" title="Add New Transaction" size="lg" submitText="Save">
    <form id="createForm" class="space-y-4">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Customer <span class="text-red-500">*</span></label>
            <select name="customer_id" id="customer_select"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                required>
                <option value="">Select customer</option>
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Product <span class="text-red-500">*</span></label>
            <select name="product_id" id="product_select"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                required>
                <option value="">Select product</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}"
                    data-sku="{{ $product->sku }}"
                    data-points="{{ $product->points_per_unit }}">
                    {{ $product->name }} ({{ $product->sku }}) - {{ $product->points_per_unit }} pts
                </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Quantity <span class="text-red-500">*</span></label>
                <input type="number" name="qty" id="qty_input"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                    placeholder="1" min="1" value="1" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Total Points</label>
                <input type="text" id="total_points_display"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100"
                    placeholder="0" readonly>
            </div>
        </div>

    </form>
</x-modal>

<!-- Edit Modal -->
<x-modal id="transactionEditModal" title="Edit Transaction" size="lg" submitText="Update" submitButtonClass="bg-green-600 hover:bg-green-700 text-white">
    <form id="editForm" class="space-y-4">
        <input type="hidden" name="id" id="edit_id">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Order ID</label>
            <input type="text" name="order_id" id="edit_order_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700 bg-gray-100"
                readonly>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Customer <span class="text-red-500">*</span></label>
            <select name="customer_id" id="edit_customer_select"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                required>
                <option value="">Select customer</option>
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Product <span class="text-red-500">*</span></label>
            <select name="product_id" id="edit_product_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                required>
                <option value="">Select product</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}"
                    data-sku="{{ $product->sku }}"
                    data-points="{{ $product->points_per_unit }}">
                    {{ $product->name }} ({{ $product->sku }}) - {{ $product->points_per_unit }} pts
                </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Quantity <span class="text-red-500">*</span></label>
                <input type="number" name="qty" id="edit_qty"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                    placeholder="1" min="1" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Total Points</label>
                <input type="text" id="edit_total_points_display"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100"
                    placeholder="0" readonly>
            </div>
        </div>
    </form>
</x-modal>

<!-- Delete Confirmation Modal -->
<x-modal id="transactionDeleteModal" title="Confirm Delete" size="sm" submitText="Delete" submitButtonClass="bg-red-600 hover:bg-red-700 text-white">
    <div class="text-center py-4">
        <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <p class="text-gray-700 font-poppins mb-2">Are you sure you want to delete this transaction?</p>
        <p class="text-sm text-gray-600 font-poppins font-semibold" id="delete_order_id"></p>
        <p class="text-sm text-gray-500 mt-3 font-poppins">This action cannot be undone and will affect customer points.</p>
    </div>
    <input type="hidden" id="delete_id">
</x-modal>

@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/point_transaction.js') }}"></script>
@endpush
<style>
    /* Select2 Container */
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

    /* Dropdown */
    .select2-dropdown {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Search Input */
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        padding: 8px 12px;
        font-size: 14px;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        outline: none;
        border-color: #374151;
        box-shadow: 0 0 0 2px rgba(55, 65, 81, 0.1);
    }

    /* Dropdown Results */
    .select2-container--default .select2-results__option {
        padding: 10px 12px;
        font-size: 14px;
        color: #374151;
    }

    /* Hover State */
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #3b82f6 !important;
        color: #ffffff !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] .text-gray-500,
    .select2-container--default .select2-results__option--highlighted[aria-selected] .text-gray-900 {
        color: #ffffff !important;
    }

    /* Selected State */
    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #eff6ff;
        color: #1e40af;
    }

    /* Focused State */
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #374151;
        box-shadow: 0 0 0 2px rgba(55, 65, 81, 0.1);
    }

    /* Disabled State */
    .select2-container--default .select2-selection--single[aria-disabled="true"] {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }

    /* Loading State */
    .select2-container--default .select2-results__option--loading {
        color: #9ca3af;
    }

    /* No Results */
    .select2-container--default .select2-results__message {
        color: #6b7280;
        padding: 12px;
    }

    /* Clear Button */
    .select2-container--default .select2-selection__clear {
        color: #6b7280;
        font-size: 18px;
        font-weight: bold;
        margin-right: 8px;
    }

    .select2-container--default .select2-selection__clear:hover {
        color: #374151;
    }

    /* Product Template Specific */
    .select2-results__option .flex.flex-col {
        line-height: 1.4;
    }

    .select2-results__option .flex.flex-col .font-medium {
        display: block;
        margin-bottom: 2px;
    }

    .select2-results__option .flex.flex-col .text-xs {
        display: block;
    }

    /* Ensure white text on blue background for ALL child elements */
    .select2-container--default .select2-results__option--highlighted[aria-selected] * {
        color: #ffffff !important;
    }
</style>

@endsection