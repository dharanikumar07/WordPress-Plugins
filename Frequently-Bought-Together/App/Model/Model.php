<?php
namespace Fbt\App\Model;
defined("ABSPATH") or die();
class Model
{
    public static function getFbtProducts($post_id)
    {
        $fbt_products = get_post_meta($post_id, '_fbt_products', true);
        return ! empty($fbt_products) ? explode(',', $fbt_products) : [];
    }

    public static function getProducts()
    {
        $args = ['post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'fields' => 'ids',];
        return get_posts($args);
    }
}
