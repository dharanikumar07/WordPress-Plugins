<?php

namespace Fbt\App\Controller;
defined("ABSPATH") or die();
class Controller
{
    /**
     * This function is used to add the fbt tab
     *
     * @param $tabs
     * @return mixed
     */
    public static function addProductTab($tabs)
    {
        $tabs['fbt'] = ['label'    => __('Frequently Bought Together', 'fbtPlugin'),
            'target'   => 'fbt_product_data',
            'class'    => ['show_if_simple', 'show_if_variable'],
            'priority' => 60,];
        return $tabs;
    }

    /**
     * This function add the html elements of our new product tab
     *
     * @return void
     */
    public static function addProductTabView()
    {
        $template_path = 'products-view.php';
        if (file_exists(FBT_PATH . 'App/View/' . $template_path)) {
            wc_get_template($template_path, [], '', FBT_PATH . 'App/View/');
        }
    }

    /**
     * This function holds script
     *
     * @return void
     */
    public static function enqueueScripts()
    {
        wp_enqueue_style('fbtproduct-css', 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css');
        wp_enqueue_script('fbtproduct-js', 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js', array('jquery'), null, true);
        wp_enqueue_style('fbt-style-css', FBT_URL . 'assets/css/customstyle.css');
        wp_enqueue_script('fbt-ajax-script', FBT_URL . 'assets/js/script.js', ['jquery'], '1.0.0', true);
        wp_localize_script('fbt-ajax-script', 'fbt_ajax', ['ajax_url' => admin_url('admin-ajax.php'),
                'selectProductMessage' =>esc_html__('Please select at least one product.', 'frequently-bought-together'),
                'nonce' => wp_create_nonce('fbt_add_to_cart_nonce')]);
    }

    /**
     * This function is used to save products
     *
     * @param $post_id
     * @return void
     */
    public static function saveProducts($post_id)
    {
        if (isset($_POST['fbt_products'])) {
            $fbt_products = array_map('sanitize_text_field', $_POST['fbt_products']);
            update_post_meta($post_id, '_fbt_products', implode(',', $fbt_products));
        } else {
            delete_post_meta($post_id, '_fbt_products');
        }
    }

    /**
     *This function is used to display FBT
     *
     * @return void
     */
    public static function displayProducts()
    {
        $template_path = 'fbt-products-view.php';
        if (file_exists(FBT_PATH . 'App/View/' . $template_path)) {
            wc_get_template($template_path, [], '', FBT_PATH . 'App/View/');
        }
    }

    public static function addToCart()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'fbt_add_to_cart_nonce')) {
            wp_send_json_error(['message' => esc_html__('Nonce verification failed', 'frequently-bought-together')]);
            return;
        }

        if (isset($_POST['product_ids']) && is_array($_POST['product_ids'])) {
            foreach ($_POST['product_ids'] as $product_id) {
                WC()->cart->add_to_cart($product_id);
            }
            wp_send_json_success(['message' => esc_html__('Product added to the cart', 'frequently-bought-together')]);
        } else {
            wp_send_json_error(['message' => esc_html__('No products selected', 'frequently-bought-together')]);
        }
    }

}
