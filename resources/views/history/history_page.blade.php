@extends('layouts.nav')

@section('title', 'History Product')
@section('page_title', 'History')

@section('content')
<div class="space-y-6 mb-6">
    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold font-poppins mb-0">Product History</h2>
    <p class="text-sm sm:text-base lg:text-lg font-light text-gray-500 font-poppins">Track all changes made to products.</p>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <!-- Filter Section -->
    <div class="p-4 sm:p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input
                    type="text"
                    placeholder="Search product or SKU..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex gap-2">
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Actions</option>
                    <option value="created">Created</option>
                    <option value="updated">Updated</option>
                    <option value="deleted">Deleted</option>
                </select>
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 sm:p-6 bg-gray-50">
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <p class="text-sm text-gray-500 font-poppins mb-1">Total Changes</p>
            <p class="text-2xl font-semibold font-poppins text-gray-900">247</p>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <p class="text-sm text-gray-500 font-poppins mb-1">This Month</p>
            <p class="text-2xl font-semibold font-poppins text-blue-600">38</p>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <p class="text-sm text-gray-500 font-poppins mb-1">Today</p>
            <p class="text-2xl font-semibold font-poppins text-green-600">5</p>
        </div>
    </div>

    <!-- History List -->
    <div class="divide-y divide-gray-200">
        <!-- Created Action -->
        <div class="p-4 sm:p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-green-100">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-medium font-poppins text-gray-900">CCTV Indoor 2MP Hikvision</h3>
                            <p class="text-sm text-gray-500 font-poppins">09 Dec 2025, 14:30</p>
                        </div>
                    </div>
                    <div class="ml-13">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Created
                        </span>
                        <p class="text-sm text-gray-600 font-poppins mt-2">
                            <span class="font-medium">SKU:</span> CCT-HKV-001 •
                            <span class="font-medium">Qty:</span> 150 •
                            <span class="font-medium">Price:</span> Rp 850.000 •
                            <span class="font-medium">Points:</span> 9
                        </p>
                        <p class="text-xs text-gray-400 font-poppins mt-1">By: Admin User</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Updated Action -->
        <div class="p-4 sm:p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-100">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-medium font-poppins text-gray-900">CCTV Outdoor 4MP Dahua</h3>
                            <p class="text-sm text-gray-500 font-poppins">09 Dec 2025, 11:15</p>
                        </div>
                    </div>
                    <div class="ml-13">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Updated
                        </span>
                        <div class="mt-2 space-y-1">
                            <div class="text-sm font-poppins">
                                <span class="font-medium text-gray-700">Quantity:</span>
                                <span class="text-red-600 line-through ml-1">150</span>
                                <span class="text-green-600 ml-1">→ 120</span>
                            </div>
                            <div class="text-sm font-poppins">
                                <span class="font-medium text-gray-700">Price:</span>
                                <span class="text-red-600 line-through ml-1">Rp 1.400.000</span>
                                <span class="text-green-600 ml-1">→ Rp 1.500.000</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 font-poppins mt-1">By: Admin User</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Updated Action (Point Unit) -->
        <div class="p-4 sm:p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-100">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-medium font-poppins text-gray-900">DVR 8 Channel Hikvision</h3>
                            <p class="text-sm text-gray-500 font-poppins">08 Dec 2025, 16:45</p>
                        </div>
                    </div>
                    <div class="ml-13">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Updated
                        </span>
                        <div class="mt-2 space-y-1">
                            <div class="text-sm font-poppins">
                                <span class="font-medium text-gray-700">Point Unit:</span>
                                <span class="text-red-600 line-through ml-1">30</span>
                                <span class="text-green-600 ml-1">→ 35</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 font-poppins mt-1">By: Manager</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Updated Action (Multiple Fields) -->
        <div class="p-4 sm:p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-100">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-medium font-poppins text-gray-900">NVR 16 Channel Dahua</h3>
                            <p class="text-sm text-gray-500 font-poppins">08 Dec 2025, 10:20</p>
                        </div>
                    </div>
                    <div class="ml-13">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Updated
                        </span>
                        <div class="mt-2 space-y-1">
                            <div class="text-sm font-poppins">
                                <span class="font-medium text-gray-700">SKU:</span>
                                <span class="text-red-600 line-through ml-1">NVR-DAH-003</span>
                                <span class="text-green-600 ml-1">→ NVR-DAH-004</span>
                            </div>
                            <div class="text-sm font-poppins">
                                <span class="font-medium text-gray-700">Quantity:</span>
                                <span class="text-red-600 line-through ml-1">75</span>
                                <span class="text-green-600 ml-1">→ 60</span>
                            </div>
                            <div class="text-sm font-poppins">
                                <span class="font-medium text-gray-700">Point Unit:</span>
                                <span class="text-red-600 line-through ml-1">50</span>
                                <span class="text-green-600 ml-1">→ 55</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 font-poppins mt-1">By: Admin User</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deleted Action -->
        <div class="p-4 sm:p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-red-100">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-medium font-poppins text-gray-900">CCTV PTZ 5MP (Discontinued)</h3>
                            <p class="text-sm text-gray-500 font-poppins">07 Dec 2025, 09:00</p>
                        </div>
                    </div>
                    <div class="ml-13">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Deleted
                        </span>
                        <p class="text-sm text-gray-600 font-poppins mt-2">
                            <span class="font-medium">SKU:</span> CCT-PTZ-005 •
                            <span class="font-medium">Last Qty:</span> 45 •
                            <span class="font-medium">Last Price:</span> Rp 4.500.000 •
                            <span class="font-medium">Points:</span> 45
                        </p>
                        <p class="text-xs text-gray-400 font-poppins mt-1">By: Manager</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Updated Action (Name Change) -->
        <div class="p-4 sm:p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-100">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-medium font-poppins text-gray-900">Kabel Coaxial RG59 100M</h3>
                            <p class="text-sm text-gray-500 font-poppins">06 Dec 2025, 15:30</p>
                        </div>
                    </div>
                    <div class="ml-13">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Updated
                        </span>
                        <div class="mt-2 space-y-1">
                            <div class="text-sm font-poppins">
                                <span class="font-medium text-gray-700">Product Name:</span>
                                <span class="text-red-600 line-through ml-1 block mt-1">Kabel Coaxial RG59</span>
                                <span class="text-green-600 block mt-1">→ Kabel Coaxial RG59 100M</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 font-poppins mt-1">By: Admin User</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Created Action -->
        <div class="p-4 sm:p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-green-100">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-medium font-poppins text-gray-900">Kabel UTP Cat6 305M</h3>
                            <p class="text-sm text-gray-500 font-poppins">05 Dec 2025, 13:00</p>
                        </div>
                    </div>
                    <div class="ml-13">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Created
                        </span>
                        <p class="text-sm text-gray-600 font-poppins mt-2">
                            <span class="font-medium">SKU:</span> KBL-UTP-007 •
                            <span class="font-medium">Qty:</span> 180 •
                            <span class="font-medium">Price:</span> Rp 1.200.000 •
                            <span class="font-medium">Points:</span> 12
                        </p>
                        <p class="text-xs text-gray-400 font-poppins mt-1">By: Admin User</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Updated Action (Stock Adjustment) -->
        <div class="p-4 sm:p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-100">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-medium font-poppins text-gray-900">Power Supply 12V 10A</h3>
                            <p class="text-sm text-gray-500 font-poppins">04 Dec 2025, 08:45</p>
                        </div>
                    </div>
                    <div class="ml-13">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Updated
                        </span>
                        <div class="mt-2 space-y-1">
                            <div class="text-sm font-poppins">
                                <span class="font-medium text-gray-700">Quantity:</span>
                                <span class="text-red-600 line-through ml-1">200</span>
                                <span class="text-green-600 ml-1">→ 250</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 font-poppins mt-1">By: Warehouse Staff</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="p-4 sm:p-6 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700 font-poppins">
                Showing <span class="font-medium">1</span> to <span class="font-medium">9</span> of <span class="font-medium">247</span> results
            </div>
            <div class="flex gap-2">
                <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm font-poppins hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Previous
                </button>
                <button class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm font-poppins">1</button>
                <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm font-poppins hover:bg-gray-50">2</button>
                <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm font-poppins hover:bg-gray-50">3</button>
                <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm font-poppins hover:bg-gray-50">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>
@endsection