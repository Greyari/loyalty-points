$(document).ready(function() {
    $('#product_select').select2({
        placeholder: "Select product",
        width: '100%',
    });

    function updateQtyInputs() {
        let container = $('#qty_container');
        container.empty();

        $('#product_select').find(':selected').each(function() {
            let productId = $(this).val();
            let productName = $(this).text();
            let points = $(this).data('points');

            let qtyHtml = `
                <div class="grid grid-cols-2 gap-4 items-center">
                    <label class="font-medium">${productName} Qty:</label>
                    <input type="number" name="qtys[${productId}]" min="1" value="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-700">
                </div>
            `;
            container.append(qtyHtml);
        });

        updateTotalPoints();
    }

    function updateTotalPoints() {
        let total = 0;
        $('#product_select').find(':selected').each(function() {
            let productId = $(this).val();
            let points = $(this).data('points');
            let qty = $(`input[name='qtys[${productId}]']`).val() || 0;
            total += points * parseInt(qty);
        });

        $('#total_points_display').val(total);
    }

    // trigger when product selection changes
    $('#product_select').on('change', updateQtyInputs);

    // trigger when any qty input changes
    $(document).on('input', '#qty_container input[type="number"]', updateTotalPoints);
});

