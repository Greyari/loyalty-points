    document.addEventListener('DOMContentLoaded', function() {

        // Mock data untuk demo
        const productData = {
            1: {
                product_name: 'Dahua',
                sku: '1233223',
                category: 'CCTV',
                quantity: 21,
                price: '12.000.000',
                status: 'Available'
            },
            2: {
                product_name: 'Hikvision',
                sku: '0882-223',
                category: 'CCTV',
                quantity: 210,
                price: '30.000.000',
                status: 'Available'
            }
        };

        // Listen to table add event
        document.addEventListener('table:add', function(e) {
            document.getElementById('createForm').reset();
            open_createModal();
        });

        // Listen to table edit event
        document.addEventListener('table:edit', function(e) {
            const id = e.detail.id;
            const data = productData[id];

            if (data) {
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_product_name').value = data.product_name;
                document.getElementById('edit_sku').value = data.sku;
                document.getElementById('edit_category').value = data.category;
                document.getElementById('edit_quantity').value = data.quantity;
                document.getElementById('edit_price').value = data.price;
                document.getElementById('edit_status').value = data.status;
            }

            open_editModal();
        });

        // Listen to table delete event
        document.addEventListener('table:delete', function(e) {
            const id = e.detail.id;
            const data = productData[id];

            document.getElementById('delete_id').value = id;
            if (data) {
                document.getElementById('delete_product_name').textContent = data.product_name + ' (' + data.sku + ')';
            }

            open_deleteModal();
        });

        // Handle create submission
        document.getElementById('createModal').addEventListener('modal:submit', function(e) {
            const formData = new FormData(document.getElementById('createForm'));
            const data = Object.fromEntries(formData);

            console.log('Creating product:', data);

            // AJAX call here
            // fetch('/api/products', {
            //     method: 'POST',
            //     headers: { 'Content-Type': 'application/json' },
            //     body: JSON.stringify(data)
            // })

            close_createModal();
            alert('Product created successfully!');
        });

        // Handle edit submission
        document.getElementById('editModal').addEventListener('modal:submit', function(e) {
            const formData = new FormData(document.getElementById('editForm'));
            const data = Object.fromEntries(formData);

            console.log('Updating product:', data);

            // AJAX call here
            // fetch('/api/products/' + data.id, {
            //     method: 'PUT',
            //     headers: { 'Content-Type': 'application/json' },
            //     body: JSON.stringify(data)
            // })

            close_editModal();
            alert('Product updated successfully!');
        });

        // Handle delete submission
        document.getElementById('deleteModal').addEventListener('modal:submit', function(e) {
            const id = document.getElementById('delete_id').value;

            console.log('Deleting product:', id);

            // AJAX call here
            // fetch('/api/products/' + id, {
            //     method: 'DELETE'
            // })

            close_deleteModal();
            alert('Product deleted successfully!');
        });
    });