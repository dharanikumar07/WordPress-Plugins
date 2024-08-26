<?php
namespace AAC\App;

use AAC\App\Controller\Controller;

class Router
{
    public static function init()
    {
        if(!is_admin()) {
            add_action('woocommerce_before_calculate_totals', [Controller::class, 'generateCouponCode'], 10);
            add_filter('woocommerce_get_shop_coupon_data', [Controller::class, 'setCoupon'], 10, 2);
        }

    }
}
