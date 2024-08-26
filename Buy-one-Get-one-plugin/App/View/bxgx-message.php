<?php

defined("ABSPATH") or die();
global $product;
if (get_post_meta($product->get_id(), '_bxgx_products', true) === 'yes') {
    ?>
    <div style="font-family: sans-serif; color: seagreen;font-weight: bold;">
        <p><?php echo esc_html__('Buy One, Get One Offer is applicable.....','buy-x-get-x'); ?></p>
    </div>
    <?php
}