class DataTable {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        if (!this.container) return;

        this.searchInput = this.container.querySelector('.search-input');
        this.entriesSelect = this.container.querySelector('.entries-select');
        this.tbody = this.container.querySelector('.table-body');
        this.infoText = this.container.querySelector('.info-text');
        this.prevBtn = this.container.querySelector('.prev-btn');
        this.nextBtn = this.container.querySelector('.next-btn');
        this.paginationNumbers = this.container.querySelector('.page-numbers');
        this.addButton = this.container.querySelector('.add-button');

        this.currentPage = 1;
        this.entriesPerPage = parseInt(this.entriesSelect.value);
        this.allRows = Array.from(this.tbody.querySelectorAll('.table-row'));
        this.visibleRows = [...this.allRows];

        this.init();
    }

    init() {
        this.bindEvents();
        this.updateTable();
    }

    bindEvents() {
        this.searchInput.addEventListener('input', () => this.handleSearch());
        this.entriesSelect.addEventListener('change', () => this.handleEntriesChange());
        this.prevBtn.addEventListener('click', () => this.handlePrev());
        this.nextBtn.addEventListener('click', () => this.handleNext());

        if (this.addButton) {
            this.addButton.addEventListener('click', () => this.dispatchEvent('table:add'));
        }

        this.container.addEventListener('click', (e) => {
            const viewBtn = e.target.closest('.view-btn');
            const editBtn = e.target.closest('.edit-btn');
            const deleteBtn = e.target.closest('.delete-btn');

            if (viewBtn) {
                this.dispatchEvent('table:view', { id: viewBtn.dataset.id });
            }
            if (editBtn) {
                this.dispatchEvent('table:edit', { id: editBtn.dataset.id });
            }
            if (deleteBtn) {
                this.dispatchEvent('table:delete', { id: deleteBtn.dataset.id });
            }
        });
    }

    dispatchEvent(eventName, detail = {}) {
        const event = new CustomEvent(eventName, {
            bubbles: true,
            detail: { ...detail, componentId: this.container.id }
        });
        this.container.dispatchEvent(event);
    }

    handleSearch() {
        const query = this.searchInput.value.toLowerCase();
        this.visibleRows = query === ''
            ? [...this.allRows]
            : this.allRows.filter(row => row.dataset.search.includes(query));
        this.currentPage = 1;

        const noDataText = this.container.querySelector('.no-data-text');
        if (query !== '') {
            noDataText.textContent = `No data found for "${this.searchInput.value}"`;
        } else {
            noDataText.textContent = 'No data available';
        }
        
        this.updateTable();
    }

    handleEntriesChange() {
        this.entriesPerPage = parseInt(this.entriesSelect.value);
        this.currentPage = 1;
        this.updateTable();
    }

    handlePrev() {
        if (this.currentPage > 1) {
            this.currentPage--;
            this.updateTable();
        }
    }

    handleNext() {
        const totalPages = Math.ceil(this.visibleRows.length / this.entriesPerPage);
        if (this.currentPage < totalPages) {
            this.currentPage++;
            this.updateTable();
        }
    }

    renderPageNumbers() {
        this.paginationNumbers.innerHTML = '';
        const totalPages = Math.ceil(this.visibleRows.length / this.entriesPerPage) || 1;

        // Calculate page range to display (max 3 pages)
        let startPage = Math.max(1, this.currentPage - 1);
        let endPage = Math.min(totalPages, startPage + 2);

        // Adjust start if we're near the end
        if (endPage - startPage < 1) {
            startPage = Math.max(1, endPage - 2);
        }

        // Show first page if not in range
        if (startPage > 1) {
            this.createPageButton(1);
            if (startPage > 2) {
                this.createEllipsis();
            }
        }

        // Show page numbers in range
        for (let i = startPage; i <= endPage; i++) {
            this.createPageButton(i);
        }

        // Show last page if not in range
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                this.createEllipsis();
            }
            this.createPageButton(totalPages);
        }

        // Remove left border from first button
        if (this.paginationNumbers.firstChild) {
            this.paginationNumbers.firstChild.classList.add('border-l-0');
        }
    }

    createPageButton(pageNum) {
        const btn = document.createElement('button');
        btn.textContent = pageNum;
        btn.className = `px-3 py-1 border-t border-b border-l border-gray-300 text-sm ${
            pageNum === this.currentPage
                ? 'bg-blue-500 text-white'
                : 'bg-white text-gray-700 hover:bg-gray-100'
        }`;

        btn.addEventListener('click', () => {
            this.currentPage = pageNum;
            this.updateTable();
        });

        this.paginationNumbers.appendChild(btn);
    }

    createEllipsis() {
        const ellipsis = document.createElement('span');
        ellipsis.textContent = '...';
        ellipsis.className = 'px-3 py-1 border-t border-b border-l border-gray-300 text-sm bg-white text-gray-700';
        this.paginationNumbers.appendChild(ellipsis);
    }

    updateTable() {
        // Refresh all rows from DOM
        this.allRows = Array.from(this.tbody.querySelectorAll('.table-row'));
        
        // Reapply search filter if search is active
        const query = this.searchInput.value.toLowerCase();
        if (query !== '') {
            this.visibleRows = this.allRows.filter(row => row.dataset.search.includes(query));
        } else {
            this.visibleRows = [...this.allRows];
        }

        // Hide all rows initially
        this.allRows.forEach(row => row.style.display = 'none');

        const start = (this.currentPage - 1) * this.entriesPerPage;
        const end = start + this.entriesPerPage;
        const rowsToShow = this.visibleRows.slice(start, end);
        rowsToShow.forEach(row => row.style.display = '');

        const totalVisible = this.visibleRows.length;
        const showingStart = totalVisible > 0 ? start + 1 : 0;
        const showingEnd = Math.min(end, totalVisible);
        this.infoText.textContent = `Showing ${showingStart} to ${showingEnd} of ${totalVisible} entries`;

        const totalPages = Math.ceil(totalVisible / this.entriesPerPage) || 1;
        this.prevBtn.disabled = this.currentPage === 1;
        this.nextBtn.disabled = this.currentPage >= totalPages;

        const noDataRow = this.container.querySelector('.no-data-row');
        const noDataText = this.container.querySelector('.no-data-text');
        
        if (this.visibleRows.length === 0) {
            noDataRow.style.display = '';
            
            // Set appropriate message based on search state
            if (this.searchInput.value.trim() !== '') {
                noDataText.textContent = `No data found for "${this.searchInput.value}"`;
            } else {
                noDataText.textContent = 'No data available';
            }
            
            this.infoText.textContent = `Showing 0 to 0 of 0 entries`;
            this.paginationNumbers.innerHTML = '';
            this.prevBtn.disabled = true;
            this.nextBtn.disabled = true;
            return;
        } else {
            noDataRow.style.display = 'none';
        }

        this.renderPageNumbers();
    }

    // Method to refresh table after CRUD operations
    refresh() {
        this.updateTable();
    }
}

// Auto-initialize all tables
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id^="table_"]').forEach(table => {
        new DataTable(table.id);
    });
});