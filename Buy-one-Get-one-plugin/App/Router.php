<?php

namespace BXGX\App;
use BXGX\App\Controller\Controller;
class Router
{
    public function init()
    {
        if(is_admin())
        {
            add_action('admin_menu', [Controller::class, 'addMenu']);
            add_action('admin_enqueue_scripts', [Controller::class, 'enqueueScripts']);
        }
        else{
            add_action('woocommerce_single_product_summary', [Controller::class, 'displayMessage']);
            add_action('woocommerce_add_to_cart', [Controller::class, 'addToCart'], 10, 6);
            add_action('woocommerce_before_calculate_totals', [Controller::class, 'setProductPrice'], 10, 1);
            add_action('woocommerce_after_cart_item_quantity_update', [Controller::class, 'updateProductQuantity'], 10, 4);
            add_action('woocommerce_cart_item_removed', [Controller::class, 'removeFreeProductWhenMainProductRemoved'], 10, 2);
            add_filter('woocommerce_get_item_data', [Controller::class, 'displayProductDetail'], 10, 2);
//            add_filter('woocommerce_cart_item_remove_link', [Controller::class, 'disableRemoveButtonForFreeProducts'], 10, 2);
        }
        if(wp_doing_ajax())
        {
            add_action('wp_ajax_save_selected_products', [Controller::class, 'saveSelectedProducts']);
            add_action('wp_ajax_bxgx_search_products', [Controller::class, 'searchProducts']);
        }
    }
}