{{-- @extends('layouts.nav')

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

@include('transaction.create-modal')
@include('transaction.edit-modal')



@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/point_transaction.js') }}"></script>
<script src="{{ asset('js/transaction/create.js') }}"></script>
@endpush

@push('styles')
<link href="{{ asset('css/transaction.css') }}" rel="stylesheet">
@endpush

@endsection --}}
