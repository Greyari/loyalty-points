<div class="transform transition-transform duration-300 hover:-translate-y-1 bg-white rounded-xl shadow-sm p-6">
    <div class="mb-1">
        <h5 class="text-xl font-semibold text-gray-900 font-poppins">Top Customer</h5>
        <p class="text-xs text-gray-400 mt-0.5 font-poppins">Monthly top customer rank points</p>
    </div>

    @if($topCustomers->isEmpty())
    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center py-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <p class="text-gray-500 font-medium mb-2 font-poppins">No customer data available</p>
        <p class="text-sm text-gray-400 font-poppins">Start adding transactions to see the top customers</p>
    </div>
    @else
    <div class="mt-6 space-y-4">
        @foreach($topCustomers as $i => $customer)
        @php
        $rank = $i + 1;
        $customerName = optional($customer->customer)->name ?? 'Data user ini dihapus';
        @endphp

        <div class="flex items-center gap-3 group relative font-poppins">
            <!-- Rank Badge -->
            <div class="relative shrink-0">
                @if($rank == 1)
                <div class="w-12 h-12 rounded-full overflow-hidden">
                    <img src="{{ asset('assets/Gold.png') }}" class="w-full h-full object-cover" alt="Gold">
                </div>
                @elseif($rank == 2)
                <div class="w-12 h-12 rounded-full overflow-hidden">
                    <img src="{{ asset('assets/Silver.png') }}" class="w-full h-full object-cover" alt="Silver">
                </div>
                @elseif($rank == 3)
                <div class="w-12 h-12 rounded-full overflow-hidden">
                    <img src="{{ asset('assets/Bronze.png') }}" class="w-full h-full object-cover" alt="Bronze">
                </div>
                @else
                <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center">
                    <span class="text-base font-semibold text-indigo-600">{{ $rank }}</span>
                </div>
                @endif
            </div>

            <!-- Customer Info (Clickable) -->
            <a href="{{ route('orders.index', ['search' => $customerName]) }}"
                class="flex-1 min-w-0 transition-all duration-200 hover:bg-gray-50 -m-2 p-2 rounded-lg cursor-pointer"
                data-customer-name="{{ $customerName }}">
                <div class="flex items-center gap-2 mb-0.5">
                    <p class="text-xs font-semibold text-gray-800 truncate group-hover:text-blue-600 transition-colors">
                        {{ $customerName }}
                    </p>

                    <!-- Tooltip Icon -->
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors opacity-0 group-hover:opacity-100"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>

                        <!-- Tooltip -->
                        <div class="absolute left-full ml-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-gray-900 text-white text-xs rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity duration-200 z-10 shadow-lg">
                            See Details
                            <div class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-gray-900"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                    <span>{{ number_format($customer->points, 0, ',', '.') }} poin</span>
                </div>
            </a>

            <!-- Points -->
            <div class="text-right shrink-0">
                <p class="text-base font-bold text-blue-600">
                    {{ number_format($customer->points, 0, ',', '.') }}
                </p>
                <p class="text-[10px] text-gray-400">poin</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>