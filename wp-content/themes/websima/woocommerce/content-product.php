<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}

if (is_front_page() || is_singular( 'post' ) ){
    if (get_field('dark_theme')) {
    $class = 'dark';
    }
}
if (is_archive_product()) {
    echo "<div class='col-sm-6 col-lg-4 col-xl-3 product-card-wrap'>";
}
?>
    <div class="product-item-parent <?php echo $class; ?>">
        <article <?php wc_product_class('product-item', $product); ?>>
            <a href="<?php the_permalink(); ?>" class="link-hover"></a>
            <div class="product-head">
                <div class="product-item-top">
                    <?php websima_compare_btn($product->get_id())
                    ?>
                    <?php
                    if (!$product->is_type('variable')) {
                        $sell = get_field('show_percent');
                        $percent = get_field('percent');
                        if ($sell) {
                            echo '<span class="on-sale">'.$percent.'</span>';
                        }
                    }
                    ?>
                </div>

            </div>

            <div class="product-image" <?php if (isset($dataimg)) echo $dataimg; ?>>
                <?php
                if (is_archive_product() && get_field('archive_img')) {
                    echo wp_get_attachment_image(get_field('archive_img'),'img_product');
                }else {
                    if (is_front_page() && get_field('dark_theme') && get_field('dark_img') ||  is_singular( 'post' ) && get_field('dark_theme') && get_field('dark_img') ){
                        echo wp_get_attachment_image(get_field('dark_img'),'img_product');
                    }elseif (has_post_thumbnail()) {
                        the_post_thumbnail('img_product');
                    }else {
                        echo wc_placeholder_img('img_product');
                    }
                }



                ?>
                <?php if (get_field('is_new') || get_field('is_sale')) {
                    echo "<div class='product-item-end'>";
                    if (get_field('is_new')) echo "<span class='on-new'>NEW</span>";
                    if (get_field('is_Festival')) echo "<span class='on-Festival'>جشنواره</span>";

                    echo "</div>";
                } ?>
                <?php
                $features = get_field('product_features');
                if($features) {
                    echo '<ul class="product-features">';
                    foreach ($features as $feature){
                        echo '<li>';
                        echo '<span>'.$feature['title'].'</span>';
                        echo '<span>'.$feature['feature'].'</span>';

                        echo '</li>';
                    }
                    echo '</ul>';
                }
                ?>
            </div>
            <div class="product-body">
                <?php if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) : ?>
                    <span class="sku_wrapper_card"><?php esc_html_e('', 'woocommerce'); ?> <span
                                class="sku"><?php echo ($sku = $product->get_sku()) ? $sku : esc_html__('N/A', 'woocommerce'); ?></span></span>
                <?php endif; ?>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

                <?php
                if ( ! $product->is_in_stock() ){
                    echo'<p class="stock out-of-stock">ناموجود</p>';
                }else{ ?>
                     <?php echo woocommerce_template_loop_price(); ?>
                <?php  }?>
            </div>
        </article>
    </div>
<?php
if (is_archive_product()) {
    echo "</div>";
}