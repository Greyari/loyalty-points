@extends('layouts.nav')

@section('title', 'Inventory')
@section('page_title', 'Inventory')

@section('content')
<div class="space-y-6 mb-6">
    <h2 class="text-4xl font-semibold font-poppins mb-0">Inventory</h2>
    <p class="text-lg font-light text-gray-500 font-poppins">Manage your inventory.</p>
</div>

<x-data-tables
    :headers="['Customer Name', 'Email', 'Phone', 'Company', 'Status']"
    :rows="[
        [
            'id' => 1,
            'customer_name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+62 812-3456-7890',
            'company' => 'Tech Solutions Inc',
            'status' => 'Active'
        ],
        [
            'id' => 2,
            'customer_name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '+62 813-4567-8901',
            'company' => 'Digital Marketing Co',
            'status' => 'Active'
        ],
        [
            'id' => 3,
            'customer_name' => 'Michael Johnson',
            'email' => 'michael.j@example.com',
            'phone' => '+62 814-5678-9012',
            'company' => 'Global Enterprises',
            'status' => 'Inactive'
        ],
        [
            'id' => 4,
            'customer_name' => 'Sarah Williams',
            'email' => 'sarah.w@example.com',
            'phone' => '+62 815-6789-0123',
            'company' => 'Innovation Labs',
            'status' => 'Active'
        ],
        [
            'id' => 5,
            'customer_name' => 'David Brown',
            'email' => 'david.brown@example.com',
            'phone' => '+62 816-7890-1234',
            'company' => 'Smart Systems Ltd',
            'status' => 'Active'
        ],
        [
            'id' => 6,
            'customer_name' => 'Emily Davis',
            'email' => 'emily.davis@example.com',
            'phone' => '+62 817-8901-2345',
            'company' => 'Creative Agency',
            'status' => 'Pending'
        ],
        [
            'id' => 7,
            'customer_name' => 'Robert Miller',
            'email' => 'robert.m@example.com',
            'phone' => '+62 818-9012-3456',
            'company' => 'Finance Group',
            'status' => 'Active'
        ],
        [
            'id' => 8,
            'customer_name' => 'Lisa Anderson',
            'email' => 'lisa.anderson@example.com',
            'phone' => '+62 819-0123-4567',
            'company' => 'Healthcare Solutions',
            'status' => 'Active'
        ],
        [
            'id' => 9,
            'customer_name' => 'James Wilson',
            'email' => 'james.wilson@example.com',
            'phone' => '+62 820-1234-5678',
            'company' => 'Construction Corp',
            'status' => 'Inactive'
        ],
        [
            'id' => 10,
            'customer_name' => 'Jennifer Moore',
            'email' => 'jennifer.m@example.com',
            'phone' => '+62 821-2345-6789',
            'company' => 'Retail Chain',
            'status' => 'Active'
        ],
        [
            'id' => 11,
            'customer_name' => 'William Taylor',
            'email' => 'william.taylor@example.com',
            'phone' => '+62 822-3456-7890',
            'company' => 'Logistics Express',
            'status' => 'Active'
        ],
        [
            'id' => 12,
            'customer_name' => 'Patricia Thomas',
            'email' => 'patricia.t@example.com',
            'phone' => '+62 823-4567-8901',
            'company' => 'Education Institute',
            'status' => 'Pending'
        ]
    ]" />


@endsection