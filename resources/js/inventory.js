// ==================== CONSTANTS ====================
const SELECTORS = {
    modals: {
        create: '#productCreateModal',
        edit: '#productEditModal',
        delete: '#productDeleteModal'
    },

    forms: {
        create: '#createForm',
        edit: '#editForm'
    },
    fields: {
        editId: '#edit_id',
        editName: '#edit_name',
        editSku: '#edit_sku',
        editQuantity: '#edit_quantity',
        editPrice: '#edit_price',
        editPoints: '#edit_points_per_unit',
        deleteId: '#delete_id',
        deleteName: '#delete_name'
    },
    table: {
        body: '.table-body',
        row: (id) => `tr[data-id="${id}"]`,
        container: '[id^="table_"]',
        searchInput: '.search-input'
    }
};

const API_ENDPOINTS = {
    inventory: '/inventory',
    inventoryItem: (id) => `/inventory/${id}`
};

// ==================== UTILITY FUNCTIONS ====================
const utils = {
    // Get element safely
    getElement: (selector) => document.querySelector(selector),

    // Format price to Indonesian Rupiah
    formatPrice: (price) => `Rp ${parseInt(price).toLocaleString('id-ID')}`,

    // Parse price from formatted string
    parsePrice: (priceText) => priceText.replace(/Rp\s*/g, '').replace(/\./g, '').replace(/,/g, ''),

    // Get CSRF token
    getCsrfToken: () => document.querySelector('meta[name="csrf-token"]').content,

    // Create search data attribute
    createSearchData: (data) => `${data.name} ${data.sku} ${data.quantity} ${data.price} ${data.points}`.toLowerCase()
};

// ==================== API FUNCTIONS ====================
const api = {
    // Generic fetch wrapper
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

    // Create product
    create(formData) {
        return this.request(API_ENDPOINTS.inventory, {
            method: 'POST',
            body: formData
        });
    },

    // Update product
    update(id, formData) {
        formData.append('_method', 'PUT');
        return this.request(API_ENDPOINTS.inventoryItem(id), {
            method: 'POST',
            body: formData
        });
    },

    // Delete product
    delete(id) {
        return this.request(API_ENDPOINTS.inventoryItem(id), {
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
    // Show notification
    showNotification(type, message) {
        // Remove existing notifications
        document.querySelectorAll('.inventory-notification').forEach(n => n.remove());

        const config = {
            success: { color: 'green', icon: 'M5 13l4 4L19 7' },
            error: { color: 'red', icon: 'M6 18L18 6M6 6l12 12' },
            warning: { color: 'orange', icon: 'M12 9v4m0 4h.01' }
        };

        const { color, icon } = config[type];
        const notification = document.createElement('div');
        notification.className = 'inventory-notification fixed bottom-4 right-4 flex items-center w-full max-w-sm p-4 rounded-xl shadow-lg bg-white z-[9999]';
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

    // Create table row HTML
    createRowHTML(data) {
        const formattedPrice = utils.formatPrice(data.price);
        return `
            <td class="py-4 px-2 text-sm text-gray-800">${data.name}</td>
            <td class="py-4 px-2 text-sm text-gray-800">${data.sku}</td>
            <td class="py-4 px-2 text-sm text-gray-800">${data.points_per_unit}</td>
            <td class="py-2 px-2 text-sm text-gray-800">
                <div class="flex justify-center items-center gap-2">
                    <button class="edit-btn text-blue-600 hover:text-blue-800 transition-colors" data-id="${data.id}" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                    </button>
                    <button class="delete-btn text-red-600 hover:text-red-800 transition-colors" data-id="${data.id}" title="Delete">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                    </button>
                </div>
            </td>
        `;
    },

    // Animate element
    animate(element, styles, duration = 300) {
        Object.assign(element.style, styles);
        return new Promise(resolve => setTimeout(resolve, duration));
    }
};

// ==================== TABLE OPERATIONS ====================
const table = {
    // Add new row
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

    // Update existing row
    async updateRow(id, formData) {
        const row = utils.getElement(SELECTORS.table.row(id));
        if (!row) return;

        const data = {
            name: formData.get('name'),
            sku: formData.get('sku'),
            points_per_unit: formData.get('points_per_unit')
        };

        row.setAttribute('data-search', utils.createSearchData(data));

        const cells = row.querySelectorAll('td');
        cells[0].textContent = data.name;
        cells[1].textContent = data.sku;
        cells[4].textContent = data.points_per_unit;

        await ui.animate(row, { backgroundColor: '#dbeafe' }, 100);
        await ui.animate(row, { transition: 'background-color 0.5s ease', backgroundColor: '' });
    },

    // Remove row
    async removeRow(id) {
        const row = utils.getElement(SELECTORS.table.row(id));
        if (!row) return;

        await ui.animate(row, { transition: 'opacity 0.3s ease-out', opacity: '0' });
        row.remove();
        this.updateInfo();
    },

    // Update table info and pagination
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
    // Handle add button
    onAdd() {
        const form = utils.getElement(SELECTORS.forms.create);
        if (form) form.reset();
        if (typeof open_productCreateModal === 'function') open_productCreateModal();
    },

    // Handle edit button
    onEdit(e) {
        const id = e.detail.id;
        const row = utils.getElement(SELECTORS.table.row(id));
        if (!row || !utils.getElement(SELECTORS.fields.editId)) return;

        const cells = row.querySelectorAll('td');
        if (cells.length < 5) return;

        utils.getElement(SELECTORS.fields.editId).value = id;
        utils.getElement(SELECTORS.fields.editName).value = cells[0].textContent.trim();
        utils.getElement(SELECTORS.fields.editSku).value = cells[1].textContent.trim();
        utils.getElement(SELECTORS.fields.editQuantity).value = cells[2].textContent.trim();
        utils.getElement(SELECTORS.fields.editPrice).value = utils.parsePrice(cells[3].textContent.trim());
        utils.getElement(SELECTORS.fields.editPoints).value = cells[4].textContent.trim();

        if (typeof open_productEditModal === 'function') open_productEditModal();
    },
    // Handle delete button
    onDelete(e) {
        const id = e.detail.id;
        const row = utils.getElement(SELECTORS.table.row(id));
        if (!row || !utils.getElement(SELECTORS.fields.deleteId)) return;

        const productName = row.querySelector('td:first-child');
        if (!productName) return;

        utils.getElement(SELECTORS.fields.deleteId).value = id;
        const deleteNameEl = utils.getElement(SELECTORS.fields.deleteName);
        if (deleteNameEl) deleteNameEl.textContent = productName.textContent.trim();

        if (typeof open_productDeleteModal === 'function') open_productDeleteModal();
    },

    // Handle create submission
    async onCreate() {
        const form = utils.getElement(SELECTORS.forms.create);
        if (!form) return;

        try {
            const data = await api.create(new FormData(form));

            if (data.success) {
                if (typeof close_productCreateModal === 'function') close_productCreateModal();
                await table.addRow(data.data);
                ui.showNotification('success', data.message);
                form.reset();
            } else {
                ui.showNotification('error', data.message || 'Gagal menambahkan produk');
            }
        } catch (error) {
            console.error('❌ Create error:', error);
            ui.showNotification('error', error.message || 'Terjadi kesalahan');
        }
    },

    // Handle edit submission
    async onEditSubmit() {
        const form = utils.getElement(SELECTORS.forms.edit);
        if (!form) return;

        const formData = new FormData(form);
        const id = utils.getElement(SELECTORS.fields.editId).value;

        try {
            const data = await api.update(id, formData);

            if (data.success) {
                if (typeof close_productEditModal === 'function') close_productEditModal();
                await table.updateRow(id, formData);
                ui.showNotification('success', data.message);
            } else {
                ui.showNotification('error', data.message || 'Gagal mengupdate produk');
            }
        } catch (error) {
            console.error('❌ Update error:', error);
            ui.showNotification('error', error.message || 'Terjadi kesalahan');
        }
    },

    // Handle delete submission
    async onDeleteSubmit() {
        const id = utils.getElement(SELECTORS.fields.deleteId).value;

        try {
            const data = await api.delete(id);

            if (data.success) {
                if (typeof close_productDeleteModal === 'function') close_productDeleteModal();
                await table.removeRow(id);
                ui.showNotification('success', data.message);
            } else {
                ui.showNotification('error', data.message || 'Gagal menghapus produk');
            }
        } catch (error) {
            console.error('❌ Delete error:', error);
            ui.showNotification('error', error.message || 'Terjadi kesalahan');
        }
    }
};

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', () => {
    // Check if inventory page
    const modals = Object.values(SELECTORS.modals).map(s => utils.getElement(s));
    if (modals.some(m => !m)) return;

    console.log('✅ Inventory module loaded');

    // Register event listeners
    document.addEventListener('table:add', handlers.onAdd);
    document.addEventListener('table:edit', handlers.onEdit);
    document.addEventListener('table:delete', handlers.onDelete);

    utils.getElement(SELECTORS.modals.create).addEventListener('modal:submit', handlers.onCreate);
    utils.getElement(SELECTORS.modals.edit).addEventListener('modal:submit', handlers.onEditSubmit);
    utils.getElement(SELECTORS.modals.delete).addEventListener('modal:submit', handlers.onDeleteSubmit);
});
