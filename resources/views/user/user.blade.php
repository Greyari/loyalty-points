@extends('layouts.nav')

@section('title', 'User Page')
@section('page_title', 'Users')

@section('content')
<div class="space-y-6 mb-6">
    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold font-poppins mb-0">User Data</h2>
    <p class="text-sm sm:text-base lg:text-lg font-light text-gray-500 font-poppins">Manage system user accounts.</p>
</div>

<x-data-tables
    :headers="['Name', 'Email', 'Role']"
    :rows="$users"
    onAdd="true"
    onEdit="true"
    onDelete="true" />

<!-- Create Modal -->
<x-modal id="userCreateModal" title="Add New User" size="lg" submitText="Save">
    <form id="createForm" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Full Name</label>
            <input type="text" name="name"
                class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                placeholder="Enter full name" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Email</label>
            <input type="email" name="email"
                class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                placeholder="Enter email" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Role</label>
            <select name="role"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                required>
                <option>Admin Super</option>
                <option>Admin Office</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Password</label>
            <input type="password" name="password"
                class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                placeholder="Enter password" required>
        </div>
    </form>
</x-modal>

<!-- Edit Modal -->
<x-modal id="userEditModal" title="Edit User" size="lg" submitText="Update" submitButtonClass="bg-green-600 hover:bg-green-700 text-white">
    <form id="editForm" class="space-y-4">
        <input type="hidden" name="id" id="edit_id">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Full Name</label>
            <input type="text" name="name" id="edit_name"
                class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                placeholder="Enter full name" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Email</label>
            <input type="email" name="email" id="edit_email"
                class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                placeholder="Enter email" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Role</label>
            <select name="role" id="edit_role"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                required>
                <option>Admin Super</option>
                <option>Admin Office</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Password</label>
            <input type="password" name="password" id="edit_password"
                class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                placeholder="Enter new password (leave blank to keep current password)">
        </div>
    </form>
</x-modal>

<!-- Delete Confirmation Modal -->
<x-modal id="userDeleteModal" title="Confirm Delete" size="sm" submitText="Delete" submitButtonClass="bg-red-600 hover:bg-red-700 text-white">
    <div class="text-center py-4">
        <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <p class="text-gray-700 font-poppins mb-2">Are you sure you want to delete this user?</p>
        <p class="text-sm text-gray-600 font-poppins font-semibold" id="delete_name"></p>
        <p class="text-sm text-gray-500 mt-3 font-poppins">This action cannot be undone.</p>
    </div>
    <input type="hidden" id="delete_id">
</x-modal>

@push('scripts')
<script>
    // Dummy actions
    console.log("Dummy CRUD loaded");
</script>
@endpush

@endsection
