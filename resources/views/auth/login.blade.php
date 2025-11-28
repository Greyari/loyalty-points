@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex min-h-screen">
    <!-- Left side: Image -->
    <div class="hidden md:flex md:w-1/2  items-left align-left relative">
        <div class="relative z-10 text-left px-15 py-10 flex flex-col h-full justify-between">
            <div>
                <h2 class="text-4xl font-bold text-black">Customer</h2>
                <h2 class="text-4xl font-bold text-black mb-4">Loyalty</h2>
            </div>

            <div class="self-center mx-20" style="height: 30rem;"> <!-- misal 560px -->
                <img src="{{ asset('assets/team-login-image.svg') }}" alt="Team Illustration" class="h-full w-auto">
            </div>


            <!-- Bottom text -->
            <h3 class="text-1xl font-normal text-gray-600 mt-8">PT. Kreatif System Indonesia</h3>
        </div>


    </div>

    <!-- Center line -->
    <div class="hidden md:flex md:w-0.5 bg-gray-300 mx-4 my-20 rounded-full"></div>

    <!-- Right side: Form -->
    <div class="flex w-full md:w-1/2 items-center justify-center p-8">
        <div class="w-full max-w-md">
            <h1 class="text-3xl font-bold mb-15 text-center text-gray-800">Login</h1>

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label class="block mb-1 font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400"
                        required>
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-12">
                    <label class="block mb-1 font-medium text-gray-700">Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Enter your password..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400"
                        required>
                    @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-[#2A2A2A] text-white py-3 rounded-lg hover:bg-[#1A1A1A] transition">
                    Login
                </button>

                <!-- Forgot password -->
                <p class="text-center text-sm text-gray-500 mt-4">
                    <a href="#" class="text-[#2A2A2A] hover:underline">Forgot password?</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
