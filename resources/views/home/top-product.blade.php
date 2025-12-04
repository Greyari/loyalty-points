<!-- Top Product Sales Table -->
<div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 flex flex-col" style="height: 450px;">
    <div class="flex items-center justify-between mb-6">
        <h5 class="text-xl font-semibold leading-none text-gray-800 font-poppins">Top Product Sales</h5>
        <a href="/inventory" class="font-medium text-blue-600 hover:underline">View all</a>
    </div>

    <div class="flex-1 overflow-y-auto">
        @if($topProducts->isEmpty())
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center h-full text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <p class="text-gray-500 font-medium mb-2">Belum Ada Data Penjualan</p>
                <p class="text-sm text-gray-400">Mulai tambahkan transaksi untuk melihat produk terlaris</p>
            </div>
        @else
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs text-gray-700 uppercase sticky top-0 bg-white">
                    <tr>
                        <th class="px-6 py-3">Rank</th>
                        <th class="px-6 py-3">SKU</th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3 text-center">Unit</th>
                        <th class="px-6 py-3 text-right">Points</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $i => $product)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @php $rank = $i + 1; @endphp

                                @if($rank == 1)
                                    <div class="w-7 h-7 rounded-full bg-yellow-400 text-white flex items-center justify-center font-bold">
                                        {{ $rank }}
                                    </div>
                                @elseif($rank == 2)
                                    <div class="w-7 h-7 rounded-full bg-gray-400 text-white flex items-center justify-center font-bold">
                                        {{ $rank }}
                                    </div>
                                @elseif($rank == 3)
                                    <div class="w-7 h-7 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold">
                                        {{ $rank }}
                                    </div>
                                @else
                                    <div class="w-7 h-7 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center font-semibold">
                                        {{ $rank }}
                                    </div>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-600">{{ $product->product->sku }}</td>

                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $product->product->name }}
                        </td>

                        <td class="px-6 py-4 text-center font-semibold text-gray-800">
                            {{ number_format($product->qty, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 text-right font-semibold text-gray-900">
                            {{ number_format($product->qty * $product->product->points_per_unit, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
