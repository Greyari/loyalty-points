@extends('layouts.nav')

@section('title', 'Customer Page')
@section('page_title', 'Customer')

@section('content')
<div class="space-y-6">

    <h2 class="text-2xl font-semibold">Welcome,</h2>
    <p>Your stats here...</p>

    {{-- Flowbite test --}}
    <button data-modal-target="testModal" data-modal-toggle="testModal"
        class="px-4 py-2 bg-blue-600 text-white rounded-lg">
        Open Flowbite Modal
    </button>

    <!-- Modal -->
    <div id="testModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 justify-center items-center w-full bg-black/40">
        <div class="relative p-4 w-full max-w-md">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Flowbite Connected</h2>
                <p class="mb-4">Kalau modal ini muncul, berarti Flowbite berhasil.</p>
                <button data-modal-hide="testModal"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection