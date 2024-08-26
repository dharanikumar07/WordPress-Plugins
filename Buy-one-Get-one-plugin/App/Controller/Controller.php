<?php
namespace BXGX\App\Controller;

use BXGX\App\Model\Model;

class Controller {

    /**
     * Function is used enqueue the scripts
     *
     * @return void
     */
    public static function enqueueScripts()
    {
        wp_enqueue_style('buyx-getx-style', BXGX_URL . 'assets/Css/customstyle.css');
        wp_enqueue_script('buyx-getx-script', BXGX_URL . 'assets/Js/script.js', ['jquery'], '1.0.0', true);
        wp_localize_script('buyx-getx-script', 'bxgx_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'startTypingMessage' => esc_html__('Start typing to search for products...', 'woocommerce'),
            'selectProductMessage' => esc_html__('Select at least one product.', 'BxgxPlugin'),
            'nonce' => wp_create_nonce('bxgx_nonce')
        ]);
    }

    /**
     * Function is used add the menu in admin dashboard
     *
     * @return void
     */
    public static function addMenu()
    {
        add_menu_page(
            'Buy-x Get-x',
            'Buy-x Get-x',
            'manage_options',
            'buyx-getx',
            [self::class, 'bxgxViewPage'],
            'dashicons-cart',
            6
        );
    }

    /**
     * Display the Buy-x and get-x view page
     *
     * @return void
     */
    public static function bxgxViewPage()
    {
        $template_path = 'bxgx-view.php';
        if (file_exists(BXGX_PATH . 'App/View/' . $template_path)) {
            wc_get_template($template_path, [], '', BXGX_PATH . 'App/View/');
        }
    }

    /**
     * Function is used to save the selected and unselected products
     *
     * @return void
     */
    public static function saveSelectedProducts()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'bxgx_nonce')) {
            wp_send_json_error(['message' => esc_html__('Nonce verification failed', 'buy-x-get-x')]);
            return;
        }
        $products = isset($_POST['products']) ? array_map('intval', $_POST['products']) : [];
        $all_products = Model::getProducts();
        foreach ($all_products as $product_id) {
            delete_post_meta($product_id, '_bxgx_products');
        }
        foreach ($products as $product_id) {
            update_post_meta($product_id, '_bxgx_products', 'yes');
        }
        wp_send_json_success(['message' => 'Products saved successfully.']);
    }

    /**
     * Function is used to display the buy-x get-x offer message in the product
     *
     * @return void
     */
    public static function displayMessage() {
        $template_path = BXGX_PATH . 'App/View/';
        wc_get_template('bxgx-message.php', [], '', $template_path);
    }

    /**
     * Function is used to add the free product to the cart for buy-x get-x products
     *
     * @param $cart_item_key - The cart item key for the main product.
     * @param $product_id - The ID of the product being added.
     * @param $quantity - The quantity of the product being added.
     * @return void
     * @throws \Exception
     */
    public static function addToCart($cart_item_key, $product_id, $quantity) {
        $cart = WC()->cart;
        if (get_post_meta($product_id, '_bxgx_products', true) === 'yes') {
            $free_product_id = $product_id;
            $found = false;
            foreach ($cart->get_cart() as $key => $values) {
                if ($values['product_id'] == $free_product_id && isset($values['is_free'])) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $cart->add_to_cart($free_product_id, $quantity, 0, [], ['is_free' => true, 'main_product_key' => $cart_item_key]);
            }
        }
    }

    /**
     * Set the price for the free products in the cart to zero.
     *
     * @param $cart
     * @return void
     */
    public static function setProductPrice($cart)
    {
        foreach ($cart->get_cart() as $cart_item) {
            if (isset($cart_item['is_free'])) {
                $cart_item['data']->set_price(0);
            }
        }
    }

    /**
     * Function is used to update the quantity of the free product when the main product quantity is changed.
     *
     * @param string $cart_item_key The cart item key for the main product.
     * @param int $quantity The new quantity of the main product.
     * @param int $old_quantity The old quantity of the main product.
     * @param WC_Cart $cart The WooCommerce cart object.
     * @return void
     */
    public static function updateProductQuantity($cart_item_key, $quantity, $old_quantity, $cart) {
        foreach ($cart->get_cart() as $key => $cart_item) {
            if (isset($cart_item['is_free']) && $cart_item['main_product_key'] == $cart_item_key) {
                WC()->cart->set_quantity($key, $quantity);
            }
        }
    }

    /**
     * Function is used to remove the free product when the main product is removed
     *
     * @param $cart_item_key
     * @param $cart
     * @return void
     * @throws \Exception
     */
    public static function removeFreeProductWhenMainProductRemoved($cart_item_key, $cart)
    {
            foreach ($cart->get_cart() as $key => $cart_item) {
                if (isset($cart_item['is_free']) && $cart_item['is_free'] == true) {
                    $cart->remove_cart_item($key);
                } else {
                    wc_add_notice(__('The free product cannot be deleted. It re-added to the cart.', 'bxgxPlugin'), 'error');
                    self::addToCart($cart_item_key, $cart_item['product_id'], $cart_item['quantity']);
                }
            }

    }


    /**
     * Display additional information for free products in the cart.
     *
     * @param array $item_data The item data array.
     * @param array $cart_item The cart item data.
     * @return array
     */
    public static function displayProductDetail($item_data, $cart_item)
    {
        if (isset($cart_item['is_free']) && $cart_item['is_free'] === true) {
            $item_data[] = [
                'key' => esc_html__('Buy Free', 'buy-x-get-x'),
                'value' => esc_html__('Free product from offer!!..', 'buy-x-get-x')
            ];
        }
        return $item_data;
    }

    /**
     * Function is used to search products and return the result as HTML list items.
     *
     * @return void
     */
    public static function searchProducts()
    {
        $products = Model::getProducts();

        if ($products) {
            foreach ($products as $product_id) {
                $product_name = get_the_title($product_id);
                echo '<li class="list-group-item" data-id="' . esc_attr($product_id) . '" data-name="' . esc_html($product_name) . '">' . esc_html($product_name) . '</li>';
            }
        } else {
            echo '<li class="list-group-item">' . esc_html__('No products found', 'bxgxPlugin') . '</li>';
        }
        wp_die();
    }


    /**
     * Function is used to disabled a free products delete button
     *
     * @param $remove_link
     * @param $cart_item_key
     * @return mixed|string
     */
    public static function disableRemoveButtonForFreeProducts($remove_link, $cart_item_key) {
        $cart_item = WC()->cart->get_cart_item($cart_item_key);
        if (isset($cart_item['is_free']) && $cart_item['is_free'] == true) {
            return '<span class="disabled-remove-icon"><i class="dashicons dashicons-trash"></i></span>';
        }
        return $remove_link;
    }
}
