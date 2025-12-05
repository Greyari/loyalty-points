<!-- Create Modal -->
<x-modal id="transactionCreateModal" title="Add New Transaction" size="lg" submitText="Save">
    <form id="createForm" class="space-y-4">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Customer <span class="text-red-500">*</span></label>
            <select name="customer_id" id="customer_select"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500"
                required>
                <option value="">Select customer</option>
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Product <span class="text-red-500">*</span></label>
            <select name="product_ids[]" id="product_select"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700"
                multiple required>
                @foreach($products as $product)
                <option value="{{ $product->id }}"
                    data-sku="{{ $product->sku }}"
                    data-points="{{ $product->points_per_unit }}">
                    {{ $product->name }} ({{ $product->sku }}) - {{ $product->points_per_unit }} pts
                </option>
                @endforeach
            </select>
        </div>


        <div id="qty_container" class="space-y-2 mt-2">
            <!-- JS nanti akan generate input qty per product -->
        </div>


    </form>
</x-modal>