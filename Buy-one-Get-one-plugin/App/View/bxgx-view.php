<?php
namespace BXGX\View;

use BXGX\App\Model\Model;

// Retrieve a selected products with meta value 'yes'
$selected_products = Model::getSelectedProducts();
?>

    <form id="bxgx_product_form" method="POST" action="">

        <div id="bxgx_product_data" class="panel woocommerce_options_panel">
            <div class="options_group">
                <label for="bxgx_product_search"><?php esc_html_e('Select the products for buy one get one offer', 'woocommerce'); ?></label>

                <!-- Search bar -->
                <div class="form-group">
                    <input type="text" id="bxgx_product_search" class="form-control search" placeholder="<?php esc_attr_e('Search products...', 'woocommerce'); ?>">
                </div>

                <!-- Dropdown list -->
                <ul id="bxgx_product_dropdown" class="list-group list">
                    <li class="list-group-item"><?php esc_html_e('Start typing to search for products...', 'woocommerce'); ?></li>
                </ul>

                <!-- Selected products list -->
                <div id="selected_products" class="selected-products">
                    <?php
                    if (!empty($selected_products)) {
                        foreach ($selected_products as $selected_product_id) {
                            $selected_product_name = get_the_title($selected_product_id);
                            echo '<span class="bxgx-product" data-product-id="' . esc_attr($selected_product_id) . '">' . esc_html($selected_product_name) . ' <a href="#" class="bxgx-remove-product">x</a></span>';
                        }
                    } else {
                        echo '<p>' . esc_html__('No products selected yet.', 'woocommerce') . '</p>';
                    }
                    ?>
                </div>

                <p class="description"><?php esc_html_e('Select products to apply buy one get one offer', 'woocommerce'); ?></p>

                <!-- Submit Button -->
                <button type="submit" class="button button-primary"><?php esc_html_e('Submit', 'woocommerce'); ?></button>
            </div>
        </div>
    </form>

    <?php wp_reset_postdata(); ?>