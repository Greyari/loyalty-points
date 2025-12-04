<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="mb-1">
        <h5 class="text-lg font-semibold text-gray-900">Top Customer</h5>
        <p class="text-xs text-gray-400 mt-0.5">Monthly top customer rank points</p>
    </div>

    @if($topCustomers->isEmpty())
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <p class="text-gray-500 font-medium mb-2">Belum Ada Data Customer</p>
            <p class="text-sm text-gray-400">Mulai tambahkan transaksi untuk melihat customer terbaik</p>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach($topCustomers as $i => $customer)
            @php
                $rank = $i + 1;
            @endphp

            <div class="flex items-center gap-3">
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

                <!-- Customer Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-0.5">
                        <p class="text-sm font-semibold text-gray-900 truncate">
                            {{ $customer->customer->name }}
                        </p>
                    </div>

                    <div class="flex items-center gap-1.5 text-xs text-gray-500">
                        <span>{{ number_format($customer->points, 0, ',', '.') }} poin</span>
                    </div>
                </div>

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
