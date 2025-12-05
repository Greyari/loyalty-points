 <!-- Chart Card -->
 <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
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

    {{-- Dropdown --}}
    <div class="flex gap-3 mb-4">

        <select id="filterYear" class="border rounded-md px-2 py-1 text-sm">
            <option value="">All Years</option>
        </select>

        <select id="filterMonth" class="border rounded-md px-2 py-1 text-sm">
            <option value="">All Months</option>
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>

    </div>

    <div id="main-chart" class="py-4"></div>

 </div>
