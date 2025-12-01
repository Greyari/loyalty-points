@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex flex-col md:flex-row h-screen overflow-hidden">
    <!-- Left side: Image & text -->
    <div class="hidden md:flex md:w-1/2 flex-col justify-between p-4 lg:p-8 overflow-hidden">
        <div class="flex-shrink-0">
            <h2 class="text-2xl lg:text-4xl font-bold text-black leading-snug font-poppins">Customer</h2>
            <h2 class="text-2xl lg:text-4xl font-bold text-black leading-snug mb-2 lg:mb-4 font-poppins">Loyalty</h2>
        </div>

        <div class="flex justify-center items-center flex-1 min-h-0 py-7">
            <img src="{{ asset('assets/team-login-image.svg') }}" alt="Team Illustration" class="max-h-full max-w-full w-auto h-auto object-contain">
        </div>

        <h3 class="text-sm lg:text-base font-normal text-gray-600 flex-shrink-0 ">PT. Kreatif System Indonesia</h3>
    </div>

    <!-- Divider -->
    <div class="hidden md:block md:w-px bg-gray-300 my-4 lg:my-8 mx-2 lg:mx-4 rounded-full flex-shrink-0"></div>

    <!-- Right side: Form -->
    <div class="flex w-full md:w-1/2 items-center justify-center p-4 sm:p-6 lg:p-8 overflow-y-auto">
        <div class="w-full max-w-md">
            <h1 class="text-2xl lg:text-3xl font-bold mb-6 lg:mb-8 text-center text-gray-800 font-poppins">Login</h1>
            @if(session('error'))
                <div x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="bg-red-500 text-white px-4 py-2 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3 lg:mb-4">
                    <label class="block mb-1 font-medium text-gray-700 text-sm lg:text-base font-poppins">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email..."
                        class="w-full border border-gray-300 rounded-lg px-3 lg:px-4 py-2 lg:py-3 text-sm lg:text-base focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400"
                        required>
                    @error('email')
                    <p class="text-red-500 text-xs lg:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4 lg:mb-6 relative">
                    <label class="block mb-1 font-medium text-gray-700 text-sm lg:text-base font-poppins">Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Enter your password..."
                        id="password"
                        class="w-full border border-gray-300 rounded-lg px-3 lg:px-4 py-2 lg:py-3 text-sm lg:text-base focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-400"
                        required>
                    @error('password')
                    <p class="text-red-500 text-xs lg:text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <button type="button" id="togglePassword" class="absolute right-3 top-9 lg:top-10 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 lg:h-5 lg:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-[#2A2A2A] text-white py-2 lg:py-3 rounded-lg text-sm lg:text-base hover:bg-[#1A1A1A] transition font-poppins">
                    Login
                </button>

                <!-- Forgot password -->
                <p class="text-center text-xs lg:text-sm text-gray-500 mt-3 lg:mt-4">
                    <a href="#" class="text-[#2A2A2A] hover:underline">Forgot password?</a>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', () => {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
    });
</script>
@endsection
