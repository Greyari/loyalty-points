
    // document.addEventListener('DOMContentLoaded', function() {

    //     // Mock data untuk demo
    //     const transactionData = {
    //         1: {
    //             transaction_id: 'TXN-1001',
    //             date: '2025-12-01T09:30',
    //             customer: 'John Doe',
    //             total_items: 3,
    //             total_amount: 'Rp 1.500.000',
    //             payment_method: 'Cash',
    //             status: 'Completed',
    //             notes: 'First transaction of the day'
    //         },
    //         2: {
    //             transaction_id: 'TXN-1002',
    //             date: '2025-12-01T10:15',
    //             customer: 'Jane Smith',
    //             total_items: 2,
    //             total_amount: 'Rp 750.000',
    //             payment_method: 'Credit Card',
    //             status: 'Pending',
    //             notes: ''
    //         },
    //         3: {
    //             transaction_id: 'TXN-1003',
    //             date: '2025-12-01T11:00',
    //             customer: 'Michael Johnson',
    //             total_items: 5,
    //             total_amount: 'Rp 3.200.000',
    //             payment_method: 'Transfer',
    //             status: 'Completed',
    //             notes: 'Bulk purchase - corporate client'
    //         },
    //         4: {
    //             transaction_id: 'TXN-1004',
    //             date: '2025-12-01T11:45',
    //             customer: 'Sarah Williams',
    //             total_items: 1,
    //             total_amount: 'Rp 500.000',
    //             payment_method: 'Cash',
    //             status: 'Cancelled',
    //             notes: 'Customer requested refund'
    //         }
    //     };

    //     // Listen to table add event
    //     document.addEventListener('table:add', function(e) {
    //         document.getElementById('createForm').reset();
    //         open_createModal();
    //     });

    //     // Listen to table edit event
    //     document.addEventListener('table:edit', function(e) {
    //         const id = e.detail.id;
    //         const data = transactionData[id];

    //         if (data) {
    //             document.getElementById('edit_id').value = id;
    //             document.getElementById('edit_transaction_id').value = data.transaction_id;
    //             document.getElementById('edit_date').value = data.date;
    //             document.getElementById('edit_customer').value = data.customer;
    //             document.getElementById('edit_total_items').value = data.total_items;
    //             document.getElementById('edit_total_amount').value = data.total_amount;
    //             document.getElementById('edit_payment_method').value = data.payment_method;
    //             document.getElementById('edit_status').value = data.status;
    //             document.getElementById('edit_notes').value = data.notes;
    //         }

    //         open_editModal();
    //     });

    //     // Listen to table delete event
    //     document.addEventListener('table:delete', function(e) {
    //         const id = e.detail.id;
    //         const data = transactionData[id];

    //         document.getElementById('delete_id').value = id;
    //         if (data) {
    //             document.getElementById('delete_transaction_id').textContent = data.transaction_id;
    //         }

    //         open_deleteModal();
    //     });

    //     // Handle create submission
    //     document.getElementById('createModal').addEventListener('modal:submit', function(e) {
    //         const formData = new FormData(document.getElementById('createForm'));
    //         const data = Object.fromEntries(formData);

    //         console.log('Creating transaction:', data);

    //         // AJAX call here
    //         // fetch('/api/transactions', {
    //         //     method: 'POST',
    //         //     headers: { 'Content-Type': 'application/json' },
    //         //     body: JSON.stringify(data)
    //         // })
    //         // .then(response => response.json())
    //         // .then(result => {
    //         //     console.log('Success:', result);
    //         //     location.reload();
    //         // });

    //         close_createModal();
    //         alert('Transaction created successfully!');
    //     });

    //     // Handle edit submission
    //     document.getElementById('editModal').addEventListener('modal:submit', function(e) {
    //         const formData = new FormData(document.getElementById('editForm'));
    //         const data = Object.fromEntries(formData);

    //         console.log('Updating transaction:', data);

    //         // AJAX call here
    //         // fetch('/api/transactions/' + data.id, {
    //         //     method: 'PUT',
    //         //     headers: { 'Content-Type': 'application/json' },
    //         //     body: JSON.stringify(data)
    //         // })
    //         // .then(response => response.json())
    //         // .then(result => {
    //         //     console.log('Success:', result);
    //         //     location.reload();
    //         // });

    //         close_editModal();
    //         alert('Transaction updated successfully!');
    //     });

    //     // Handle delete submission
    //     document.getElementById('deleteModal').addEventListener('modal:submit', function(e) {
    //         const id = document.getElementById('delete_id').value;

    //         console.log('Deleting transaction:', id);

    //         // AJAX call here
    //         // fetch('/api/transactions/' + id, {
    //         //     method: 'DELETE'
    //         // })
    //         // .then(response => response.json())
    //         // .then(result => {
    //         //     console.log('Success:', result);
    //         //     location.reload();
    //         // });

    //         close_deleteModal();
    //         alert('Transaction deleted successfully!');
    //     });
    // });
