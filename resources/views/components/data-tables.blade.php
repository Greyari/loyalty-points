@props([
'headers' => [],
'rows' => [],
'onAdd' => null,
'onView' => null,
'onEdit' => null,
'onDelete' => null,
'module' => 'default',
])

@php
$componentId = 'table_' . uniqid();
@endphp

<div id="{{ $componentId }}" class="w-full" data-module="{{ $module }}">

    <!-- Controls (Outside Table) -->
    <div class="flex flex-col md:flex-row justify-between gap-3 mb-4 items-center">
        <!-- Entries per-page -->
        <div class="flex items-center">
            <label class="text-sm mr-2 font-medium font-poppins ">Show</label>
            <select class=" entries-select bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 px-2.5 py-1.5 pr-8">
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
                class="placeholder:text-gray-600 search-input border border-gray-300 rounded-xl px-3 py-3 text-sm w-full md:w-92 focus:ring focus:ring-gray-300"
                placeholder="Search..." />
            <button
                type="button"
                class="add-button px-3 py-2.5 bg-blue-800 text-white rounded-xl hover:bg-blue-900 whitespace-nowrap">
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
                    @if($onView || $onEdit || $onDelete)
                    <th class="text-center py-3 px-2 font-semibold text-gray-800 whitespace-nowrap font-poppins">Action</th>
                    @endif
                </tr>
            </thead>

            <tbody class="table-body">
                @foreach ($rows as $row)
                <tr class="table-row border-b border-gray-200 hover:bg-gray-50"
                    data-search="{{ strtolower(implode(' ', array_values($row))) }}"
                    data-id="{{ $row['id'] ?? '' }}">
                    @foreach ($row as $key => $value)
                    @if($key !== 'id')
                    <td class="py-4 px-2 text-sm text-gray-800">{!! $value !!}</td>

                    @endif
                    @endforeach
                    @if( $onView || $onEdit || $onDelete)
                    <td class="py-2 px-2">
                        <div class="flex justify-center items-center gap-2">
                            @if($onView)
                            <button
                                class="view-btn text-gray-600 hover:text-gray-800 transition-colors"
                                data-id="{{ $row['id'] }}"
                                title="View">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            @endif

                            @if($onEdit)
                            <button
                                class="edit-btn text-indigo-600 hover:text-indigo-800 transition-colors"
                                data-id="{{ $row['id'] }}"
                                title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                            @endif

                            @if($onDelete)
                            <button
                                class="delete-btn text-red-600 hover:text-red-800 transition-colors"
                                data-id="{{ $row['id'] }}"
                                title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                            @endif
                        </div>
                    </td>
                    @endif
                </tr>
                @endforeach
                <tr class="no-data-row text-center" style="display:none;">
                    <td colspan="{{ count($headers) + ($onEdit || $onDelete ? 1 : 0) }}" class="py-6 text-gray-500 font-medium font-poppins no-data-text"></td>
                </tr>
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
                    &lt;
                </button>

                <!-- Page numbers (JS injected) -->
                <div class="page-numbers flex font-poppins"></div>

                <!-- Next -->
                <button class="font-poppins next-btn px-3 py-1 rounded-r-lg border border-gray-300 text-sm disabled:opacity-50 disabled:pointer-events-none bg-white hover:bg-gray-100">
                    &gt;
                </button>
            </div>
        </div>
    </div>
</div>