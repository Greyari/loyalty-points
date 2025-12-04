    document.addEventListener("DOMContentLoaded", loadDashboard);

    function loadDashboard() {
        fetch("{{ route('dashboard.data') }}")
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    renderTopProducts(data.topProducts);
                    renderTopCustomers(data.topCustomers);
                }
            })
            .catch(err => console.error("Error:", err));
    }

    function renderTopProducts(products) {
        let container = document.getElementById("top-product-body");
        container.innerHTML = "";

        products.forEach((item, index) => {
            container.innerHTML += `
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">${index + 1}</td>
                    <td class="px-6 py-4">${item.product.sku}</td>
                    <td class="px-6 py-4">${item.product.name}</td>
                    <td class="px-6 py-4 text-center">${item.qty}</td>
                </tr>
            `;
        });
    }

    function renderTopCustomers(customers) {
        let container = document.getElementById("top-customer-body");
        container.innerHTML = "";

        customers.forEach((item, index) => {
            container.innerHTML += `
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">${index + 1}</td>
                    <td class="px-6 py-4">${item.customer.name}</td>
                    <td class="px-6 py-4 text-right">${item.points}</td>
                </tr>
            `;
        });
    }
