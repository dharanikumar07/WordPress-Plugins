document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('fbt-form').addEventListener('submit', function(event) {
        event.preventDefault();

        let product_ids = [];
        const checkboxes = document.querySelectorAll('input[name="fbt_product_ids[]"]:checked');

        checkboxes.forEach(function(checkbox) {
            product_ids.push(checkbox.value);
        });

        if (product_ids.length > 0) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', fbt_ajax.ajax_url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 400) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        toastr.success(response.data.message);
                    } else {
                        toastr.error(response.data.error);
                    }
                } else {
                    toastr.error("Something went wrong. Please try again.");
                }
            };

            xhr.onerror = function() {
                toastr.error("Something went wrong. Please try again.");
            };

            const formData = new FormData();
            formData.append('action', 'fbt_add_to_cart');
            formData.append('nonce', fbt_ajax.nonce);
            product_ids.forEach(function(id) {
                formData.append('product_ids[]', id);
            });

            xhr.send(formData);
        } else {
            toastr.warning(fbt_ajax.selectProductMessage);
        }
    });

});

//dropdown list

jQuery(document).ready(function($) {
    // Handle search input
    $('#fbt_product_search').on('keyup', function() {
        var searchText = $(this).val().toLowerCase();
        var hasResults = false;
        $('#fbt_product_dropdown').show(); // Show the list when typing

        $('#fbt_product_dropdown .list-group-item').each(function() {
            var productName = $(this).data('name').toLowerCase();
            if (productName.startsWith(searchText)) {
                $(this).show();
                hasResults = true;
            } else {
                $(this).hide();
            }
        });

        // Hide the list if there are no results
        if (!hasResults) {
            $('#fbt_product_dropdown').hide();
        }
    });

    // Handle product selection
    $('#fbt_product_dropdown').on('click', '.list-group-item', function() {
        var productId = $(this).data('id');
        var productName = $(this).data('name');
        var selectedProduct = `<div class="selected-product" data-id="${productId}">
                                        ${productName} <span class="remove-product">&times;</span>
                                        <input type="hidden" name="fbt_products[]" value="${productId}">
                                    </div>`;
        $('#selected_products').append(selectedProduct);
        $('#fbt_product_dropdown').hide();
        $('#fbt_product_search').val('');
    });

    // Handle product removal
    $('#selected_products').on('click', '.remove-product', function() {
        $(this).parent().remove();
    });

    // Hide dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#fbt_product_data').length) {
            $('#fbt_product_dropdown').hide();
        }
    });

    // Prevent dropdown from hiding when clicking inside
    $('#fbt_product_data').on('click', function(e) {
        e.stopPropagation();
    });
});

