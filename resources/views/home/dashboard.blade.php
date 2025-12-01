@extends('layouts.nav')

@section('title', 'Dashboard Home')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <h2 class="text-4xl font-semibold font-poppins mb-0">Dashboard Analytics</h2>
    <p class="text-l font-light text-gray-500 font-poppins">November, 01 - 30</p>
<div>
    @if(session('success'))
        <div x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"
            class="bg-green-500 text-white px-4 py-2 rounded">
            {{ session('success') }}
        </div>
    @endif
    <h1 class="text-3xl font-bold font-poppins">Dashboard Analytics</h1>
    <p class="text-xl text-gray-600">November, 01 - 30 </p>
</div>
@endsection
