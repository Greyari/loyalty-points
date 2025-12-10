// ==================== CONSTANTS ====================
const SELECTORS = {
    modals: {
        create: '#orderCreateModal',
        edit: '#orderEditModal',
        delete: '#orderDeleteModal',
        view: '#orderViewModal'
    },
    forms: {
        create: '#createForm',
        edit: '#editForm'
    },
    fields: {
        customerSelect: '#customer_select',
        notes: '#notes',
        itemsContainer: '#itemsContainer',
        addItemBtn: '#addItemBtn',
        summaryTotalItems: '#summaryTotalItems',
        summaryTotalPoints: '#summaryTotalPoints',
        editId: '#edit_id',
        editOrderId: '#edit_order_id',
        editCustomerSelect: '#edit_customer_select',
        editNotes: '#edit_notes',
        editItemsContainer: '#editItemsContainer',
        editAddItemBtn: '#editAddItemBtn',
        editSummaryTotalItems: '#editSummaryTotalItems',
        editSummaryTotalPoints: '#editSummaryTotalPoints',
        deleteId: '#delete_id',
        deleteOrderId: '#delete_order_id',
        orderViewContent: '#orderViewContent'
    },
    table: {
        body: '.table-body',
        row: (id) => `tr[data-id="${id}"]`,
        container: '[id^="table_"]',
        searchInput: '.search-input'
    },
    summary: {
        card: '#totalBelanjaCard',
        customerName: '#searchedCustomerName',
        amount: '#totalBelanjaAmount'
    }
};

const API_ENDPOINTS = {
    orders: '/orders',
    orderItem: (id) => `/orders/${id}`
};

// ==================== GLOBAL STATE ====================
let itemCounter = 0;
let editItemCounter = 0;

// ==================== UTILITY FUNCTIONS ====================
const utils = {
    getElement: (selector) => document.querySelector(selector),

    formatNumber: (number) => parseInt(number).toLocaleString('id-ID'),

    parseNumber: (text) => text.replace(/\./g, '').replace(/,/g, ''),

    getCsrfToken: () => document.querySelector('meta[name="csrf-token"]').content,

    createSearchData: (data) => `${data.order_id} ${data.created_at} ${data.customer} ${data.items_count} ${data.total_items}`.toLowerCase(),
 formatCurrency: (amount) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

};

// ==================== API FUNCTIONS ====================
const api = {
    async request(url, options = {}) {
        const headers = {
            'X-CSRF-TOKEN': utils.getCsrfToken(),
            'Accept': 'application/json',
            ...options.headers
        };

        const response = await fetch(url, {
            ...options,
            headers,
            credentials: 'same-origin'
        });

        const contentType = response.headers.get('content-type');
        if (!contentType?.includes('application/json')) {
            const text = await response.text();
            console.error('Server response:', text);
            throw new Error('Server tidak mengembalikan JSON');
        }

        return response.json();
    },

    create(data) {
        return this.request(API_ENDPOINTS.orders, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
    },

    show(id) {
        return this.request(API_ENDPOINTS.orderItem(id), {
            method: 'GET'
        });
    },

    update(id, data) {
        return this.request(API_ENDPOINTS.orderItem(id), {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
    },

    delete(id) {
        return this.request(API_ENDPOINTS.orderItem(id), {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' }
        });
    }
};

// ==================== UI FUNCTIONS ====================
const ui = {
    showNotification(type, message) {
        document.querySelectorAll('.order-notification').forEach(n => n.remove());

        const config = {
            success: { color: 'green', icon: 'M5 13l4 4L19 7' },
            error: { color: 'red', icon: 'M6 18L18 6M6 6l12 12' },
            warning: { color: 'orange', icon: 'M12 9v4m0 4h.01' }
        };

        const { color, icon } = config[type];
        const notification = document.createElement('div');
        notification.className = 'order-notification fixed bottom-4 right-4 flex items-center w-full max-w-sm p-4 rounded-xl shadow-lg bg-white z-[9999]';
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
            <td class="py-4 px-2">${data.created_at}</td>
            <td class="py-4 px-2">${data.customer}</td>
            <td class="py-4 px-2">${data.items_count}</td>
            <td class="py-4 px-2">${data.total_items}</td>
            <td class="py-4 px-2">${data.total_points}</td>
            <td class="py-4 px-2">${data.total_price}</td>
            <td class="py-2 px-2">
                <div class="flex justify-center items-center gap-2">
                    <button class="view-btn text-green-600 hover:text-green-800 transition-colors" data-id="${data.id}" title="View">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
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

    async animate(element, styles, duration = 300) {
        Object.assign(element.style, styles);
        return new Promise(resolve => setTimeout(resolve, duration));
    }
};

// ==================== ITEM ROW MANAGEMENT ====================
const itemRow = {
    create(containerId, isEdit = false) {
        const counter = isEdit ? ++editItemCounter : ++itemCounter;
        const prefix = isEdit ? 'edit_' : '';

        const row = document.createElement('div');
        row.className = 'item-row p-4 bg-gray-50 rounded-lg border border-gray-200';
        row.dataset.index = counter;

        row.innerHTML = `
            <div class="flex flex-col md:flex-row gap-4 items-center">
                <div class="flex-1 w-full md:w-auto min-w-0">
                    <select name="items[${counter}][product_id]"
                        class="${prefix}product-select product-select-${counter} w-full"
                        data-index="${counter}" required>
                        <option value="">Select product</option>
                        ${window.productsData.map(p => `
                            <option value="${p.id}"
                                data-sku="${p.sku}"
                                data-points="${p.points_per_unit}"
                                data-stock="${p.quantity}">
                                ${p.name} (${p.sku}) - ${p.points_per_unit} pts - Stock: ${p.quantity}
                            </option>
                        `).join('')}
                    </select>
                </div>

                <div class="flex items-center gap-4 flex-none flex-nowrap">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700 whitespace-nowrap">QTY :</label>
                        <input type="number"
                            name="items[${counter}][qty]"
                            class="${prefix}qty-input qty-input-${counter} px-2 py-1 w-16 text-center border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                            data-index="${counter}"
                            placeholder="1"
                            min="1"
                            value="1" required>
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Point :</label>
                        <input type="text"
                            class="${prefix}item-total item-total-${counter} px-2 py-1 w-16 text-center border border-gray-300 rounded bg-gray-100"
                            data-index="${counter}"
                            placeholder="0"
                            readonly>
                    </div>

                    <button type="button"
                        class="remove-item-btn p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors shrink-0"
                        aria-label="Remove item">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        `;

        const container = utils.getElement(containerId);
        container.appendChild(row);

        $(row).find('.product-select-' + counter).select2({
            width: '100%',
            dropdownParent: $(isEdit ? SELECTORS.modals.edit : SELECTORS.modals.create),
            placeholder: 'Select product',
            templateSelection: function(data) {
                if (!data.id) {
                    return data.text;
                }
                const $span = $('<span></span>');
                $span.text(data.text);
                $span.css({
                    'display': 'block',
                    'overflow': 'hidden',
                    'text-overflow': 'ellipsis',
                    'white-space': 'nowrap',
                    'max-width': '100%'
                });
                return $span;
            }
        });

        this.attachEvents(counter, isEdit);

        return row;
    },

    attachEvents(index, isEdit = false) {
        const prefix = isEdit ? 'edit_' : '';
        const $productSelect = $(`.${prefix}product-select.product-select-${index}`);
        const $qtyInput = $(`.${prefix}qty-input.qty-input-${index}`);

        $productSelect.on('change', () => this.calculateItemTotal(index, isEdit));
        $qtyInput.on('input', () => this.calculateItemTotal(index, isEdit));
    },

    calculateItemTotal(index, isEdit = false) {
        const prefix = isEdit ? 'edit_' : '';
        const $productSelect = $(`.${prefix}product-select.product-select-${index}`);
        const $qtyInput = $(`.${prefix}qty-input.qty-input-${index}`);
        const $totalInput = $(`.${prefix}item-total.item-total-${index}`);

        const selectedOption = $productSelect.find(':selected');
        const points = parseInt(selectedOption.data('points')) || 0;
        const qty = parseInt($qtyInput.val()) || 0;
        const total = points * qty;

        $totalInput.val(utils.formatNumber(total));

        this.updateSummary(isEdit);
    },

    updateSummary(isEdit = false) {
        const prefix = isEdit ? 'edit_' : '';
        let totalItems = 0;
        let totalPoints = 0;

        $(`.${prefix}qty-input`).each(function() {
            const qty = parseInt($(this).val()) || 0;
            const index = $(this).data('index');
            const $productSelect = $(`.${prefix}product-select.product-select-${index}`);
            const points = parseInt($productSelect.find(':selected').data('points')) || 0;

            totalItems += qty;
            totalPoints += (qty * points);
        });

        const summaryItemsId = isEdit ? SELECTORS.fields.editSummaryTotalItems : SELECTORS.fields.summaryTotalItems;
        const summaryPointsId = isEdit ? SELECTORS.fields.editSummaryTotalPoints : SELECTORS.fields.summaryTotalPoints;

        $(summaryItemsId).text(utils.formatNumber(totalItems));
        $(summaryPointsId).text(utils.formatNumber(totalPoints));
    },

    remove(button, isEdit = false) {
        const row = button.closest('.item-row');
        $(row).fadeOut(300, function() {
            $(this).remove();
            itemRow.updateSummary(isEdit);
        });
    },

    getFormData(isEdit = false) {
        const prefix = isEdit ? 'edit_' : '';
        const items = [];

        $(`.${prefix}product-select`).each(function() {
            const $this = $(this);
            const index = $this.data('index');
            const productId = $this.val();
            const qty = parseInt($(`.${prefix}qty-input.qty-input-${index}`).val()) || 0;

            if (productId && qty > 0) {
                items.push({
                    product_id: parseInt(productId),
                    qty: qty
                });
            }
        });

        return items;
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
        cells[1].textContent = data.created_at;
        cells[2].textContent = data.customer;
        cells[3].textContent = data.items_count;
        cells[4].textContent = data.total_items;
        cells[5].textContent = data.total_points;
        cells[6].textContent = data.total_price;

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
        $(SELECTORS.fields.itemsContainer).empty();
        itemCounter = 0;

        itemRow.create(SELECTORS.fields.itemsContainer);

        $(SELECTORS.fields.summaryTotalItems).text('0');
        $(SELECTORS.fields.summaryTotalPoints).text('0');

        if (typeof open_orderCreateModal === 'function') open_orderCreateModal();
    },

    async onView(e) {
        const id = e.detail.id;

        $(SELECTORS.fields.orderViewContent).html(`
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
        `);

        if (typeof open_orderViewModal === 'function') {
            open_orderViewModal();
        }

        try {
            const response = await api.show(id);

            if (response.success) {
                const order = response.data;

                let itemsHTML = order.items.map(item => `
                    <div class="grid grid-cols-12 gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="col-span-6">
                            <p class="text-sm text-gray-600 font-poppins">Product</p>
                            <p class="font-medium font-poppins">${item.product_name}</p>
                            <p class="text-xs text-gray-500 font-poppins">SKU: ${item.sku}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600 font-poppins">Qty</p>
                            <p class="font-medium font-poppins">${item.qty}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600 font-poppins">Points/Unit</p>
                            <p class="font-medium font-poppins">${utils.formatNumber(item.points_per_unit)}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-600 font-poppins">Total</p>
                            <p class="font-semibold text-blue-600 font-poppins">${utils.formatNumber(item.total_points)}</p>
                        </div>
                    </div>
                `).join('');

                const content = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 font-poppins">Order ID</p>
                                <p class="font-semibold font-poppins">${order.order_id}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 font-poppins">Date</p>
                                <p class="font-medium font-poppins">${order.created_at}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 font-poppins">Customer</p>
                            <p class="font-semibold font-poppins">${order.customer_name}</p>
                        </div>

                        ${order.notes ? `
                        <div>
                            <p class="text-sm text-gray-600 font-poppins">Notes</p>
                            <p class="text-gray-700 font-poppins">${order.notes}</p>
                        </div>
                        ` : ''}

                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-3 font-poppins">Items</p>
                            <div class="space-y-2">
                                ${itemsHTML}
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 font-poppins">Total Items (Qty)</p>
                                    <p class="text-2xl font-semibold text-gray-900 font-poppins">${utils.formatNumber(order.total_items)}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 font-poppins">Total Points</p>
                                    <p class="text-2xl font-semibold text-blue-600 font-poppins">${utils.formatNumber(order.total_points)}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 font-poppins">Total Price</p>
                                    <p class="text-2xl font-semibold text-blue-600 font-poppins">${utils.formatNumber(order.total_price)}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $(SELECTORS.fields.orderViewContent).html(content);

            } else {
                ui.showNotification('error', response.message || 'Gagal memuat detail order');
                if (typeof close_orderViewModal === 'function') {
                    close_orderViewModal();
                }
            }
        } catch (error) {
            console.error('View error:', error);
            ui.showNotification('error', 'Gagal memuat detail order');
            if (typeof close_orderViewModal === 'function') {
                close_orderViewModal();
            }
        }
    },

    async onEdit(e) {
        const id = e.detail.id;

        try {
            const response = await api.show(id);

            if (response.success) {
                const order = response.data;

                $(SELECTORS.fields.editId).val(order.id);
                $(SELECTORS.fields.editOrderId).val(order.order_id);
                $(SELECTORS.fields.editCustomerSelect).val(order.customer_id).trigger('change');
                $(SELECTORS.fields.editNotes).val(order.notes || '');

                $(SELECTORS.fields.editItemsContainer).empty();
                editItemCounter = 0;

                order.items.forEach(item => {
                    const row = itemRow.create(SELECTORS.fields.editItemsContainer, true);
                    const index = editItemCounter;

                    setTimeout(() => {
                        $(`.edit_product-select.product-select-${index}`).val(item.product_id).trigger('change');
                        $(`.edit_qty-input.qty-input-${index}`).val(item.qty);
                        itemRow.calculateItemTotal(index, true);
                    }, 100);
                });

                if (typeof open_orderEditModal === 'function') open_orderEditModal();
            }
        } catch (error) {
            console.error('Edit load error:', error);
            ui.showNotification('error', 'Gagal memuat data order');
        }
    },

    onDelete(e) {
        const id = e.detail.id;
        const row = utils.getElement(SELECTORS.table.row(id));
        if (!row) return;

        const orderId = row.querySelector('td:first-child');
        if (!orderId) return;

        utils.getElement(SELECTORS.fields.deleteId).value = id;
        const deleteOrderIdEl = utils.getElement(SELECTORS.fields.deleteOrderId);
        if (deleteOrderIdEl) deleteOrderIdEl.textContent = orderId.textContent.trim();

        if (typeof open_orderDeleteModal === 'function') open_orderDeleteModal();
    },

    async onCreate() {
        try {
            const customerId = $(SELECTORS.fields.customerSelect).val();
            const notes = $(SELECTORS.fields.notes).val();
            const items = itemRow.getFormData(false);

            if (!customerId) {
                ui.showNotification('error', 'Customer harus dipilih');
                return;
            }

            if (items.length === 0) {
                ui.showNotification('error', 'Minimal 1 produk harus dipilih');
                return;
            }

            const data = await api.create({
                customer_id: parseInt(customerId),
                notes: notes || null,
                items: items
            });

            if (!data.success) {
                ui.showNotification('error', data.message);
                return;
            }

            // CRITICAL FIX: Close everything and wait for complete cleanup
            // 1. Destroy all Select2 instances completely
            $('.select2-hidden-accessible').each(function() {
                $(this).select2('destroy');
            });
            
            // 2. Close modal
            if (typeof close_orderCreateModal === 'function') {
                close_orderCreateModal();
            }

            // 3. Wait for modal to completely disappear AND re-initialize Select2
            await new Promise(resolve => setTimeout(resolve, 400));
            
            // 4. Re-initialize Select2 for next use
            $(SELECTORS.fields.customerSelect).select2({
                placeholder: 'Select customer',
                allowClear: false,
                width: '100%',
                dropdownParent: $(SELECTORS.modals.create)
            });

            // 5. Force table reflow before adding row
            const tableContainer = document.querySelector('.table-responsive, table');
            if (tableContainer) {
                tableContainer.style.display = 'none';
                tableContainer.offsetHeight; // Force reflow
                tableContainer.style.display = '';
            }

            // 6. Now add the row
            await table.addRow(data.data);

            // 7. Show notification
            ui.showNotification('success', data.message);

        } catch (error) {
            console.error('Create error:', error);
            ui.showNotification('error', error.message || 'Terjadi kesalahan');
        }
    },

    async onEditSubmit() {
        try {
            const id = utils.getElement(SELECTORS.fields.editId).value;
            const customerId = $(SELECTORS.fields.editCustomerSelect).val();
            const notes = $(SELECTORS.fields.editNotes).val();
            const items = itemRow.getFormData(true);

            if (!customerId) {
                ui.showNotification('error', 'Customer harus dipilih');
                return;
            }

            if (items.length === 0) {
                ui.showNotification('error', 'Minimal 1 produk harus dipilih');
                return;
            }

            const data = await api.update(id, {
                customer_id: parseInt(customerId),
                notes: notes || null,
                items: items
            });

            if (data.success) {
                $('.select2-hidden-accessible').select2('close');
                
                if (typeof close_orderEditModal === 'function') close_orderEditModal();
                
                await new Promise(resolve => setTimeout(resolve, 300));
                
                await table.updateRow(id, data.data);
                
                ui.showNotification('success', data.message);
            } else {
                ui.showNotification('error', data.message || 'Gagal mengupdate order');
            }
        } catch (error) {
            console.error('Update error:', error);
            ui.showNotification('error', error.message || 'Terjadi kesalahan');
        }
    },

    async onDeleteSubmit() {
        const id = utils.getElement(SELECTORS.fields.deleteId).value;

        try {
            const data = await api.delete(id);

            if (data.success) {
                if (typeof close_orderDeleteModal === 'function') close_orderDeleteModal();
                
                await new Promise(resolve => setTimeout(resolve, 300));
                
                await table.removeRow(id);
                
                ui.showNotification('success', data.message);
            } else {
                ui.showNotification('error', data.message || 'Gagal menghapus order');
            }
        } catch (error) {
            console.error('Delete error:', error);
            ui.showNotification('error', error.message || 'Terjadi kesalahan');
        }
    }
};

// ==================== AUTO-SEARCH FROM URL ====================
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get('search');

    if (searchQuery) {
        // Wait for table to be ready
        setTimeout(() => {
            const searchInput = document.querySelector('.search-input');

            if (searchInput) {
                searchInput.value = searchQuery;
                searchInput.focus();

                // Trigger search event
                searchInput.dispatchEvent(new Event('input', { bubbles: true }));

                // Visual feedback
                searchInput.classList.add('ring-2', 'ring-blue-500');
                setTimeout(() => {
                    searchInput.classList.remove('ring-2', 'ring-blue-500');
                }, 2000);

                // Calculate and show Total Belanja
                searchSummary.calculate(searchQuery);
            }
        }, 100);
    }
});

// ==================== INITIALIZATION ====================
$(document).ready(function() {
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

    // Add Item Button handlers
    $(SELECTORS.fields.addItemBtn).on('click', function() {
        itemRow.create(SELECTORS.fields.itemsContainer, false);
    });

    $(SELECTORS.fields.editAddItemBtn).on('click', function() {
        itemRow.create(SELECTORS.fields.editItemsContainer, true);
    });
// Search input handler for Total Belanja
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchSummary.calculate(this.value);
        });
        
        // Clear button handler
        const clearButton = searchInput.parentElement.querySelector('button');
        if (clearButton) {
            clearButton.addEventListener('click', function() {
                searchSummary.hide();
            });
        }
    }
 // Table update handler
    const tableContainer = utils.getElement(SELECTORS.table.container);
    if (tableContainer) {
        tableContainer.addEventListener('table:updated', function() {
            const searchInput = this.querySelector(SELECTORS.table.searchInput);
            if (searchInput && searchInput.value) {
                searchSummary.calculate(searchInput.value);
            }
        });
    }

    // Remove Item Button handler
    $(document).on('click', '.remove-item-btn', function() {
        const isEdit = $(this).closest('#editItemsContainer').length > 0;
        itemRow.remove(this, isEdit);
    });

    // Register event listeners
    document.addEventListener('table:add', handlers.onAdd);
    document.addEventListener('table:view', handlers.onView);
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

// ==================== SEARCH SUMMARY ====================
const searchSummary = {
    calculate(searchQuery) {
        if (!searchQuery || searchQuery.trim() === '') {
            this.hide();
            return;
        }

        const query = searchQuery.toLowerCase().trim();
        const tableRows = document.querySelectorAll('.table-row[data-search]');
        
        let totalPrice = 0;
        let matchedCustomer = '';
        let hasMatches = false;

        tableRows.forEach(row => {
            const searchData = row.getAttribute('data-search');
            
            if (searchData && searchData.includes(query)) {
                hasMatches = true;
                
                // Extract customer name from search data
                if (!matchedCustomer) {
                    const customerCell = row.querySelector('td:nth-child(3)');
                    if (customerCell) {
                        matchedCustomer = customerCell.textContent.trim();
                    }
                }
                
                // Extract total price from row
                const priceCell = row.querySelector('td:nth-child(7)');
                if (priceCell) {
                    const priceText = priceCell.textContent.trim();
                    const cleanPrice = priceText.replace(/[^\d]/g, '');
                    totalPrice += parseInt(cleanPrice) || 0;
                }
            }
        });

        if (hasMatches) {
            this.show(matchedCustomer, totalPrice);
        } else {
            this.hide();
        }
    },

    show(customerName, totalAmount) {
        const card = utils.getElement(SELECTORS.summary.card);
        const nameEl = utils.getElement(SELECTORS.summary.customerName);
        const amountEl = utils.getElement(SELECTORS.summary.amount);

        if (card && nameEl && amountEl) {
            nameEl.textContent = customerName;
            amountEl.textContent = utils.formatCurrency(totalAmount);
            
            card.classList.remove('hidden');
            card.style.animation = 'slideInRight 0.4s ease-out';
        }
    },

    hide() {
        const card = utils.getElement(SELECTORS.summary.card);
        if (card) {
            card.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => {
                card.classList.add('hidden');
            }, 300);
        }
    }
};