@extends('layouts.nav')

@section('title', 'Dashboard Home')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <h2 class="text-4xl font-semibold font-poppins mb-0">Dashboard Analytics</h2>
    <p class="text-lg font-light text-gray-500 font-poppins">November, 01 - 30</p>

    @if(session('success'))
    <x-toast type="success" :message="session('success')" />
    @endif

    <!-- Container grid untuk layout -->
    <div class="grid grid-cols-3 gap-6">
        <!-- Kolom Kiri: Chart + Top Product -->
        <div class="col-span-2 space-y-6">
            @include('home.chart')
            @include('home.top-product')
        </div>

        <!-- Kolom Kanan: Total Penjualan + Latest Customers + Latest Transactions -->
        <div class="col-span-1 space-y-6">
            @include('home.total-penjualan')
            @include('home.top-customer')
            @include('home.latest-transaction')
        </div>
    </div>
</div>
@endsection