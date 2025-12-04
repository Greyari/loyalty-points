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