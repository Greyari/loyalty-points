// ==================== CONSTANTS ====================
const SELECTORS = {
    modals: {
        create: '#userCreateModal',
        edit: '#userEditModal',
        delete: '#userDeleteModal'
    },
    forms: {
        create: '#createForm',
        edit: '#editForm'
    },
    fields: {
        editId: '#edit_id',
        editName: '#edit_name',
        editEmail: '#edit_email',
        editRole: '#edit_role',
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
    user: '/user',
    userItem: (id) => `/user/${id}`
};

// ==================== UTILITY FUNCTIONS ====================
const utils = {
    getElement: (selector) => document.querySelector(selector),
    getCsrfToken: () => document.querySelector('meta[name="csrf-token"]').content,
    createSearchData: (data) => `${data.name} ${data.email} ${data.role}`.toLowerCase()
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
        return this.request(API_ENDPOINTS.user, {
            method: 'POST',
            body: formData
        });
    },

    update(id, formData) {
        formData.append('_method', 'PUT');
        return this.request(API_ENDPOINTS.userItem(id), {
            method: 'POST',
            body: formData
        });
    },

    delete(id) {
        return this.request(API_ENDPOINTS.userItem(id), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ _method: 'DELETE', _token: utils.getCsrfToken() })
        });
    }
};

// ==================== UI FUNCTIONS ====================
const ui = {
    showNotification(type, message) {
        document.querySelectorAll('.user-notification').forEach(n => n.remove());
        const config = {
            success: { color: 'green', icon: 'M5 13l4 4L19 7' },
            error: { color: 'red', icon: 'M6 18L18 6M6 6l12 12' },
            warning: { color: 'orange', icon: 'M12 9v4m0 4h.01' }
        };
        const { color, icon } = config[type];
        const notification = document.createElement('div');
        notification.className = 'user-notification fixed bottom-4 right-4 flex items-center w-full max-w-sm p-4 rounded-xl shadow-lg bg-white z-[9999]';
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
            <td class="py-4 px-2">${data.name}</td>
            <td class="py-4 px-2">${data.email}</td>
            <td class="py-4 px-2">${data.role}</td>
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
    },

    async updateRow(id, formData) {
        const row = utils.getElement(SELECTORS.table.row(id));
        if (!row) return;

        const data = {
            name: formData.get('name'),
            email: formData.get('email'),
            role: formData.get('role'),
        };

        row.setAttribute('data-search', utils.createSearchData(data));

        const cells = row.querySelectorAll('td');
        cells[0].textContent = data.name;
        cells[1].textContent = data.email;
        cells[2].textContent = data.role;

        await ui.animate(row, { backgroundColor: '#dbeafe' }, 100);
        await ui.animate(row, { transition: 'background-color 0.5s ease', backgroundColor: '' });
    },

    async removeRow(id) {
        const row = utils.getElement(SELECTORS.table.row(id));
        if (!row) return;

        await ui.animate(row, { transition: 'opacity 0.3s ease-out', opacity: '0' });
        row.remove();
    }
};

// ==================== EVENT HANDLERS ====================
const handlers = {
    onAdd() {
        const form = utils.getElement(SELECTORS.forms.create);
        if (form) form.reset();
        if (typeof open_userCreateModal === 'function') open_userCreateModal();
    },

    onEdit(e) {
        const id = e.detail.id;
        const row = utils.getElement(SELECTORS.table.row(id));
        if (!row) return;

        const cells = row.querySelectorAll('td');
        utils.getElement(SELECTORS.fields.editId).value    = id;
        utils.getElement(SELECTORS.fields.editName).value  = cells[0].textContent.trim();
        utils.getElement(SELECTORS.fields.editEmail).value = cells[1].textContent.trim();
        utils.getElement(SELECTORS.fields.editRole).value  = cells[2].textContent.trim();

        if (typeof open_userEditModal === 'function') open_userEditModal();
    },

    onDelete(e) {
        const id = e.detail.id;
        const row = utils.getElement(SELECTORS.table.row(id));
        if (!row) return;

        const nameCell = row.querySelector('td:first-child');
        utils.getElement(SELECTORS.fields.deleteId).value = id;
        utils.getElement(SELECTORS.fields.deleteName).textContent = nameCell.textContent.trim();

        if (typeof open_userDeleteModal === 'function') open_userDeleteModal();
    },

    async onCreate() {
        const form = utils.getElement(SELECTORS.forms.create);
        if (!form) return;

        try {
            const data = await api.create(new FormData(form));

            if (data.success) {
                if (typeof close_userCreateModal === 'function') close_userCreateModal();
                await table.addRow(data.data);
                ui.showNotification('success', data.message);
                form.reset();
            } else {
                ui.showNotification('error', data.message || 'Gagal menambahkan user');
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
                if (typeof close_userEditModal === 'function') close_userEditModal();
                await table.updateRow(id, formData);
                ui.showNotification('success', data.message);
            } else {
                ui.showNotification('error', data.message || 'Gagal mengupdate user');
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
                if (typeof close_userDeleteModal === 'function') close_userDeleteModal();
                await table.removeRow(id);
                ui.showNotification('success', data.message);
            } else {
                ui.showNotification('error', data.message || 'Gagal menghapus user');
            }
        } catch (error) {
            console.error('❌ Delete error:', error);
            ui.showNotification('error', error.message || 'Terjadi kesalahan');
        }
    }
};

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', () => {
    const modals = Object.values(SELECTORS.modals).map(s => utils.getElement(s));
    if (modals.some(m => !m)) return;

    console.log('✅ user module loaded');

    document.addEventListener('table:add', handlers.onAdd);
    document.addEventListener('table:edit', handlers.onEdit);
    document.addEventListener('table:delete', handlers.onDelete);

    utils.getElement(SELECTORS.modals.create).addEventListener('modal:submit', handlers.onCreate);
    utils.getElement(SELECTORS.modals.edit).addEventListener('modal:submit', handlers.onEditSubmit);
    utils.getElement(SELECTORS.modals.delete).addEventListener('modal:submit', handlers.onDeleteSubmit);
});
