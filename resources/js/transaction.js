// ==================== CONSTANTS ====================
const SELECTORS = {
    modals: {
        create: '#transactionCreateModal',
        edit: '#transactionEditModal',
        delete: '#transactionDeleteModal'
    },
    forms: {
        create: '#createForm',
        edit: '#editForm'
    },
    fields: {
        customerSelect: '#customer_select',
        productSelect: '#product_select',
        qtyInput: '#qty_input',
        totalPointsDisplay: '#total_points_display',
        editId: '#edit_id',
        editOrderId: '#edit_order_id',
        editCustomerSelect: '#edit_customer_select',
        editProductId: '#edit_product_id',
        editQty: '#edit_qty',
        editTotalPointsDisplay: '#edit_total_points_display',
        deleteId: '#delete_id',
        deleteOrderId: '#delete_order_id'
    },
    table: {
        body: '.table-body',
        row: (id) => `tr[data-id="${id}"]`,
        container: '[id^="table_"]',
        searchInput: '.search-input'
    }
};

const API_ENDPOINTS = {
    transactions: '/transaction',
    transactionItem: (id) => `/transaction/${id}`
};

// ==================== UTILITY FUNCTIONS ====================
const utils = {
    getElement: (selector) => document.querySelector(selector),

    formatNumber: (number) => parseInt(number).toLocaleString('id-ID'),

    parseNumber: (text) => text.replace(/\./g, '').replace(/,/g, ''),

    getCsrfToken: () => document.querySelector('meta[name="csrf-token"]').content,

    createSearchData: (data) => `${data.order_id} ${data.date} ${data.customer} ${data.product} ${data.sku} ${data.qty} ${data.points} ${data.total_points}`.toLowerCase()
};

// ==================== API FUNCTIONS ====================
const api = {
    async request(url, options) {
        const response = await fetch(url, {
            headers: {
                'X-CSRF-TOKEN': utils.getCsrfToken(),
                'Accept': 'application/json',
                ...options.headers
            },
            ...options
        });

        const contentType = response.headers.get('content-type');
        if (!contentType?.includes('application/json')) {
            const text = await response.text();
            console.error('❌ Non-JSON response:', text.substring(0, 500));
            throw new Error('Server tidak mengembalikan JSON');
        }

        return response.json();
    },

    create(formData) {
        return this.request(API_ENDPOINTS.transactions, {
            method: 'POST',
            body: formData
        });
    },

    update(id, formData) {
        formData.append('_method', 'PUT');
        return this.request(API_ENDPOINTS.transactionItem(id), {
            method: 'POST',
            body: formData
        });
    },

    delete(id) {
        return this.request(API_ENDPOINTS.transactionItem(id), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                _method: 'DELETE',
                _token: utils.getCsrfToken()
            })
        });
    }
};

// ==================== UI FUNCTIONS ====================
const ui = {
    showNotification(type, message) {
        document.querySelectorAll('.transaction-notification').forEach(n => n.remove());

        const config = {
            success: { color: 'green', icon: 'M5 13l4 4L19 7' },
            error: { color: 'red', icon: 'M6 18L18 6M6 6l12 12' },
            warning: { color: 'orange', icon: 'M12 9v4m0 4h.01' }
        };

        const { color, icon } = config[type];
        const notification = document.createElement('div');
        notification.className = 'transaction-notification fixed bottom-4 right-4 flex items-center w-full max-w-sm p-4 rounded-xl shadow-lg bg-white z-[9999]';
        notification.innerHTML = `
            <div class="shrink-0 w-10 h-10 mr-3 flex items-center justify-center rounded-full bg-${color}-500/20 text-${color}-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="${icon}" />
                </svg>
            </div>
            <div class="flex-1 text-sm text-gray-900 font-poppins">${message}</div>
            <button class="ml-4 text-gray-400 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;

        document.body.appendChild(notification);
        notification.querySelector('button').onclick = () => notification.remove();
        setTimeout(() => notification.remove(), 5000);
    },

    createRowHTML(data) {
        return `
            <td class="py-4 px-2">${data.order_id}</td>
            <td class="py-4 px-2">${data.date}</td>
            <td class="py-4 px-2">${data.customer}</td>
            <td class="py-4 px-2">${data.product}</td>
            <td class="py-4 px-2">${data.sku}</td>
            <td class="py-4 px-2">${data.qty}</td>
            <td class="py-4 px-2">${data.points}</td>
            <td class="py-4 px-2">${data.total_points}</td>
            <td class="py-2 px-2">
                <div class="flex justify-center items-center gap-2">
                    <button class="edit-btn text-blue-600 hover:text-blue-800 transition-colors" data-id="${data.id}" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                    </button>
                    <button class="delete-btn text-red-600 hover:text-red-800 transition-colors" data-id="${data.id}" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </td>
        `;
    },

    animate(element, styles, duration = 300) {
        Object.assign(element.style, styles);
        return new Promise(resolve => setTimeout(resolve, duration));
    },

    calculateTotalPoints(productSelector, qtySelector, displaySelector) {
        const $productSelect = $(productSelector);
        const $qtyInput = $(qtySelector);
        const $display = $(displaySelector);

        const selectedOption = $productSelect.find(':selected');
        const pointsPerUnit = parseFloat(selectedOption.data('points')) || 0;
        const qty = parseInt($qtyInput.val()) || 0;
        const totalPoints = pointsPerUnit * qty;

        $display.val(utils.formatNumber(totalPoints));
    }
};

// ==================== TABLE OPERATIONS ====================
const table = {
    async addRow(data) {
        const tbody = utils.getElement(SELECTORS.table.body);
        if (!tbody) return;

        const row = document.createElement('tr');
        row.className = 'table-row border-b border-gray-200 hover:bg-gray-50';
        row.setAttribute('data-id', data.id);
        row.setAttribute('data-search', utils.createSearchData(data));
        row.innerHTML = ui.createRowHTML(data);

        row.style.opacity = '0';
        tbody.insertBefore(row, tbody.firstChild);

        await ui.animate(row, { transition: 'opacity 0.3s ease-in', opacity: '1' }, 10);
        this.updateInfo();
    },

    async updateRow(id, data) {
        const row = utils.getElement(SELECTORS.table.row(id));
        if (!row) return;

        row.setAttribute('data-search', utils.createSearchData(data));

        const cells = row.querySelectorAll('td');
        cells[0].textContent = data.order_id;
        cells[1].textContent = data.date;
        cells[2].textContent = data.customer;
        cells[3].textContent = data.product;
        cells[4].textContent = data.sku;
        cells[5].textContent = data.qty;
        cells[6].textContent = data.points;
        cells[7].textContent = data.total_points;

        await ui.animate(row, { backgroundColor: '#dbeafe' }, 100);
        await ui.animate(row, { transition: 'background-color 0.5s ease', backgroundColor: '' });
    },

    async removeRow(id) {
        const row = utils.getElement(SELECTORS.table.row(id));
        if (!row) return;

        await ui.animate(row, { transition: 'opacity 0.3s ease-out', opacity: '0' });
        row.remove();
        this.updateInfo();
    },

    updateInfo() {
        const container = utils.getElement(SELECTORS.table.container);
        if (!container) return;

        container.dispatchEvent(new CustomEvent('table:updated'));

        const searchInput = container.querySelector(SELECTORS.table.searchInput);
        if (searchInput) {
            searchInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }
};

// ==================== EVENT HANDLERS ====================
const handlers = {
    onAdd() {
        const form = utils.getElement(SELECTORS.forms.create);
        if (form) form.reset();
        
        $(SELECTORS.fields.customerSelect).val(null).trigger('change');
        $(SELECTORS.fields.productSelect).val(null).trigger('change');
        $(SELECTORS.fields.totalPointsDisplay).val('0');
        
        if (typeof open_transactionCreateModal === 'function') open_transactionCreateModal();
    },

    onEdit(e) {
        const id = e.detail.id;
        const row = utils.getElement(SELECTORS.table.row(id));
        if (!row || !utils.getElement(SELECTORS.fields.editId)) return;

        const cells = row.querySelectorAll('td');
        if (cells.length < 8) return;

        const orderId = cells[0].textContent.trim();
        const customerText = cells[2].textContent.trim();
        const productText = cells[3].textContent.trim();
        const qty = cells[5].textContent.trim();

        utils.getElement(SELECTORS.fields.editId).value = id;
        utils.getElement(SELECTORS.fields.editOrderId).value = orderId;
        
        const $customerSelect = $(SELECTORS.fields.editCustomerSelect);
        $customerSelect.find('option').each(function() {
            if ($(this).text() === customerText) {
                $customerSelect.val($(this).val()).trigger('change');
            }
        });

        const $productSelect = $(SELECTORS.fields.editProductId);
        $productSelect.find('option').each(function() {
            if ($(this).text().includes(productText)) {
                $productSelect.val($(this).val()).trigger('change');
            }
        });

        utils.getElement(SELECTORS.fields.editQty).value = qty;

        setTimeout(() => {
            ui.calculateTotalPoints(
                SELECTORS.fields.editProductId,
                SELECTORS.fields.editQty,
                SELECTORS.fields.editTotalPointsDisplay
            );
        }, 100);

        if (typeof open_transactionEditModal === 'function') open_transactionEditModal();
    },

    onDelete(e) {
        const id = e.detail.id;
        const row = utils.getElement(SELECTORS.table.row(id));
        if (!row || !utils.getElement(SELECTORS.fields.deleteId)) return;

        const orderId = row.querySelector('td:first-child');
        if (!orderId) return;

        utils.getElement(SELECTORS.fields.deleteId).value = id;
        const deleteOrderIdEl = utils.getElement(SELECTORS.fields.deleteOrderId);
        if (deleteOrderIdEl) deleteOrderIdEl.textContent = orderId.textContent.trim();

        if (typeof open_transactionDeleteModal === 'function') open_transactionDeleteModal();
    },

    async onCreate() {
        const form = utils.getElement(SELECTORS.forms.create);
        if (!form) return;

        try {
            const data = await api.create(new FormData(form));

            if (data.success) {
                if (typeof close_transactionCreateModal === 'function') close_transactionCreateModal();
                await table.addRow(data.data);
                ui.showNotification('success', data.message);
                form.reset();
                $(SELECTORS.fields.customerSelect).val(null).trigger('change');
                $(SELECTORS.fields.productSelect).val(null).trigger('change');
                $(SELECTORS.fields.totalPointsDisplay).val('0');
            } else {
                ui.showNotification('error', data.message || 'Gagal menambahkan transaksi');
            }
        } catch (error) {
            console.error('❌ Create error:', error);
            ui.showNotification('error', error.message || 'Terjadi kesalahan');
        }
    },

    async onEditSubmit() {
        const form = utils.getElement(SELECTORS.forms.edit);
        if (!form) return;

        const formData = new FormData(form);
        const id = utils.getElement(SELECTORS.fields.editId).value;

        try {
            const data = await api.update(id, formData);

            if (data.success) {
                if (typeof close_transactionEditModal === 'function') close_transactionEditModal();
                await table.updateRow(id, data.data);
                ui.showNotification('success', data.message);
            } else {
                ui.showNotification('error', data.message || 'Gagal mengupdate transaksi');
            }
        } catch (error) {
            console.error('❌ Update error:', error);
            ui.showNotification('error', error.message || 'Terjadi kesalahan');
        }
    },

    async onDeleteSubmit() {
        const id = utils.getElement(SELECTORS.fields.deleteId).value;

        try {
            const data = await api.delete(id);

            if (data.success) {
                if (typeof close_transactionDeleteModal === 'function') close_transactionDeleteModal();
                await table.removeRow(id);
                ui.showNotification('success', data.message);
            } else {
                ui.showNotification('error', data.message || 'Gagal menghapus transaksi');
            }
        } catch (error) {
            console.error('❌ Delete error:', error);
            ui.showNotification('error', error.message || 'Terjadi kesalahan');
        }
    }
};

// ==================== SELECT2 INITIALIZATION ====================
function formatProduct(product) {
    if (!product.id) return product.text;
    
    const $element = $(product.element);
    const name = $element.text().split('(')[0].trim();
    const sku = $element.data('sku');
    const points = $element.data('points');
    
    return $(
        '<div class="flex flex-col py-1">' +
            '<span class="font-medium text-gray-900">' + name + '</span>' +
            '<span class="text-xs text-gray-500 mt-0.5">SKU: ' + sku + ' • ' + points + ' pts/unit</span>' +
        '</div>'
    );
}

function formatProductSelection(product) {
    if (!product.id) return product.text;
    return $(product.element).text().split('(')[0].trim();
}

// ==================== INITIALIZATION ====================
$(document).ready(function() {
    console.log('✅ Point Transaction module loaded');

    // Initialize Select2 for customer dropdowns
    $(SELECTORS.fields.customerSelect).select2({
        placeholder: 'Select customer',
        allowClear: false,
        width: '100%',
        dropdownParent: $(SELECTORS.modals.create)
    });

    $(SELECTORS.fields.editCustomerSelect).select2({
        placeholder: 'Select customer',
        allowClear: false,
        width: '100%',
        dropdownParent: $(SELECTORS.modals.edit)
    });

    // Initialize Select2 for product dropdowns
    $(SELECTORS.fields.productSelect).select2({
        placeholder: 'Select product',
        allowClear: false,
        width: '100%',
        dropdownParent: $(SELECTORS.modals.create),
        templateResult: formatProduct,
        templateSelection: formatProductSelection
    });

    $(SELECTORS.fields.editProductId).select2({
        placeholder: 'Select product',
        allowClear: false,
        width: '100%',
        dropdownParent: $(SELECTORS.modals.edit),
        templateResult: formatProduct,
        templateSelection: formatProductSelection
    });

    // Auto-calculate total points (Create Modal)
    $(SELECTORS.fields.productSelect + ', ' + SELECTORS.fields.qtyInput).on('change input', function() {
        ui.calculateTotalPoints(
            SELECTORS.fields.productSelect,
            SELECTORS.fields.qtyInput,
            SELECTORS.fields.totalPointsDisplay
        );
    });

    // Auto-calculate total points (Edit Modal)
    $(SELECTORS.fields.editProductId + ', ' + SELECTORS.fields.editQty).on('change input', function() {
        ui.calculateTotalPoints(
            SELECTORS.fields.editProductId,
            SELECTORS.fields.editQty,
            SELECTORS.fields.editTotalPointsDisplay
        );
    });

    // Register event listeners
    document.addEventListener('table:add', handlers.onAdd);
    document.addEventListener('table:edit', handlers.onEdit);
    document.addEventListener('table:delete', handlers.onDelete);

    utils.getElement(SELECTORS.modals.create).addEventListener('modal:submit', handlers.onCreate);
    utils.getElement(SELECTORS.modals.edit).addEventListener('modal:submit', handlers.onEditSubmit);
    utils.getElement(SELECTORS.modals.delete).addEventListener('modal:submit', handlers.onDeleteSubmit);
});

// ==================== CSRF TOKEN SETUP ====================
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// ==================== MODAL HANDLERS ====================
// Open Create Modal
$(document).on('click', '[data-modal="transactionCreateModal"]', function() {
    $('#createForm')[0].reset();
    $('#customer_select').val(null).trigger('change');
    $('#product_select').val(null).trigger('change');
    $('#total_points_display').val('0');
    $('#transactionCreateModal').removeClass('hidden');
});

// Open Delete Modal
$(document).on('click', '.delete-btn', function() {
    var id = $(this).data('id');
    var orderId = $(this).data('order-id');
    
    $('#delete_id').val(id);
    $('#delete_order_id').text(orderId);
    $('#transactionDeleteModal').removeClass('hidden');
});

// ==================== FORM SUBMISSIONS ====================
// Create Transaction
$(document).on('click', '#transactionCreateModal button[type="submit"]', function(e) {
    e.preventDefault();
    
    var formData = {
        customer_id: $('#customer_select').val(),
        product_id: $('#product_select').val(),
        qty: $('#qty_input').val()
    };
    
    $.post('/point-transactions', formData, function(response) {
        $('#transactionCreateModal').addClass('hidden');
        location.reload();
    }).fail(function(xhr) {
        alert('Error: ' + (xhr.responseJSON?.message || 'Failed to create transaction'));
    });
});

// Update Transaction
$(document).on('click', '#transactionEditModal button[type="submit"]', function(e) {
    e.preventDefault();
    
    var id = $('#edit_id').val();
    var formData = {
        customer_id: $('#edit_customer_select').val(),
        product_id: $('#edit_product_id').val(),
        qty: $('#edit_qty').val(),
        _method: 'PUT'
    };
    
    $.post('/point-transactions/' + id, formData, function(response) {
        $('#transactionEditModal').addClass('hidden');
        location.reload();
    }).fail(function(xhr) {
        alert('Error: ' + (xhr.responseJSON?.message || 'Failed to update transaction'));
    });
});

// Delete Transaction
$(document).on('click', '#transactionDeleteModal button[type="submit"]', function(e) {
    e.preventDefault();
    
    var id = $('#delete_id').val();
    
    $.ajax({
        url: '/point-transactions/' + id,
        type: 'DELETE',
        success: function(response) {
            $('#transactionDeleteModal').addClass('hidden');
            location.reload();
        },
        error: function(xhr) {
            alert('Error: ' + (xhr.responseJSON?.message || 'Failed to delete transaction'));
        }
    });
});

// ==================== MODAL CLOSE HANDLERS ====================
$(document).on('click', '[data-dismiss="modal"]', function() {
    $(this).closest('.fixed').addClass('hidden');
});

$(document).on('click', '.fixed', function(e) {
    if (e.target === this) {
        $(this).addClass('hidden');
    }
});