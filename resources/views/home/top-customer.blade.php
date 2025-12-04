<!-- Top Customer Card -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="mb-1">
        <h5 class="text-lg font-semibold text-gray-900 font-poppins">Top Customer</h5>
        <p class="text-xs text-gray-400 mt-0.5 font-poppins">Monthly top customer rank points</p>
    </div>

    <div class="mt-6 space-y-4">
        @php
        $customers = [
        [
        'name' => 'PT Maju Jaya Sejahtera',
        'points' => '125.480',
        'time' => '2 hari lalu',
        'rank' => 1,
        'badge' => 'GOLD'
        ],
        [
        'name' => 'CV Berkah Elektronik',
        'points' => '98.250',
        'time' => '1 minggu lalu',
        'rank' => 2,
        'badge' => 'SILVER'
        ],
        [
        'name' => 'Toko Sentosa Jaya',
        'points' => '76.320',
        'time' => '3 hari lalu',
        'rank' => 3,
        'badge' => 'BRONZE'
        ],
        [
        'name' => 'UD Mandiri Sukses',
        'points' => '54.890',
        'time' => '5 hari lalu',
        'rank' => 4,
        'badge' => null
        ],
        [
        'name' => 'PT Global Tech',
        'points' => '42.150',
        'time' => '1 minggu lalu',
        'rank' => 5,
        'badge' => null
        ],
        ];
        @endphp

        @foreach($customers as $customer)
        <div class="flex items-center gap-3">
            <!-- Rank Badge -->
            <div class="relative shrink-0">
                @if($customer['rank'] == 1)
                <div class="w-12 h-12 rounded-full bg-amber-400 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('assets/Gold.png') }}" alt="Gold" class="w-full h-full object-cover">
                </div>
                @elseif($customer['rank'] == 2)
                <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('assets/Silver.png') }}" alt="Silver" class="w-full h-full object-cover">
                </div>
                @elseif($customer['rank'] == 3)
                <div class="w-12 h-12 rounded-full bg-orange-400 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('assets/Bronze.png') }}" alt="Bronze" class="w-full h-full object-cover">
                </div>
                @else
                <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center border border-indigo-600">
                    <span class="text-base font-semibold text-indigo-600 ">{{ $customer['rank'] }}</span>
                </div>
                @endif
                @if($customer['rank'] <= 3)
                    @php
                    $badgeBgColor=match($customer['rank']) {
                    1=> 'bg-amber-400',
                    2 => 'bg-gray-400',
                    3 => 'bg-[#C07D42]',
                    default => 'bg-gray-400'
                    };
                    @endphp
                    <div class="absolute -top-1 -right-1 w-5 h-5 rounded-full flex items-center justify-center shadow-sm border-2 border-white {{ $badgeBgColor }}">
                        <span class="text-xs font-bold text-white">{{ $customer['rank'] }}</span>
                    </div>
                    @endif
            </div>

            <!-- Customer Info -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $customer['name'] }}</p>
                    @if($customer['badge'])
                    @php
                    $badgeClasses = match($customer['badge']) {
                    'GOLD' => 'bg-amber-100 text-amber-700',
                    'SILVER' => 'bg-gray-200 text-gray-700',
                    'BRONZE' => 'bg-orange-100 text-orange-700',
                    default => 'bg-gray-100 text-gray-700'
                    };
                    @endphp
                    <span class="px-2 py-0.5 text-[10px] font-bold rounded {{ $badgeClasses }}">
                        {{ $customer['badge'] }}
                    </span>
                    @endif
                </div>
                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award">
                        <path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526" />
                        <circle cx="12" cy="8" r="6" />
                    </svg>
                    <span>{{ number_format((float)str_replace('.', '', $customer['points']), 0, ',', '.') }} poin</span>
                    <span class="text-gray-400">•</span>
                    <span>{{ $customer['time'] }}</span>
                </div>
            </div>

            <!-- Points Display -->
            <div class="text-right shrink-0">
                <p class="text-base font-bold text-blue-600">{{ $customer['points'] }}</p>
                <p class="text-[10px] text-gray-400">poin</p>
            </div>
        </div>
        @endforeach
    </div>
</div>