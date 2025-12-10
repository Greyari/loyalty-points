<!-- Chart Card -->
<div class="transform transition-transform duration-300 hover:-translate-y-1 bg-white border border-gray-200 rounded-xl shadow-sm p-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
        <!-- Title Section -->
        <div>
            <h5 class="text-2xl font-bold text-gray-900 font-poppins">Monthly Sales</h5>
            <p class="text-gray-500 font-poppins">Sales chart data</p>
        </div>

        <!-- Filter Dropdown Section -->
        <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
            <!-- Mode Filter -->
            <select id="filterMode"
                class="transform transition-transform duration-300 hover:-translate-y-1 font-poppins h-9 px-3 pr-9 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white cursor-pointer">
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
            </select>

            <!-- Year Filter (visible only in monthly mode) -->
            <select id="filterYear"
                class="transform transition-transform duration-300 hover:-translate-y-1 font-poppins h-9 px-3 pr-9 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white cursor-pointer hidden">
                <option value="">All Years</option>
            </select>
        </div>
    </div>

    <!-- Chart Container -->
    <div class="relative">
        <div id="chartLoader" class="hidden absolute inset-0 items-center justify-center bg-white bg-opacity-75 z-10">
            <div class="flex flex-col items-center">
                <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-600 font-poppins">Loading chart...</p>
            </div>
        </div>

        <div id="main-chart" class="py-4 min-h-[412px]"></div>
    </div>
</div>

<style>
    /* Select hover effect */
    select:hover {
        border-color: #6366f1;
        background: #eef2ff;
    }

    /* Select option styling */
    select option {
        font-family: 'Poppins', sans-serif;
    }

    select option:hover,
    select option:checked {
        background: #eef2ff !important;
        color: #0837e2 !important;
    }

    /* Select focus effect */
    select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, .3);
        background: #ffffff;
    }

    /* Smooth transitions */
    select,
    #filterYear {
        transition: all 0.3s ease;
    }
</style>