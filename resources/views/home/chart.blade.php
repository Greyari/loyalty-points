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