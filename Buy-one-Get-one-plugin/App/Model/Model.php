<?php
namespace BXGX\App\Model;

class Model
{
    public static function getProducts()
    {
        $search_query = sanitize_text_field($_POST['search_query']);
        $args = [
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            's' => $search_query,
            'fields' => 'ids'
        ];

        $products = get_posts($args);
        return $products;
    }
    public static function getSelectedProducts()
    {
        $args = [
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => '_bxgx_products',
                    'value' => 'yes',
                    'compare' => '='
                ]
            ],
            'fields' => 'ids'
        ];

        $selected_products = get_posts($args);
        return $selected_products;
    }
}
