@extends('layouts.nav')

@section('title', 'Dashboard Home')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold font-poppins mb-0">Dashboard Analytics</h2>
            <p class="text-sm sm:text-base lg:text-lg font-light text-gray-500 font-poppins">Sales & Revenue Insights</p>
        </div>


    </div>

    @if(session('success'))
    <x-toast type="success" :message="session('success')" />
    @endif

    <!-- Container grid untuk layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Kolom Kiri: Chart + Top Product -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            @include('home.chart')
            @include('home.top-product')
        </div>

        <!-- Kolom Kanan: Total Penjualan + Latest Customers + Latest Transactions -->
        <div class="lg:col-span-1 space-y-4 sm:space-y-6">
            @include('home.total-penjualan')
            @include('home.top-customer')
            @include('home.latest-transaction')
        </div>
    </div>
</div>
@endsection