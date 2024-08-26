<?php

namespace Fbt\App;

use Fbt\App\Controller\Controller;

defined("ABSPATH") or die();
class Router
{
    public function init()
    {
        if (is_admin())
        {
            add_filter('woocommerce_product_data_tabs', [Controller::class, 'addProductTab']);
            add_action('woocommerce_product_data_panels', [Controller::class, 'addProductTabView']);
            add_action('woocommerce_process_product_meta', [Controller::class, 'saveProducts']);
            add_action('admin_enqueue_scripts',[Controller::class, 'enqueueScripts']);
        }else{
            add_action('wp_enqueue_scripts',[Controller::class, 'enqueueScripts']);
            add_action('woocommerce_after_single_product', [Controller::class, 'displayProducts']);
        }
        if(wp_doing_ajax())
        {
            add_action('wp_ajax_fbt_add_to_cart', [Controller::class, 'addToCart']);
        }
    }
}
