@extends('layouts.nav')

    @section('title', 'Inventory')
    @section('page_title', 'Inventory')

    @section('content')
    <div class="space-y-6 mb-6">
        <h2 class="text-4xl font-semibold font-poppins mb-0">Inventory</h2>
        <p class="text-lg font-light text-gray-500 font-poppins">Manage your inventory.</p>
    </div>

    <x-data-tables
        :headers="['Product Name', 'SKU', 'Quantity', 'Price', 'Point Unit']"
        :rows="$products"
        onAdd="true"
        onEdit="true"
        onDelete="true"
    />

    <!-- Create Modal -->
    <x-modal id="productCreateModal" title="Add New Product" size="lg" submitText="Save">
        <form id="createForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Product Name</label>
                <input type="text" name="name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700 focus:border-transparent"
                    placeholder="Enter product name" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">SKU</label>
                    <input type="text" name="sku"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                        placeholder="SKU-001" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Quantity</label>
                    <input type="number" name="quantity"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                        placeholder="0" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Price</label>
                    <input type="text" name="price"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                        placeholder="0" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Points Per Unit</label>
                    <input type="number" name="points_per_unit"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-gray-700"
                        placeholder="0" required>
                </div>
            </div>

        </form>
    </x-modal>

    <!-- Edit Modal -->
    <x-modal id="productEditModal" title="Edit Product" size="lg" submitText="Update" submitButtonClass="bg-green-600 hover:bg-green-700 text-white">
        <form id="editForm" class="space-y-4">
            <input type="hidden" name="id" id="edit_id">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Product Name</label>
                <input type="text" name="name" id="edit_name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700 focus:border-transparent"
                    placeholder="Enter product name" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">SKU</label>
                    <input type="text" name="sku" id="edit_sku"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                        placeholder="SKU-001" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Quantity</label>
                    <input type="number" name="quantity" id="edit_quantity"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                        placeholder="0" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Price</label>
                    <input type="text" name="price" id="edit_price"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                        placeholder="0" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Points per Unit</label>
                    <input type="number" name="points_per_unit" id="edit_points_per_unit"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-gray-700"
                        placeholder="0" required>
                </div>
            </div>
        </form>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal id="productDeleteModal" title="Confirm Delete" size="sm" submitText="Delete" submitButtonClass="bg-red-600 hover:bg-red-700 text-white">
        <div class="text-center py-4">
            <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <p class="text-gray-700 font-poppins mb-2">Are you sure you want to delete this product?</p>
            <p class="text-sm text-gray-600 font-poppins font-semibold" id="delete_name"></p>
            <p class="text-sm text-gray-500 mt-3 font-poppins">This action cannot be undone.</p>
        </div>
        <input type="hidden" id="delete_id">
    </x-modal>

    @push('scripts')
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="{{ asset('js/inventory.js') }}"></script>
    @endpush

@endsection
