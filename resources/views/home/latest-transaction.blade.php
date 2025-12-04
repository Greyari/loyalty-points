<!-- Latest Transactions Card -->
<div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 flex flex-col" style="height: 392px;">
    <div class="flex items-center justify-between mb-4">
        <h5 class="text-xl font-semibold leading-none text-gray-800 font-poppins">Recent Transaction</h5>
        <a href="/transaction" class="text-xs text-blue-600 hover:underline font-poppins">See all</a>
    </div>
    <div class="flex-1 overflow-y-auto">
        <ul role="list" class="divide-y divide-gray-200">
            @php
            $transactions = [
            ['id'=>'#1','customer'=>'PT Rafka Lestari mengirim bayi order.','status'=>'completed','date'=>'20 min'],
            ['id'=>'#2','customer'=>'Adi Wahab di Marketo','status'=>'completed','date'=>'2 jam'],
            ['id'=>'#3','customer'=>'Rayi Saputra Jaya (Toko)','status'=>'pending','date'=>'3 jam'],
            ['id'=>'#4','customer'=>'Selamat Nurhalimah pembuatan...','status'=>'completed','date'=>'4 jam'],
            ['id'=>'#5','customer'=>'Adi Wahab di Marketo','status'=>'failed','date'=>'5 jam'],
            ];
            @endphp

            @foreach($transactions as $transaction)
            @php
            $statusBgClass = match($transaction['status']) {
            'completed' => 'bg-green-100',
            'pending' => 'bg-yellow-100',
            default => 'bg-red-100'
            };

            $statusIconColor = match($transaction['status']) {
            'completed' => 'text-green-600',
            'pending' => 'text-yellow-600',
            default => 'text-red-600'
            };
            @endphp

            <li class="py-2">
                <div class="flex items-start gap-3">
                    <div class="shrink-0">
                        <div class="w-10 h-10 rounded-lg {{ $statusBgClass }} flex items-center justify-center">
                            @if($transaction['status'] == 'completed')
                            <svg class="w-5 h-5 {{ $statusIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            @elseif($transaction['status'] == 'pending')
                            <svg class="w-5 h-5 {{ $statusIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @else
                            <svg class="w-5 h-5 {{ $statusIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-800 truncate font-poppins">{{$transaction['id']}}</p>
                        <p class="text-xs text-gray-500 truncate font-poppins">{{$transaction['customer']}}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400 font-poppins">{{$transaction['date']}}</p>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>