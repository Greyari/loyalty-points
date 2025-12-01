@extends('layouts.nav')

@section('title', 'Transaction')
@section('page_title', 'Transaction')

@section('content')
<div class="space-y-6 mb-6">
    <h2 class="text-4xl font-semibold font-poppins mb-0">Transaction</h2>
    <p class="text-lg font-light text-gray-500 font-poppins">Manage your transaction data.</p>
</div>

<x-data-tables
    :headers="[
        'Transaction ID',
        'Date',
        'Customer',
        'Total Items',
        'Total Amount',
        'Payment Method',
        'Status'
    ]"
    :rows="[
        [
            'id' => 1,
            'transaction_id' => 'TXN-1001',
            'date' => '2025-12-01 09:30',
            'customer' => 'John Doe',
            'total_items' => 3,
            'total_amount' => 'Rp 1.500.000',
            'payment_method' => 'Cash',
            'status' => 'Completed'
        ],
        [
            'id' => 2,
            'transaction_id' => 'TXN-1002',
            'date' => '2025-12-01 10:15',
            'customer' => 'Jane Smith',
            'total_items' => 2,
            'total_amount' => 'Rp 750.000',
            'payment_method' => 'Credit Card',
            'status' => 'Pending'
        ],
        [
            'id' => 3,
            'transaction_id' => 'TXN-1003',
            'date' => '2025-12-01 11:00',
            'customer' => 'Michael Johnson',
            'total_items' => 5,
            'total_amount' => 'Rp 3.200.000',
            'payment_method' => 'Transfer',
            'status' => 'Completed'
        ],
        [
            'id' => 4,
            'transaction_id' => 'TXN-1004',
            'date' => '2025-12-01 11:45',
            'customer' => 'Sarah Williams',
            'total_items' => 1,
            'total_amount' => 'Rp 500.000',
            'payment_method' => 'Cash',
            'status' => 'Cancelled'
        ]
    ]"
    onAdd="true"
    onEdit="true"
    onDelete="true" />

<!-- Create Modal -->
<x-modal id="createModal" title="Add New Transaction" size="lg" submitText="Save Transaction">
    <form id="createForm" class="space-y-4">

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Transaction ID</label>
                <input type="text"
                    name="transaction_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="TXN-1005"
                    required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Date</label>
                <input type="datetime-local"
                    name="date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Customer Name</label>
            <input type="text"
                name="customer"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 "
                placeholder="Enter customer name"
                required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Total Items</label>
                <input type="number"
                    name="total_items"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 "
                    placeholder="0"
                    min="1"
                    required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Total Amount</label>
                <input type="text"
                    name="total_amount"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Rp 0"
                    required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Payment Method</label>
                <select name="payment_method"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 "
                    required>
                    <option value="">Select payment method</option>
                    <option value="Cash">Cash</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="Transfer">Transfer</option>
                    <option value="E-Wallet">E-Wallet</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Status</label>
                <select name="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="">Select status</option>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Notes (Optional)</label>
            <textarea name="notes"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 "
                placeholder="Additional notes..."></textarea>
        </div>

    </form>
</x-modal>

<!-- Edit Modal -->
<x-modal id="editModal" title="Edit Transaction" size="lg" submitText="Update Transaction" submitButtonClass="bg-green-600 hover:bg-green-700 text-white">
    <form id="editForm" class="space-y-4">
        <input type="hidden" name="id" id="edit_id">

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Transaction ID</label>
                <input type="text"
                    name="transaction_id"
                    id="edit_transaction_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent  bg-gray-100"
                    readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Date</label>
                <input type="datetime-local"
                    name="date"
                    id="edit_date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 "
                    required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Customer Name</label>
            <input type="text"
                name="customer"
                id="edit_customer"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 "
                placeholder="Enter customer name"
                required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Total Items</label>
                <input type="number"
                    name="total_items"
                    id="edit_total_items"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 "
                    placeholder="0"
                    min="1"
                    required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Total Amount</label>
                <input type="text"
                    name="total_amount"
                    id="edit_total_amount"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 "
                    placeholder="Rp 0"
                    required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Payment Method</label>
                <select name="payment_method"
                    id="edit_payment_method"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 "
                    required>
                    <option value="">Select payment method</option>
                    <option value="Cash">Cash</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="Transfer">Transfer</option>
                    <option value="E-Wallet">E-Wallet</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Status</label>
                <select name="status"
                    id="edit_status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 "
                    required>
                    <option value="">Select status</option>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Notes (Optional)</label>
            <textarea name="notes"
                id="edit_notes"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 "
                placeholder="Additional notes..."></textarea>
        </div>

    </form>
</x-modal>

<!-- Delete Confirmation Modal -->
<x-modal id="deleteModal" title="Confirm Delete" size="sm" submitText="Delete" submitButtonClass="bg-red-600 hover:bg-red-700 text-white">
    <div class="text-center py-4">
        <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <p class="text-gray-700 font-poppins mb-2">Are you sure you want to delete this transaction?</p>
        <p class="text-sm text-gray-600 font-poppins font-semibold" id="delete_transaction_id"></p>
        <p class="text-sm text-gray-500 mt-3 font-poppins">This action cannot be undone.</p>
    </div>
    <input type="hidden" id="delete_id">
</x-modal>


@endsection