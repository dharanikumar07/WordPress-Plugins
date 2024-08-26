jQuery(document).ready(function ($) {

    // Handle a form submission
    $('#bxgx_product_form').on('submit', function (e) {
        e.preventDefault();

        var selectedProducts = [];
        $('#selected_products .bxgx-product').each(function () {
            selectedProducts.push($(this).data('product-id'));
        });

        if (selectedProducts.length === 0) {
            alert(bxgx_ajax.selectProductMessage);
            return;
        }

        $.ajax({
            url: bxgx_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'save_selected_products',
                products: selectedProducts,
                nonce: bxgx_ajax.nonce
            },
            success: function (response) {
                if (response.success) {
                    alert(response.data.message);
                } else {
                    alert(response.data.error);
                }
            },
            error: function () {
                alert('Request failed.');
            }
        });
    });

    // Handle product search and selection
    $('#bxgx_product_search').on('keyup', function () {
        let searchQuery = $(this).val();

        if (searchQuery.length < 2) {
            $('#bxgx_product_dropdown').html('<li class="list-group-item dropdown-message">' + bxgx_ajax.startTypingMessage + '</li>');
            return;
        }

        $.ajax({
            url: bxgx_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'bxgx_search_products',
                search_query: searchQuery,
                nonce: bxgx_ajax.nonce
            },
            success: function (response) {
                if (response.trim() === '') {
                    $('#bxgx_product_dropdown').html('<li class="list-group-item dropdown-message">' + bxgx_ajax.noProductsFoundMessage + '</li>');
                } else {
                    $('#bxgx_product_dropdown').html(response);
                }
            },
            error: function () {
                $('#bxgx_product_dropdown').html('<li class="list-group-item dropdown-message">' + bxgx_ajax.requestFailedMessage + '</li>');
            }
        });
    });

    // Handle product item selection
    $('#bxgx_product_dropdown').on('click', 'li', function () {
        var productId = $(this).data('id');
        var productName = $(this).data('name');

        // Ensure the clicked item has a valid data-id attribute
        if (productId && productName) {
            var selectedProductHtml = '<span class="bxgx-product" data-product-id="' + productId + '">' + productName + ' <a href="#" class="bxgx-remove-product">x</a></span>';
            $('#selected_products').append(selectedProductHtml);
            $('#bxgx_product_dropdown').empty(); // Clear the dropdown after selection
        }
    });

    $('#selected_products').on('click', '.bxgx-remove-product', function (e) {
        e.preventDefault();
        $(this).parent().remove();
    });
});
