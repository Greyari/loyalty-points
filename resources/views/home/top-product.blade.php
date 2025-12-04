<!-- Top Product Sales Table -->
<div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 flex flex-col" style="height: 450px;">
    <div class="flex items-center justify-between mb-6">
        <h5 class="text-xl font-semibold leading-none text-gray-800 font-poppins">Top Product Sales</h5>
        <a href="/inventory" class="font-medium text-blue-600 hover:underline">View all</a>
    </div>

    <div class="flex-1 overflow-y-auto">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-xs text-gray-700 sticky top-0">
                <tr>
                    <th scope="col" class="pl-6 pr-2 py-3 font-poppins">Rank</th>
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
                @php
                $rankClasses = match($product['rank']) {
                1 => 'bg-[#FFC569] text-white border-4 border-[#FFAF33]',
                2 => 'bg-[#B5C0CE] text-white border-4 border-[#9DABBE]',
                3 => 'bg-[#D09B6D] text-white border-4 border-[#C07D42]',
                default => 'bg-indigo-50 text-indigo-600 border border-indigo-600'
                };
                @endphp

                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="pl-6 pr-2 py-4">
                        <div class="flex items-center">
                            <div class="w-7 h-7 rounded-full {{ $rankClasses }} flex items-center justify-center font-semibold text-xs">
                                {{$product['rank']}}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{$product['sku']}}</td>
                    <td class="px-6 py-4 text-gray-600">
                        {{$product['name']}}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-gray-600">{{$product['unit']}}</span>
                    </td>
                    <td class="px-6 py-4 text-right font-semibold text-gray-900">
                        {{$product['revenue']}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>