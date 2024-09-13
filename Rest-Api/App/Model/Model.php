<?php

namespace RADP\App\Model;

class Model
{
    public static function getProducts()
    {
        $args = [
            'status' => 'publish',
            'limit'  => -1,
        ];

        $products = wc_get_products($args);
        return $products;
    }
}