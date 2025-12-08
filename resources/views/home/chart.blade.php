 <!-- Chart Card -->
 <div class="  transform transition-transform duration-300 hover:-translate-y-1  bg-white border border-gray-200 rounded-xl shadow-sm p-6">
     <div class="flex justify-between items-center mb-4">
         <div>
             <h5 class="text-2xl font-bold text-gray-900 font-poppins">Monthly Sales</h5>
             <p class="text-gray-500 font-poppins">Sales chart data</p>
         </div>
         <!-- Dropdown Wrapper -->
         <div class="flex flex-col sm:flex-row gap-3 mb-4 items-start sm:items-center">
             <select id="filterMode"
                 class=" transform transition-transform duration-300 hover:-translate-y-1  font-poppins h-9 px-3 pr-9 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white cursor-pointer">
                 <option value="monthly">Monthly</option>
                 <option value="yearly">Yearly</option>
             </select>

             <select id="filterYear"
                 class=" transform transition-transform duration-300 hover:-translate-y-1  font-poppins h-9 px-3 pr-9 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white cursor-pointer hidden ">
                 <option value="">All Years</option>
             </select>
         </div>
     </div>
     <div id="main-chart" class="py-4"></div>
 </div>
 <style>
     select:hover {
         border-color: #6366f1;
         /* indigo-500 */
         background: #eef2ff;
         /* indigo-50 */
     }

     /* Hover saat dropdown terbuka (option list) */
     select option {
         font-family: 'Poppins', sans-serif;
     }

     select option:hover,
     select option:checked {
         background: #eef2ff !important;
         color: #0837e2 !important;
     }

     select:focus {
         border-color: #4f46e5;
         /* indigo-600 */
         box-shadow: 0 0 0 3px rgba(79, 70, 229, .3);
         background: #ffffff;
     }
 </style>