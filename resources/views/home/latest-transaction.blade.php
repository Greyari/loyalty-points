<!-- Latest Transactions Card -->
<div class="transform transition-transform duration-300 hover:-translate-y-1  bg-white border border-gray-200 rounded-lg shadow-sm p-6 flex flex-col" style="height: 461px;">
    <div class="flex items-center justify-between mb-4">
        <h5 class="text-xl font-semibold leading-none text-gray-800 font-poppins">Recent Transactions</h5>
        <a href="/orders" class="text-xs text-blue-600 hover:underline font-poppins">See all</a>
    </div>
    <div class="flex-1 overflow-y-auto">
        @if($recentTransactions && $recentTransactions->count() > 0)
        <ul role="list" class="divide-y divide-gray-200">
            @foreach($recentTransactions as $transaction)
            @php
            // Determine status
            $status = 'completed';

            if (isset($transaction['status'])) {
            $status = $transaction['status'];
            } elseif (($transaction['total_points'] ?? 0) < 0) {
                $status='pending' ;
                }

                $statusBgClass=match($status) { 'completed'=> 'bg-green-100',
                'pending' => 'bg-yellow-100',
                'failed' => 'bg-red-100',
                default => 'bg-green-100'
                };

                $statusIconColor = match($status) {
                'completed' => 'text-green-600',
                'pending' => 'text-yellow-600',
                'failed' => 'text-red-600',
                default => 'text-green-600'
                };

                // Time ago
                $timeAgo = $transaction['created_at']->diffForHumans();
                @endphp

                <li class="py-2">
                    <div class="flex items-start gap-3">

                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-800 truncate font-poppins">
                                {{ $transaction['order_id'] }}
                            </p>
                            <p class="text-xs text-gray-500 truncate font-poppins">
                                {{ $transaction['customer']->name ?? 'Unknown Customer' }}
                            </p>

                            <p class="text-xs text-gray-400 font-poppins mt-1">
                                <span class="font-medium {{ ($transaction['total_points'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ($transaction['total_points'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($transaction['total_points'] ?? 0) }} pts
                                </span>
                                • {{ number_format($transaction['qty'] ?? 0) }} qty
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="text-xs text-gray-400 font-poppins">{{ $timeAgo }}</p>
                        </div>
                    </div>
                </li>
                @endforeach
        </ul>

        @else
        <div class="flex flex-col items-center justify-center h-full text-center py-8">
            <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-sm font-medium text-gray-500 font-poppins">No transactions yet</p>
            <p class="text-xs text-gray-400 font-poppins mt-1">Transactions will appear here</p>
        </div>
        @endif
    </div>
</div>