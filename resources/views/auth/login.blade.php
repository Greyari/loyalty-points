@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex flex-col md:flex-row h-screen overflow-hidden">
    <!-- Left side: Image & text -->
    <div class="hidden md:flex md:w-1/2 flex-col justify-between p-4 lg:p-8 overflow-hidden">
        <div class="shrink-0">
            <h2 class="text-2xl lg:text-4xl font-bold text-black leading-snug font-poppins  transform transition-transform duration-300 hover:-translate-y-1 ">Customer</h2>
            <h2 class="text-2xl lg:text-4xl font-bold text-black leading-snug mb-2 lg:mb-4 font-poppins  transform transition-transform duration-300 hover:-translate-y-1 ">Loyalty</h2>
        </div>

        <div class=" flex justify-center items-center flex-1 min-h-0 py-7">
            <img src="{{ asset('assets/team-login-image.svg') }}" alt="Team Illustration" class=" transform transition-transform duration-300 hover:-translate-y-1  max-h-full max-w-full w-auto h-auto object-contain">
        </div>

        <h3 class="text-sm lg:text-base font-normal text-gray-600 shrink-0 font-poppins  transform transition-transform duration-300 hover:-translate-y-1 ">PT. Kreatif System Indonesia</h3>
    </div>

    <!-- Divider -->
    <div class=" hidden md:block md:w-px bg-gray-300 my-4 lg:my-8 mx-2 lg:mx-4 rounded-full shrink-0"></div>

    <!-- Right side: Form -->
    <div class="flex w-full md:w-1/2 items-center justify-center p-4 sm:p-6 lg:p-8 overflow-y-auto">
        <div class="w-full max-w-md">
            <h1 class="text-2xl lg:text-3xl font-bold mb-6 lg:mb-8 text-center text-gray-800 font-poppins transform transition-transform duration-300 hover:-translate-y-1  ">Login</h1>
            @if(session('error'))
            <x-toast type="error" :message="session('error')" />
            @endif


            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <!-- Email -->
                <div class=" transform transition-transform duration-300 hover:-translate-y-1  mb-3 lg:mb-4">
                    <label class="block mb-1 font-medium text-gray-700 text-sm lg:text-base font-poppins">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email..."
                        class="placeholder:text-gray-400 w-full border bg-transparent border-gray-300 rounded-lg px-3 lg:px-4 py-2 lg:py-3 text-sm lg:text-base text-gray-700 focus:outline-none focus:border-gray-400"
                        required>
                    @error('email')
                    <p class="text-red-500 text-xs lg:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class=" transform transition-transform duration-300 hover:-translate-y-1  mb-4 lg:mb-6 relative">
                    <label class="block mb-1 font-medium text-gray-700 text-sm lg:text-base font-poppins">Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Enter your password..."
                        id="password"
                        class="placeholder:text-gray-400 w-full border bg-transparent border-gray-300 rounded-lg px-3 lg:px-4 py-2 lg:py-3 pr-10 text-sm lg:text-base text-gray-700 focus:outline-none focus:border-gray-400"
                        required>
                    @error('password')
                    <p class="text-red-500 text-xs lg:text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Toggle Eye Icon -->
                    <button type="button" id="togglePassword"
                        class=" absolute right-3 top-9 lg:top-10 text-gray-400 hover:text-gray-600 cursor-pointer flex items-center justify-center transition-colors duration-200">

                        <!-- Eye Closed Icon (password hidden) -->
                        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 transition-opacity duration-200"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>

                        <!-- Eye Open Icon (password visible) -->
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 hidden transition-opacity duration-200"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class=" transform transition-transform duration-300 hover:-translate-y-1  w-full bg-[#2A2A2A] text-white py-2 lg:py-3 rounded-lg text-sm lg:text-base hover:bg-[#1A1A1A] font-poppins">
                    Login
                </button>


            </form>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    togglePassword.addEventListener('click', () => {
        const isPasswordVisible = password.getAttribute('type') === 'text';

        // Toggle password visibility
        password.setAttribute('type', isPasswordVisible ? 'password' : 'text');

        // Toggle icon visibility with smooth transition
        eyeOpen.classList.toggle('hidden');
        eyeClosed.classList.toggle('hidden');
    });
</script>

@endsection