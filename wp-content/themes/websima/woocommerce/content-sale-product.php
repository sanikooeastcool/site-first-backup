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
$pid = $product->get_id();
// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
    <div class="product-item-parent dark">
        <article <?php wc_product_class('product-item', $product); ?>>
            <div class="product-head">
                <div class="product-item-top">
                    <?php websima_compare_btn($pid) ?>
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

            <a href="<?php the_permalink(); ?>" class="product-image" <?php if (isset($dataimg)) echo $dataimg; ?>>
                <?php
                $sale_img = get_field('img_sale');
                $dark_img = get_field('dark_img');
                if($sale_img) {
                    echo wp_get_attachment_image($sale_img,'img_product');
                }elseif ($dark_img) {
                    echo wp_get_attachment_image($dark_img,'img_product');
                }elseif (has_post_thumbnail()) {
                    the_post_thumbnail('img_product');
                } else {
                    echo wc_placeholder_img('img_product');
                } ?>
                <?php if (get_field('is_new') || get_field('is_sale')) {
                    echo "<div class='product-item-end'>";
                    if (get_field('is_new')) echo "<span class='on-new'>New</span>";
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
            </a>
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
        <?php  if (isset($timer)) {

            $_sale_price_dates_to = '';
            $date_arr = array();
            if ($product->is_type('variable')) {
                $available_variations = $product->get_available_variations();
                foreach ($available_variations as $var) {
                    $var_sale_price_dates_to = get_field('_sale_price_dates_to', $var['variation_id']);
                    if ($var_sale_price_dates_to) {
                        array_push($date_arr, date('Y-M-d', $var_sale_price_dates_to));
                    }
                }
                if ($date_arr) {
                    usort($date_arr, function ($a, $b) {
                        $dateTimestamp1 = strtotime($a);
                        $dateTimestamp2 = strtotime($b);
                        return $dateTimestamp1 < $dateTimestamp2 ? -1 : 1;
                    });
                }
                if ($date_arr[count($date_arr) - 1]) {
                    echo "<input class='product-sale-expire' type='hidden' value='" . $date_arr[count($date_arr) - 1] . "'>";
                    echo "<div class='countdown-wrapper'><span><i class='icon-timer'></i></span><div class='countdown'></div></div>";
                }
            } else {
                $_sale_price_dates_to = get_field('_sale_price_dates_to');
                if ($_sale_price_dates_to) {
                    echo "<input class='product-sale-expire' type='hidden' value='" . date('Y-M-d', $_sale_price_dates_to) . "'>";
                    echo "<div class='countdown-wrapper'><span><i class='icon-timer'></i></span><div class='countdown'></div></div>";
                }
            }
        }?>
    </div>
<?php
if (is_archive_product()) {
    echo "</div>";
}