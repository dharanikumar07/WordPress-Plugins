<?php
namespace RADP\App\Controller;

use RADP\App\Model\Model;
use WP_Error;

class MainController
{
    public static function registerRoutes()
    {
        register_rest_route('wc/dkstore/v1', '/products', [
            [
                'methods'             => 'GET',
                'callback'            => [self::class, 'handleGetProducts'],
                'permission_callback' => [static::class, 'checkAuthentication'], // Custom authentication check
            ]
        ]);
    }

    // Authentication check using WooCommerce's OAuth system
    public static function checkAuthentication($request)
    {
        /*return  (new \WC_REST_Authentication())->authenticate(false);*/
        if (!is_user_logged_in())
        {
          return new WP_Error('rest_forbidden', __('You are not authenticated'), ['status' => 401]);
        }
        return true;
    }

    public static function handleGetProducts()
    {
        $products = Model::getProducts();

        return self::render($products);
    }

    public static function render($products)
    {
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'id'    => $product->get_id(),
                'name'  => $product->get_name(),
                'price' => $product->get_price(),
                'stock_status' => $product->get_stock_status(),
            ];
        }
        return rest_ensure_response($data);
    }
}
