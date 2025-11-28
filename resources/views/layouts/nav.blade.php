<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Nav')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex h-screen bg-[#F7F6FB] overflow-hidden">

    <!-- Sidebar -->
    <aside id="sidebar" class="bg-[#F7F6FB] border-r border-[#C8C8C8] transition-all duration-300 flex-shrink-0 w-56 flex flex-col fixed lg:relative h-full z-50 -translate-x-full lg:translate-x-0">
        <!-- Logo section -->
        <div class="h-16 flex items-center px-4 flex-shrink-0">
            <button id="toggleSidebar" class="p-2 hover:bg-[#E8E7ED] rounded-lg transition mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <span id="logoText" class="text-xl font-bold text-gray-800 font-poppins">Loyalty</span>
        </div>

        <!-- Menu links -->
        <nav class="py-4 px-2 flex-1 overflow-y-auto">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}" class="nav-item flex items-center px-3 py-3 hover:bg-[#E8E7ED] hover:text-gray-800 transition rounded-lg group {{ request()->routeIs('dashboard') ? 'bg-[#E8E7ED] text-gray-800' : 'text-gray-400' }}">
                        <svg class="w-5 h-5 flex-shrink-0 nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="ml-3 nav-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer') }}" class="nav-item flex items-center px-3 py-3 hover:bg-[#E8E7ED] hover:text-gray-800 transition rounded-lg group {{ request()->routeIs('customer') ? 'bg-[#E8E7ED] text-gray-800' : 'text-gray-400' }}">
                        <svg class="w-5 h-5 flex-shrink-0 nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="ml-3 nav-text">Customer</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transaction') }}" class="nav-item flex items-center px-3 py-3 hover:bg-[#E8E7ED] hover:text-gray-800 transition rounded-lg group {{ request()->routeIs('transaction') ? 'bg-[#E8E7ED] text-gray-800' : 'text-gray-400' }}">
                        <svg class="w-5 h-5 flex-shrink-0 nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span class="ml-3 nav-text">Transaction</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('inventory') }}" class="nav-item flex items-center px-3 py-3 hover:bg-[#E8E7ED] hover:text-gray-800 transition rounded-lg group {{ request()->routeIs('inventory') ? 'bg-[#E8E7ED] text-gray-800' : 'text-gray-400' }}">
                        <svg class="w-5 h-5 flex-shrink-0 nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="ml-3 nav-text">Inventory</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings') }}" class="nav-item flex items-center px-3 py-3 hover:bg-[#E8E7ED] hover:text-gray-800 transition rounded-lg group {{ request()->routeIs('settings') ? 'bg-[#E8E7ED] text-gray-800' : 'text-gray-400' }}">
                        <svg class="w-5 h-5 flex-shrink-0 nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="ml-3 nav-text">Settings</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Logout button -->
        <div class="py-4 px-2 border-t border-[#C8C8C8]">
            <form action="">
                @csrf
                <button type="submit" class="nav-item flex items-center w-full px-3 py-3 hover:bg-[#E8E7ED] hover:text-gray-800 transition rounded-lg group text-gray-400">
                    <svg class="w-5 h-5 flex-shrink-0 nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="ml-3 nav-text">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

    <!-- Main content -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Navbar -->
        <header class=" shadow-none p-4 flex justify-between items-center flex-shrink-0">
            <div class="flex items-center gap-3">
                <button id="mobileMenuBtn" class="lg:hidden p-2 hover:bg-[#E8E7ED] rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </header>

        <!-- Content -->
        <main class="pt-2 px-7 pb-7 flex-1 overflow-auto bg-[#F7F6FB]">
            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const overlay = document.getElementById('overlay');
        const logoText = document.getElementById('logoText');
        const navTexts = document.querySelectorAll('.nav-text');
        const navItems = document.querySelectorAll('.nav-item');
        const navIcons = document.querySelectorAll('.nav-icon');

        // Desktop toggle functionality
        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth >= 1024) {
                const isCollapsed = sidebar.classList.contains('w-16');

                if (isCollapsed) {
                    sidebar.classList.remove('w-16');
                    sidebar.classList.add('w-56');
                    logoText.classList.remove('hidden');
                    navTexts.forEach(text => text.classList.remove('hidden'));
                    navItems.forEach(item => item.classList.remove('justify-center'));
                    navIcons.forEach(icon => icon.classList.remove('mx-auto'));
                } else {
                    sidebar.classList.remove('w-56');
                    sidebar.classList.add('w-16');
                    logoText.classList.add('hidden');
                    navTexts.forEach(text => text.classList.add('hidden'));
                    navItems.forEach(item => item.classList.add('justify-center'));
                    navIcons.forEach(icon => icon.classList.add('mx-auto'));
                }
            }
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            const isOpen = sidebar.classList.contains('translate-x-0');

            if (isOpen) {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
            }
        }

        mobileMenuBtn.addEventListener('click', toggleMobileMenu);
        overlay.addEventListener('click', toggleMobileMenu);

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                overlay.classList.add('hidden');
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
            } else {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>

</body>

</html>