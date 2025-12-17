@extends('layouts.nav')

@section('title', 'Inventory')
@section('page_title', 'Inventory')

@section('content')

<!-- Toast Messages -->
@if(session('success'))
<x-toast type="success" :message="session('success')" />
@endif

@if(session('error'))
<x-toast type="error" :message="session('error')" />
@endif

@if(session('warning'))
<x-toast type="warning" :message="session('warning')" />
@endif

<div class="space-y-6 mb-6">
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold font-poppins mb-0">Inventory</h2>
            <p class="text-sm sm:text-base lg:text-lg font-light text-gray-500 font-poppins">Manage your inventory.</p>
        </div>
        <button onclick="window.open_productImportModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors flex items-center gap-2 font-poppins">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            Import
        </button>
    </div>
</div>

<x-data-tables
    :headers="['Product Name', 'SKU', 'Point Unit']"
    :rows="$products"
    onAdd="true"
    onEdit="true"
    onDelete="true" />

<!-- Create Modal -->
<x-modal id="productCreateModal" title="Add New Product" size="lg" submitText="Save">
    <form id="createForm" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Product Name</label>
            <input type="text" name="name"
                class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                placeholder="Enter product name" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">SKU</label>
                <input type="text" name="sku"
                    class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500"
                    placeholder="SKU-001" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Quantity</label>
                <input type="number" name="quantity"
                    class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500"
                    placeholder="0" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Price</label>
                <input type="text" name="price"
                    class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500"
                    placeholder="0" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2">Points Per Unit</label>
                <input type="number" name="points_per_unit"
                    class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300  rounded-lg focus:ring-2 focus:ring-gray-500"
                    placeholder="0" required>
            </div>
        </div>
    </form>
</x-modal>

<!-- Edit Modal -->
<x-modal id="productEditModal" title="Edit Product" size="lg" submitText="Update" submitButtonClass="bg-green-600 hover:bg-green-700 text-white">
    <form id="editForm" class="space-y-4">
        <input type="hidden" name="id" id="edit_id">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Product Name</label>
            <input type="text" name="name" id="edit_name"
                class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent"
                placeholder="Enter product name" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">SKU</label>
                <input type="text" name="sku" id="edit_sku"
                    class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500"
                    placeholder="SKU-001" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 font-poppins">Quantity</label>
                <input type="number" name="quantity" id="edit_quantity"
                    class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500"
                    placeholder="0" required>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2">Points per Unit</label>
                <input type="number" name="points_per_unit" id="edit_points_per_unit"
                    class="placeholder:text-gray-400 w-full px-3 py-2 border border-gray-300  rounded-lg focus:ring-2 focus:ring-gray-500"
                    placeholder="0" required>
            </div>
        </div>
    </form>
</x-modal>

<!-- Delete Confirmation Modal -->
<x-modal id="productDeleteModal" title="Confirm Delete" size="sm" submitText="Delete" submitButtonClass="bg-red-600 hover:bg-red-700 text-white">
    <div class="text-center py-4">
        <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <p class="text-gray-700 font-poppins mb-2">Are you sure you want to delete this product?</p>
        <p class="text-sm text-gray-600 font-poppins font-semibold" id="delete_name"></p>
        <p class="text-sm text-gray-500 mt-3 font-poppins">This action cannot be undone.</p>
    </div>
    <input type="hidden" id="delete_id">
</x-modal>

<!-- Import Modal -->
<x-modal id="productImportModal" title="Import Products" size="xl" submitText="Import" submitButtonClass="bg-blue-600 hover:bg-blue-700 text-white">
    <form id="importForm" method="POST" enctype="multipart/form-data" action="{{ route('products.import') }}" class="space-y-6">
        @csrf

        <!-- Import Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-blue-900 mb-2 font-poppins">File Format Requirements</h4>
                    <p class="text-sm text-blue-800 mb-3 font-poppins">Your Excel/CSV file must contain these columns:</p>
                    <div class="bg-white rounded border border-blue-200 p-3 mb-3">
                        <div class="grid grid-cols-3 gap-2 text-xs font-mono">
                            <div class="font-semibold text-blue-900">Item Description</div>
                            <div class="font-semibold text-blue-900">Item No.</div>
                            <div class="font-semibold text-blue-900">Quantity</div>
                            <div class="text-gray-600">Product A</div>
                            <div class="text-gray-600">SKU-001</div>
                            <div class="text-gray-600">10</div>
                            <div class="text-gray-600">Product B</div>
                            <div class="text-gray-600">SKU-002</div>
                            <div class="text-gray-600">25</div>
                        </div>
                    </div>
                    <div class="space-y-1 text-sm text-blue-800">
                        <p class="flex items-start gap-2 font-poppins">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Column names can vary (Product Name, Description, SKU, Code, Qty, Stock, etc.)</span>
                        </p>
                        <p class="flex items-start gap-2 font-poppins">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>System will automatically detect headers</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Download Template Button -->
        <div class="flex justify-center">
            <a href="{{ route('products.template') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors font-poppins">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download Example Excel
            </a>
        </div>

        <!-- File Upload with Drag & Drop -->
        <div class="space-y-4">
            <div
                id="dropzone-area"
                class="flex items-center justify-center w-full">
                <label
                    for="dropzone-file"
                    id="dropzone-label"
                    class="flex flex-col items-center justify-center w-full h-64 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2" />
                        </svg>
                        <p class="mb-2 text-sm text-gray-600 font-poppins">
                            <span class="font-semibold">Click to upload</span> or drag and drop
                        </p>
                        <p class="text-xs text-gray-500 font-poppins">CSV or XLSX (MAX. 10MB)</p>
                    </div>
                    <input id="dropzone-file" name="excel_file" type="file" class="hidden" accept=".csv,.xlsx,.xls" />
                </label>
            </div>

            <div id="file-info" class="hidden">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700 font-poppins" id="file-name"></p>
                            <p class="text-xs text-gray-500 font-poppins" id="file-size"></p>
                        </div>
                    </div>
                    <button type="button" id="remove-file" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-modal>

<!-- Toast Container -->
<div id="toast-container" class="fixed bottom-4 right-4 z-50"></div>

@push('scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="{{ asset('js/inventory.js') }}"></script>
<script>
    // Toast notification function
    function showToast(type, message, duration = 5000) {
        const container = document.getElementById('toast-container');

        const iconClasses = {
            success: 'text-green-500',
            error: 'text-red-500',
            warning: 'text-orange-500'
        };

        const bgClasses = {
            success: 'bg-green-500/20',
            error: 'bg-red-500/20',
            warning: 'bg-orange-500/20'
        };

        const iconPaths = {
            success: 'M5 13l4 4L19 7',
            error: 'M6 18L18 6M6 6l12 12',
            warning: 'M12 9v4m0 4h.01'
        };

        const toast = document.createElement('div');
        toast.className = 'flex items-center w-full max-w-sm p-4 rounded-xl shadow-md bg-white/90 mb-4 transition-opacity duration-300';
        toast.innerHTML = `
            <div class="shrink-0 w-10 h-10 mr-3 flex items-center justify-center rounded-full ${bgClasses[type]}">
                <svg class="w-6 h-6 ${iconClasses[type]}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="${iconPaths[type]}" />
                </svg>
            </div>
            <div class="flex-1 text-sm text-gray-900">${message}</div>
            <button onclick="this.parentElement.remove()" class="ml-4 text-gray-400 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;

        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const dropzoneFile = document.getElementById('dropzone-file');
        const dropzoneArea = document.getElementById('dropzone-area');
        const dropzoneLabel = document.getElementById('dropzone-label');
        const fileInfo = document.getElementById('file-info');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const removeFileBtn = document.getElementById('remove-file');
        const importModal = document.getElementById('productImportModal');

        // Handle file selection
        if (dropzoneFile) {
            dropzoneFile.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    displayFileInfo(file);
                }
            });
        }

        // Drag and drop handlers
        if (dropzoneLabel) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzoneLabel.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzoneLabel.addEventListener(eventName, () => {
                    dropzoneLabel.classList.add('border-blue-500', 'bg-blue-50');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzoneLabel.addEventListener(eventName, () => {
                    dropzoneLabel.classList.remove('border-blue-500', 'bg-blue-50');
                }, false);
            });

            dropzoneLabel.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length > 0) {
                    dropzoneFile.files = files;
                    displayFileInfo(files[0]);
                }
            }, false);
        }

        // Display file info
        function displayFileInfo(file) {
            const validTypes = ['.csv', '.xlsx', '.xls'];
            const fileExt = '.' + file.name.split('.').pop().toLowerCase();

            if (!validTypes.includes(fileExt)) {
                showToast('error', 'Invalid file type. Please upload CSV or Excel file.');
                dropzoneFile.value = '';
                return;
            }

            if (file.size > 10485760) { // 10MB
                showToast('error', 'File size exceeds 10MB limit.');
                dropzoneFile.value = '';
                return;
            }

            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            fileInfo.classList.remove('hidden');
        }

        // Handle file removal
        if (removeFileBtn) {
            removeFileBtn.addEventListener('click', function() {
                dropzoneFile.value = '';
                fileInfo.classList.add('hidden');
            });
        }

        // Format file size helper
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Handle import modal submit
        if (importModal) {
            importModal.addEventListener('modal:submit', function(e) {
                if (!dropzoneFile.files.length) {
                    e.preventDefault();
                    showToast('error', 'Please select a file to import.');
                    return;
                }

                document.getElementById('importForm').submit();
            });
        }
    });
</script>
@endpush

@endsection
