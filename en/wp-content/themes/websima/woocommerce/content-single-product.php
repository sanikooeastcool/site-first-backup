<?php

/**

 * The template for displaying product content in the single-product.php template

 *

 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.

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


if ('' === $product->get_price() || 0 == $product->get_price()) {
    $desc_badge = get_field_object('cta_moredesc_prd', 'option')['value'];
}
?>


<main id="main" class="site-main">

    <div class="container">



        <?php

        /**

         * Hook: woocommerce_before_single_product.

         *

         * @hooked woocommerce_output_all_notices - 10

         */

        do_action('woocommerce_before_single_product');



        if (post_password_required()) {

            echo get_the_password_form(); // WPCS: XSS ok.

            return;
        }

        ?>

        <div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

            <div class="product-main-data">

                <div class="row">
                    <div class="col-md-6 col-lg-4 col-xl-3 order-3 ">
                        <div class="product-info">
                            <div class="product-info-top">
                                <?php if (wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) : ?>
                                    <span class="sku_wrapper"><?php esc_html_e('barcode  ', 'woocommerce'); ?> <span class="sku"><?php echo ($sku = $product->get_sku()) ? $sku : esc_html__('N/A', 'woocommerce'); ?></span></span>
                                <?php endif; ?>
                                <?php
                                $rating = $product->get_average_rating();
                                $rating_value = floor($rating * 4) / 4;
                                if ($rating != '') {
                                    echo '<span class="rating-product"><i class="icon-star"></i><span>' . $rating_value . '/5</span></span>';
                                } else {
                                    echo '<span><i class="icon-star"></i><span>0/5</span></span>';
                                }
                                ?>
                                <?php websima_wc_wishlist_btn($pid) ?>
                            </div>
                            <div class="product-info-desc">
                                <h4 class="title"><?php echo get_field('purchase_title', 'option') ?></h4>
                                <p class="desc"><?php echo get_field('purchase_desc', 'option') ?></p>
                                <button class="request-form-btn"> Submit a consultation request</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-5 order-2">

                        <div class="summary entry-summary">

                            <?php

                            /**

                             * Hook: woocommerce_single_product_summary.

                             *

                             * @hooked woocommerce_template_single_title - 5

                             * @hooked woocommerce_template_single_rating - 10

                             * @hooked woocommerce_template_single_price - 10

                             * @hooked woocommerce_template_single_excerpt - 20

                             * @hooked woocommerce_template_single_add_to_cart - 30

                             * @hooked woocommerce_template_single_meta - 40

                             * @hooked woocommerce_template_single_sharing - 50

                             * @hooked WC_Structured_Data::generate_product_data() - 60

                             */

                            do_action('woocommerce_single_product_summary');

                            ?>
                            <?php
                            if ('' === $product->get_price() || 0 == $product->get_price()) {


                                echo '<p class="stock available-on-backorder">' . $desc_badge . '</p>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-10 col-lg-4 order-1 mx-auto">

                        <?php
                        the_title('<h1 class="product_title entry-title d-lg-none">', '</h1>');
                        echo wc_get_product_category_list($product->get_id(), ' ', '<span class="posted_in d-lg-none">' . _n('', '', count($product->get_category_ids()), 'woocommerce') . ' ', '</span>'); ?>
                        <div class="product-share d-lg-none mb-4">
                            <span>Share with your friends</span>

                            <div class="share-icon">
                                <?php echo websima_shares(); ?>
                            </div>
                            <i class="icon-share"></i>
                        </div>
                        <?php
                        /**

                         * Hook: woocommerce_before_single_product_summary.

                         *

                         * @hooked woocommerce_show_product_sale_flash - 10

                         * @hooked woocommerce_show_product_images - 20

                         */

                        do_action('woocommerce_before_single_product_summary');

                        ?>

                    </div>

                    <?php if (get_field('extra_info')) { ?>
                        <div class="col-12 order-5">
                            <div class="extra-info editor-content main-content">
                                <?php echo get_field('extra_info') ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if (get_field('content_field', 'option')) { ?>
                        <div class="col-12 col-lg-6 order-4">
                            <div class="extra-info editor-content main-content">
                                <?php echo get_field('content_field', 'option') ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (get_field('img_field', 'option')) { ?>
                        <div class="col-12 col-lg-6 order-4">
                            <div class="extra-info editor-content main-content">
                                <?php echo get_field('img_field', 'option') ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>
        </div>

    </div>
    <?php
    $show_group_products = get_field('show_group_products');
    $group_products = get_field('group_products');

    if ($group_products != null && $show_group_products) { ?>

        <section class="section-group_products">
            <div class="container">
                <div class="title-part inner-title title-site-center">
                    <i class="icon-token-title"></i>
                    <h4 class="title-heading">Group products</h4>
                </div>
                <?php echo websima_list_products('products', 6, get_field('group_products')); ?>
            </div>

        </section>
    <?php } ?>

    <div class="container">

        <?php

        /**

         * Hook: woocommerce_after_single_product_summary.

         *

         * @hooked woocommerce_output_product_data_tabs - 10

         * @hooked woocommerce_upsell_display - 15

         * @hooked woocommerce_output_related_products - 20

         */

        do_action('woocommerce_after_single_product_summary');

        ?>

    </div>

    </div>
    </div>

    <?php do_action('woocommerce_after_single_product'); ?>
</main>