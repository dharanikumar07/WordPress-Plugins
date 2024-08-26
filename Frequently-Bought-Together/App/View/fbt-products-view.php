<?php
defined("ABSPATH") or die();
global $product;

// Get frequently bought together products
$fbt_products_meta = get_post_meta($product->get_id(), '_fbt_products', true);
$fbt_products = !empty($fbt_products_meta) ? explode(',', $fbt_products_meta) : array();

if (!empty($fbt_products) && is_array($fbt_products)) {
    ?>
    <div class="fbt-products" style="width: 100%; background: inherit; border-radius: 6px; text-align: center; font-family: inherit; font-size: inherit;">
        <br><br>
        <h2 class="fbt-title" style="font-weight: bold; font-size: 1.5em; margin-bottom: 20px;">Frequent Products</h2>
        <form id="fbt-form" method="post">
            <div class="Products" style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
                <?php
                foreach ($fbt_products as $fbt_product_id) {
                    $fbt_product = wc_get_product($fbt_product_id);
                    if ($fbt_product && 'publish' === get_post_status($fbt_product_id)) {
                        ?>
                        <div class="fbt-form" style="margin: 10px; padding: 15px; border: 1px solid #ddd; border-radius: 8px; width: 280px; height: 350px;">
                            <a href="<?php echo esc_url(get_permalink($fbt_product_id)); ?>" class="fbt-product-img" style="display: block; width: 100%; height: 60%; overflow: hidden;">
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url($fbt_product_id)); ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="<?php echo esc_attr($fbt_product->get_name()); ?>">
                            </a>
                            <div class="fbt-content" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 10px; height: 20%;">
                                <div class="fbt-item" style="display: flex; align-items: center; padding: 10px; justify-content: space-between;">
                                    <input type="checkbox" name="fbt_product_ids[]" style="margin-right: 10px;" value="<?php echo esc_attr($fbt_product_id); ?>" checked class="check">
                                    <a href="<?php echo esc_url(get_permalink($fbt_product_id)); ?>" style="font-size: 1.2em; color: #333; flex: 1;"><?php echo esc_html($fbt_product->get_name()); ?></a>
                                </div>
                            </div>
                            <div class="fbt-content" style="display: flex; align-items: center; justify-content: center; padding: 10px; height: 20%;">
                                <span class="price" style="text-align: center; font-size: 1.1em;"><?php echo wp_kses_post($fbt_product->get_price_html()); ?></span>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo '<p>Product not found or not published for ID: ' . esc_html($fbt_product_id) . '</p>';
                    }
                }
                ?>
            </div>
            <button id="fbt-add-all-to-cart" style="margin: 20px 0; background-color: black; color: white; border-radius: 8px; padding: 12px 24px; border: none; font-size: 1.2em;" type="submit">
                <?php echo esc_html__('Add to Cart', 'frequently-bought-together') ?>
            </button>
        </form>

    </div>
    <?php
} else {
    echo '<p>No frequently bought together products found.</p>';
}
?>
