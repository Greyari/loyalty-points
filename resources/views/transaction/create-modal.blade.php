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