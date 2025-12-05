 <!-- Total Penjualan Card -->
 <div class="bg-white rounded-lg shadow-md p-4 flex justify-between items-center h-[113px]">
     <div class="flex flex-col justify-between h-full">
         <div>
             <p class="font-bold text-gray-900 text-base font-poppins">Total Sales</p>
             <p class="text-gray-500 text-sm font-poppins">Total amount this month</p>
         </div>

        <p class="font-bold text-gray-900 text-xl mt-2 font-poppins">
            Rp. {{ number_format($totalSales ?? 0, 0, ',', '.') }}
        </p>
     </div>
     <!-- kalo penjualan naik -->
     <div class="flex items-center justify-center w-16 h-16 rounded-full bg-green-100">
         <svg class="w-8 h-8 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
             <path d="M16 7h6v6" />
             <path d="m22 7-8.5 8.5-5-5L2 17" />
         </svg>
     </div>
     <!-- kalo penjualan turun -->
     <!-- <div class="flex items-center justify-center w-16 h-16 rounded-full bg-red-100">
         <svg class="w-8 h-8 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
             <path d="M16 17h6v-6" />
             <path d="m22 17-8.5-8.5-5 5L2 7" />
         </svg>
     </div> -->

 </div>
