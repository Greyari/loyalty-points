@props([
'headers' => [],
'rows' => [],
'onAdd' => null,
'onEdit' => null,
'onDelete' => null,
])

@php
$componentId = 'table_' . uniqid();
@endphp

<div id="{{ $componentId }}" class="w-full">

    <!-- Controls (Outside Table) -->
    <div class="flex flex-col md:flex-row justify-between gap-3 mb-4 items-center">
        <!-- Entries per-page -->
        <div class="flex items-center">
            <label class="text-sm mr-2 font-medium font-poppins">Show</label>
            <select class="entries-select bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 px-2.5 py-1.5 pr-8">
                <option value="10" class="font-poppins">10</option>
                <option value="25" class="font-poppins">25</option>
                <option value="50" class="font-poppins">50</option>
                <option value="100" class="font-poppins">100</option>
            </select>
            <span class="text-sm ml-2 font-medium font-poppins">entries</span>
        </div>

        <!-- Search + Add button -->
        <div class="flex items-center space-x-2 w-full md:w-auto font-poppins">
            <input
                type="text"
                class="search-input border border-gray-300 rounded-xl px-3 py-3 text-sm w-full md:w-92 focus:ring focus:ring-gray-300"
                placeholder="Search..." />
            <button
                type="button"
                class="px-3 py-2.5 bg-blue-800 text-white rounded-xl hover:bg-blue-900"
                @if($onAdd)
                @click="{{ $onAdd }}()"
                @endif>
                + Add Data
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto bg-white p-4 rounded-xl shadow-sm border border-gray-200">
        <table class="w-full">
            <thead class="border-b border-gray-200">
                <tr>
                    @foreach ($headers as $header)
                    <th class="text-left py-3 px-2 font-semibold text-gray-800 whitespace-nowrap font-poppins">{{ $header }}</th>
                    @endforeach
                    <th class="text-center py-3 px-2 font-semibold text-gray-600 whitespace-nowrap font-poppins">Action</th>
                </tr>
            </thead>

            <tbody class="table-body">
                @foreach ($rows as $row)
                <tr class="table-row border-b border-gray-200 hover:bg-gray-100"
                    data-search="{{ strtolower(implode(' ', array_values($row))) }}"
                    data-id="{{ $row['id'] ?? '' }}">
                    @foreach ($row as $key => $value)
                    @if($key !== 'id') {{-- jangan tampilkan kolom id --}}
                    <td class="py-2 px-2">{{ $value }}</td>
                    @endif
                    @endforeach
                    <td class="py-2 px-2">
                        <div class="flex justify-center items-center gap-2">
                            <!-- Edit Icon -->
                            <button
                                @if($onEdit)
                                @click="{{ $onEdit }}('{{ $row['id'] }}')"
                                @endif
                                class="text-blue-600 hover:text-blue-800 transition-colors"
                                title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>

                            <!-- Delete Icon -->
                            <button
                                @if($onDelete)
                                @click="{{ $onDelete }}('{{ $row['id'] }}')"
                                @endif
                                class="text-red-600 hover:text-red-800 transition-colors"
                                title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="no-data-row text-center" style="display:none;">
                    <td colspan="{{ count($headers) + 1 }}" class="py-6 text-gray-500 font-medium font-poppins no-data-text"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col md:flex-row justify-between items-center mt-4 gap-2">
        <p class="text-sm text-gray-600 info-text">
            Showing 1 to {{ min(10, count($rows)) }} of {{ count($rows) }} entries
        </p>

        <div class="flex items-center">
            <div class="pagination flex">
                <!-- Prev -->
                <button class="font-poppins prev-btn px-3 py-1 rounded-l-lg border border-gray-300 text-sm disabled:opacity-50 disabled:pointer-events-none bg-white hover:bg-gray-100">
                    <
                        </button>

                        <!-- Page numbers (JS injected) -->
                        <div class="page-numbers flex font-poppins"></div>

                        <!-- Next -->
                        <button class="font-poppins next-btn px-3 py-1 rounded-r-lg border border-gray-300 text-sm disabled:opacity-50 disabled:pointer-events-none bg-white hover:bg-gray-100">
                            >
                        </button>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('{{ $componentId }}');
        if (!container) return;

        const searchInput = container.querySelector('.search-input');
        const entriesSelect = container.querySelector('.entries-select');
        const tbody = container.querySelector('.table-body');
        const infoText = container.querySelector('.info-text');
        const prevBtn = container.querySelector('.prev-btn');
        const nextBtn = container.querySelector('.next-btn');
        const paginationNumbers = container.querySelector('.page-numbers');

        let currentPage = 1;
        let entriesPerPage = parseInt(entriesSelect.value);
        let allRows = Array.from(tbody.querySelectorAll('.table-row'));
        let visibleRows = [...allRows];

        /**
         * Render pagination buttons dynamically
         */
        function renderPageNumbers() {
            paginationNumbers.innerHTML = '';
            const totalPages = Math.ceil(visibleRows.length / entriesPerPage) || 1;

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `px-3 py-1 border-t border-b border-l border-gray-300 text-sm bg-white text-gray-700 hover:bg-gray-100`;

                if (i === currentPage) {
                    btn.className = `px-3 py-1 border-t border-b border-l border-gray-300 text-sm bg-blue-500 text-white`;
                }

                btn.addEventListener('click', () => {
                    currentPage = i;
                    updateTable();
                });

                paginationNumbers.appendChild(btn);
            }

            if (paginationNumbers.firstChild) {
                paginationNumbers.firstChild.classList.add('border-l-0');
            }
        }

        /**
         * Update table display based on current page and search
         */
        function updateTable() {
            allRows.forEach(row => row.style.display = 'none');

            const start = (currentPage - 1) * entriesPerPage;
            const end = start + entriesPerPage;
            const rowsToShow = visibleRows.slice(start, end);
            rowsToShow.forEach(row => row.style.display = '');

            const totalVisible = visibleRows.length;
            const showingStart = totalVisible > 0 ? start + 1 : 0;
            const showingEnd = Math.min(end, totalVisible);
            infoText.textContent = `Showing ${showingStart} to ${showingEnd} of ${totalVisible} entries`;

            const totalPages = Math.ceil(totalVisible / entriesPerPage) || 1;
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage >= totalPages;

            // no data handling
            const noDataRow = container.querySelector('.no-data-row');
            if (visibleRows.length === 0) {
                noDataRow.style.display = '';
                infoText.textContent = `Showing 0 to 0 of 0 entries`;
                paginationNumbers.innerHTML = '';
                prevBtn.disabled = true;
                nextBtn.disabled = true;
                return;
            } else {
                noDataRow.style.display = 'none';
            }

            renderPageNumbers();
        }

        // Search filter
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            visibleRows = query === '' ? [...allRows] : allRows.filter(row => row.dataset.search.includes(query));
            currentPage = 1;

            // simpan kata yang dicari ke no-data-text
            const noDataText = container.querySelector('.no-data-text');
            noDataText.textContent = `No data found for "${this.value}"`;
            updateTable();
        });

        // Change entries per page
        entriesSelect.addEventListener('change', function() {
            entriesPerPage = parseInt(this.value);
            currentPage = 1;
            updateTable();
        });

        // Prev / Next buttons
        prevBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updateTable();
            }
        });

        nextBtn.addEventListener('click', function() {
            const totalPages = Math.ceil(visibleRows.length / entriesPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updateTable();
            }
        });

        updateTable();
    });
</script>