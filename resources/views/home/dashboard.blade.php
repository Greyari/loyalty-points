@extends('layouts.nav')

@section('title', 'Dashboard Home')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <h2 class="text-4xl font-semibold font-poppins mb-0">Dashboard Analytics</h2>
    <p class="text-lg font-light text-gray-500 font-poppins">November, 01 - 30</p>

    @if(session('success'))
    <x-toast type="success" :message="session('success')" />
    @endif

    <!-- Container grid untuk layout -->
    <div class="grid grid-cols-3 gap-6">
        <!-- Kolom Kiri: Chart + Top Product -->
        <div class="col-span-2 space-y-6">
            <!-- Chart Card -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h5 class="text-2xl font-bold text-gray-900 font-poppins">Monthly Sales</h5>
                        <p class="text-gray-500 font-poppins">Sales chart data</p>
                    </div>
                    <div class="flex items-center px-3 py-1 bg-green-100 text-green-700 font-medium rounded-full text-sm">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v13m0-13 4 4m-4-4-4 4" />
                        </svg>
                        12%
                    </div>
                </div>

                <div id="main-chart" class="py-4"></div>

                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <button id="dropdownLastDays14Button" data-dropdown-toggle="LastDays14dropdown" data-dropdown-placement="bottom"
                        class="text-sm font-medium text-gray-700 hover:text-gray-900 inline-flex items-center px-2 py-1 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Last 7 days
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7" />
                        </svg>
                    </button>

                    <a href="#"
                        class="text-sm font-medium text-gray-700 hover:text-gray-900 inline-flex items-center px-3 py-1 border border-gray-200 rounded-md bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        Progress report
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m14 0-4 4m4-4-4-4" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Top Product Sales Table -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 flex flex-col" style="height: 450px;">
                <div class="flex items-center justify-between mb-6">
                    <h5 class="text-xl font-semibold leading-none text-gray-800 font-poppins">Top Product Sales</h5>
                    <a href="/inventory" class="font-medium text-blue-600 hover:underline">View all</a>
                </div>

                <div class="flex-1 overflow-y-auto">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs text-gray-700 uppercase  sticky top-0">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-poppins">Rank</th>
                                <th scope="col" class="px-6 py-3 font-poppins">SKU</th>
                                <th scope="col" class="px-6 py-3 font-poppins">Name</th>
                                <th scope="col" class="px-6 py-3 font-poppins text-center">Unit</th>
                                <th scope="col" class="px-6 py-3 font-poppins text-right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $products = [
                            ['rank'=>1,'sku'=>'CCTV-HD-001','name'=>'HD Camera 1080p','unit'=>145,'revenue'=>'Rp. 72.5 Jt'],
                            ['rank'=>2,'sku'=>'CCTV-HD-002','name'=>'HD Camera 4K','unit'=>98,'revenue'=>'Rp. 58.8 Jt'],
                            ['rank'=>3,'sku'=>'DVR-16CH-01','name'=>'DVR 16 Channel','unit'=>67,'revenue'=>'Rp. 40.2 Jt'],
                            ['rank'=>4,'sku'=>'NVR-8CH-01','name'=>'NVR 8 Channel','unit'=>58,'revenue'=>'Rp. 34.8 Jt'],
                            ['rank'=>5,'sku'=>'CAB-100M-01','name'=>'Cable 100m','unit'=>203,'revenue'=>'Rp. 20.3 Jt'],
                            ];
                            @endphp

                            @foreach($products as $product)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($product['rank'] == 1)
                                        <div class="w-7 h-7 rounded-full bg-yellow-400 flex items-center justify-center text-white font-semibold text-xs">
                                            {{$product['rank']}}
                                        </div>
                                        @elseif($product['rank'] == 2)
                                        <div class="w-7 h-7 rounded-full bg-gray-400 flex items-center justify-center text-white font-semibold text-xs">
                                            {{$product['rank']}}
                                        </div>
                                        @elseif($product['rank'] == 3)
                                        <div class="w-7 h-7 rounded-full bg-orange-500 flex items-center justify-center text-white font-semibold text-xs">
                                            {{$product['rank']}}
                                        </div>
                                        @else
                                        <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-semibold text-xs">
                                            {{$product['rank']}}
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-poppins text-gray-600">{{$product['sku']}}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 font-poppins">
                                    {{$product['name']}}
                                </td>
                                <td class="px-6 py-4 text-center font-poppins">
                                    <span class="font-semibold text-gray-800">{{$product['unit']}}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900 font-poppins">
                                    {{$product['revenue']}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Total Penjualan + Latest Customers + Latest Transactions -->
        <div class="col-span-1 space-y-6">
            <!-- Total Penjualan Card -->
            <div class="bg-white rounded-lg shadow-md p-4 flex justify-between items-center h-[113px]">
                <div class="flex flex-col justify-between h-full">
                    <div>
                        <p class="font-bold text-gray-900 text-base font-poppins">Total Sales</p>
                        <p class="text-gray-500 text-sm font-poppins">Total amount this month</p>
                    </div>
                    <p class="font-bold text-gray-900 text-xl mt-2 font-poppins">Rp. 107.890.000</p>
                </div>
                <div class="flex items-center justify-center w-16 h-16 rounded-full bg-green-100">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10l4-4 8 8 4-4" />
                    </svg>
                </div>
            </div>

            <!-- Latest Customers Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 flex flex-col" style="height: 365px;">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-xl font-semibold leading-none text-gray-800 font-poppins">Top Customer</h5>
                    <a href="/customer" class="text-xs text-blue-600 hover:underline font-poppins">See all</a>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <ul role="list" class="divide-y divide-gray-200">
                        @php
                        $customers = [
                        ['name'=>'PT Matahari Sakti','email'=>'Rp 22.000.000 - 78 transaksi','points'=>'95.980','rank'=>1],
                        ['name'=>'PT Tania Nayomi','email'=>'Rp 18.200.000 - 65 transaksi','points'=>'88.940','rank'=>2],
                        ['name'=>'Rio Saputra Jaya (Toko)','email'=>'Rp 14.500.000 - 52 transaksi','points'=>'82.500','rank'=>3],
                        ['name'=>'PT Wibudi Sukses','email'=>'Rp 11.800.000 - 48 transaksi','points'=>'76.200','rank'=>4],
                        ];
                        @endphp

                        @foreach($customers as $customer)
                        <li class="py-3">
                            <div class="flex items-center gap-2">
                                <div class="shrink-0">
                                    @if($customer['rank'] == 1)
                                    <div class="w-8 h-8 rounded-full bg-yellow-400 flex items-center justify-center text-white">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 1l2.39 4.84 5.34.78-3.87 3.77.91 5.31L10 13.77 5.23 15.7l.91-5.31-3.87-3.77 5.34-.78L10 1z" />
                                        </svg>
                                    </div>
                                    @elseif($customer['rank'] == 2)
                                    <div class="w-8 h-8 rounded-full bg-gray-400 flex items-center justify-center text-white">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 2l2 6h6l-4.5 4 2 6L10 14l-5.5 4 2-6L2 8h6l2-6z" />
                                        </svg>
                                    </div>
                                    @elseif($customer['rank'] == 3)
                                    <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center text-white">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 0l2.39 4.84 5.34.78-3.87 3.77.91 5.31L10 13.77 5.23 15.7l.91-5.31-3.87-3.77 5.34-.78L10 0z" />
                                        </svg>
                                    </div>
                                    @else
                                    <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-700 font-semibold text-sm">
                                        {{$customer['rank']}}
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 ms-2">
                                    <p class="font-medium text-gray-800 truncate font-poppins text-sm">{{$customer['name']}}</p>
                                    <p class="text-xs text-gray-500 font-poppins truncate">{{$customer['email']}}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-medium text-gray-800 font-poppins">{{$customer['points']}}</p>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Latest Transactions Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 flex flex-col" style="height: 450px;">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-xl font-semibold leading-none text-gray-800 font-poppins">Recent Transaction</h5>
                    <a href="/transaction" class="text-xs text-blue-600 hover:underline font-poppins">See all</a>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <ul role="list" class="divide-y divide-gray-200">
                        @php
                        $transactions = [
                        ['id'=>'#7','customer'=>'PT Rafka Lestari mengirim bayi order.','status'=>'completed','date'=>'20 min'],
                        ['id'=>'#8','customer'=>'Adi Wahab di Marketo','status'=>'completed','date'=>'2 jam'],
                        ['id'=>'#9','customer'=>'Rayi Saputra Jaya (Toko)','status'=>'pending','date'=>'3 jam'],
                        ['id'=>'#10','customer'=>'Selamat Nurhalimah pembuatan...','status'=>'completed','date'=>'4 jam'],
                        ['id'=>'#11','customer'=>'Adi Wahab di Marketo','status'=>'failed','date'=>'5 jam'],
                        ];
                        @endphp

                        @foreach($transactions as $transaction)
                        <li class="py-3">
                            <div class="flex items-start gap-3">
                                <div class="shrink-0">
                                    <div class="w-10 h-10 rounded-lg 
                                        @if($transaction['status'] == 'completed') 
                                        @elseif($transaction['status'] == 'pending') bg-yellow-100 
                                        @else 
                                        @endif
                                        flex items-center justify-center">
                                        @if($transaction['status'] == 'completed')
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        @elseif($transaction['status'] == 'pending')
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        @else
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        </div>
    </div>
</div>
@endsection