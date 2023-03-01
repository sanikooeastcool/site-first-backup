<?php
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
add_action('woocommerce_after_single_product', 'woocommerce_upsell_display', 15);
add_action('woocommerce_after_single_product', 'woocommerce_output_related_products', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 6);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 20);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10);

add_filter('loop_shop_per_page', 'new_loop_shop_per_page', 20);
function new_loop_shop_per_page($cols)
{
    // $cols contains the current number of products per page based on the value stored on Options –> Reading
    // Return the number of products you wanna show per page.
    $cols = 6;
    return $cols;
}

add_filter('woocommerce_get_image_size_gallery_thumbnail', function ($size) {
    return array(
        'width' => 150,
        'height' => 150,
        'crop' => 0,
    );
});

add_filter('woocommerce_default_catalog_orderby', 'misha_default_catalog_orderby');
function misha_default_catalog_orderby($sort_by)
{
    return 'date';
}

add_filter('woocommerce_layered_nav_count', '__return_false');


add_filter('woocommerce_enqueue_styles', 'jk_dequeue_styles');
function jk_dequeue_styles($enqueue_styles)
{
    unset($enqueue_styles['woocommerce-general']);    // Remove the gloss
    unset($enqueue_styles['woocommerce-layout']);        // Remove the layout
    unset($enqueue_styles['woocommerce-smallscreen']);    // Remove the smallscreen optimisation
    return $enqueue_styles;
}

add_action('wp', 'bbloomer_remove_sidebar_product_pages');

function bbloomer_remove_sidebar_product_pages()
{
    if (is_product()) {
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    }
}

/**
 * Remove the breadcrumbs
 */
add_action('init', 'woo_remove_wc_breadcrumbs');
function woo_remove_wc_breadcrumbs()
{
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
}


add_action('woocommerce_after_single_product_summary', 'move_tags_product_to_end_tab', 11);
function move_tags_product_to_end_tab()
{
    global $product;
    echo wc_get_product_tag_list($product->get_id(), ' ', '<div class="tags_post">' . _n('', '', count($product->get_tag_ids()), 'woocommerce') . ' ', '</span>');
}


if (!function_exists('is_archive_product')) {
    function is_archive_product()
    {
        if ((is_woocommerce() && is_tax()) || is_shop()) {
            return true;
        }
        return false;
    }
}

if (!function_exists('websima_list_products')) {
    function websima_list_products($query = 'recent_products', $count = 6, $ids = null, $cat = null)
    {
        /*
         * recent_products
         * featured_products
         * best_selling_products
         * sale_products
         * top_rated_products
         */
        $html = '';
        $id_products = '';
        $slug = '';
        if ($ids != null) $id_products = implode(",", $ids);
        if ($cat != null) {
            $term = get_term($cat, 'product_cat');
            $slug = $term->slug;
        }
        $html .= do_shortcode("[" . $query . " limit='" . $count . "' category='" . $slug . "' ids='" . $id_products . "']");
        return $html;
    }
}


add_action('wp_footer', 'bbloomer_add_cart_quantity_plus_minus');
function bbloomer_add_cart_quantity_plus_minus()
{
    // Only run this on the single product page
    //if ( ! is_product() ) return;
    if (is_cart() || is_product()) {
?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {

                $('.woocommerce').on('click', 'form.woocommerce-cart-form .plus,form.woocommerce-cart-form .minus', function(e) {
                    e.preventDefault();
                    setTimeout(function() {
                        jQuery('[name="update_cart"]').trigger('click');
                    }, 100);

                    // Get current quantity values
                    var qty = $(this).parent().find('.qty');
                    var btnupdate = $('form.woocommerce-cart-form').find('button[name="update_cart"]');
                    var val = parseInt(qty.val());
                    var max = qty.attr('max');
                    var min = qty.attr('min');
                    var step = parseInt(qty.attr('step'));

                    //console.log(max);
                    // Change the value if plus or minus
                    if ($(this).is('.plus')) {
                        if (max && (max <= val)) {
                            qty.val(max);
                        } else {
                            qty.val(val + step);
                        }
                    } else {
                        if (min && (min >= val)) {
                            qty.val(min);
                        } else if (val > 1) {
                            qty.val(val - step);
                        }
                    }
                    btnupdate.removeAttr('disabled');
                });

                $('form.cart').on('click', 'button.plus, button.minus', function(e) {
                    e.preventDefault();

                    // Get current quantity values
                    var qty = $(this).closest('form.cart').find('.qty');
                    var val = parseFloat(qty.val());
                    var max = parseFloat(qty.attr('max'));
                    var min = parseFloat(qty.attr('min'));
                    var step = parseFloat(qty.attr('step'));

                    // Change the value if plus or minus
                    if ($(this).is('.plus')) {
                        if (max && (max <= val)) {
                            qty.val(max);
                        } else {
                            qty.val(val + step);
                        }
                    } else {
                        if (min && (min >= val)) {
                            qty.val(min);
                        } else if (val > 1) {
                            qty.val(val - step);
                        }
                    }
                });
            });
        </script>
    <?php
    }
}

add_action('wp_footer', 'bbloomer_cart_refresh_update_qty');
function bbloomer_cart_refresh_update_qty()
{
    if (is_cart()) {
    ?>
        <script type="text/javascript">
            jQuery('div.woocommerce').on('change', 'input.qty', function() {
                setTimeout(function() {
                    jQuery('[name="update_cart"]').trigger('click');
                }, 100);
            });
        </script>
<?php
    }
}


function wc_varb_price_range($wcv_price, $product)
{

    $prefix = sprintf('%s ', __('', 'wcvp_range'));

    $wcv_reg_min_price = $product->get_variation_regular_price('min', true);
    $wcv_min_sale_price    = $product->get_variation_sale_price('min', true);
    $wcv_max_price = $product->get_variation_price('max', true);
    $wcv_min_price = $product->get_variation_price('min', true);

    $wcv_price = ($wcv_min_sale_price == $wcv_reg_min_price) ?
        wc_price($wcv_reg_min_price) :
        '<del>' . wc_price($wcv_reg_min_price) . '</del>' . '<ins>' . wc_price($wcv_min_sale_price) . '</ins>';

    return ($wcv_min_price == $wcv_max_price) ?
        $wcv_price :
        sprintf('%s%s', $prefix, $wcv_price);
}

add_filter('woocommerce_variable_sale_price_html', 'wc_varb_price_range', 10, 2);
add_filter('woocommerce_variable_price_html', 'wc_varb_price_range', 10, 2);


/* Redirects to the Orders List instead of Woocommerce My Account Dashboard */
function wpmu_woocommerce_account_redirect()
{

    $current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $dashboard_url = get_permalink(get_option('woocommerce_myaccount_page_id'));

    if (is_user_logged_in() && $dashboard_url == $current_url) {
        $url = get_home_url() . '/my-account/orders';
        wp_redirect($url);
        exit;
    }
}
add_action('template_redirect', 'wpmu_woocommerce_account_redirect');

/* Remove the Dashboard tab of the My Account Page */
function custom_account_menu_items($items)
{
    unset($items['dashboard']);
    unset($items['downloads']);
    //unset($items['edit-address']);
    //unset($items['edit-account']);
    return $items;
}
add_filter('woocommerce_account_menu_items', 'custom_account_menu_items');

register_sidebar(array(
    'name' => 'sidebar shop',
    'id' => 'sidebar_shop',
    'before_widget' => '<div id="%1$s" class="widget widget-side mb-4 %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<div class="widget-title"><h4>',
    'after_title' => '</h4></div>',
));

remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination');
add_action('woocommerce_after_shop_loop', function ($query = null) {
    $args = array(
        'type' => 'list',
        'prev_text' => "<i class='icon-chevron-thin-left'></i>",
        'next_text' => "<i class='icon-chevron-thin-right'></i>",
    );
    if ($query != '') $args['total'] = $query->max_num_pages;
    echo paginate_links($args);
});


/**
 * After setup theme
 **/
add_action('after_setup_theme', 'websima_after_theme_setup');
function websima_after_theme_setup()
{
    add_theme_support('woocommerce');
    add_theme_support('html5', array('style', 'script'));
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    //Remove Default Wordpress Gallery Styles
    add_filter('use_default_gallery_style', '__return_false');
}

//instock/outofstock in shop page
add_action('pre_get_posts', 'filter_press_tax');

function filter_press_tax($query)
{
    if ($query->is_main_query()) {
        if (isset($_GET['ordersort'])) {
            $ordersort = $_GET['ordersort'];
            if ($ordersort) :
                $query->set('order', $ordersort);
            endif;
        }

        if (isset($_GET['stock'])) {
            $stock = $_GET['stock'];
            if ($stock == 'true') :
                $query->set('meta_query', array(
                    array(
                        'key' => '_stock_status',
                        'value' => 'instock',
                        'compare' => '=',
                    ),
                ));
            endif;
        }

        if (isset($_GET['ordershow'])) {
            $ordershow = $_GET['ordershow'];
            if ($ordershow) :
                $query->set('posts_per_page', $ordershow);
            endif;
        }
    }
    return;
}

add_filter('woocommerce_checkout_fields', 'custom_remove_woo_checkout_fields');

function custom_remove_woo_checkout_fields($fields)
{
    unset($fields['shipping']['shipping_company']);
    //    unset($fields['shipping']['shipping_address_2']);
    return $fields;
}


add_filter('woocommerce_default_address_fields', 'bbloomer_reorder_checkout_fields');

function bbloomer_reorder_checkout_fields($fields)
{
    //    unset($fields['address_2']);
    unset($fields['company']);
    $fields['state']['priority'] = 50;
    $fields['address_1']['priority'] = 81;
    $fields['address_2']['priority'] = 80;
    $fields['address_1']['label'] = 'Address';
    $fields['address_2']['label'] = 'Birthday';
    $fields['address_2']['required'] = true;
    $fields['address_2']['placeholder'] = 'Day / month / year';
    return $fields;
}

add_filter('woocommerce_get_catalog_ordering_args', 'wcs_get_catalog_ordering_args');
function wcs_get_catalog_ordering_args($args)
{
    $orderby_value = isset($_GET['orderby']) ? woocommerce_clean($_GET['orderby']) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby'));

    if ('on_sale' == $orderby_value) {
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
        $args['meta_key'] = '_sale_price';
    }
    return $args;
}

add_filter('woocommerce_default_catalog_orderby_options', 'wcs_catalog_orderby');
add_filter('woocommerce_catalog_orderby', 'wcs_catalog_orderby');
function wcs_catalog_orderby($sortby)
{
    $sortby['on_sale'] = 'Sort by discount';
    return $sortby;
}

add_action('wp_enqueue_scripts', 'dequeue_woocommerce_styles_scripts', 99);
function dequeue_woocommerce_styles_scripts()
{
    if (function_exists('is_woocommerce')) {
        if (!is_woocommerce() && !is_cart() && !is_checkout()) {
            # Styles
            wp_dequeue_style('woocommerce-general');
            wp_dequeue_style('woocommerce-layout');
            wp_dequeue_style('woocommerce-smallscreen');
            wp_dequeue_style('woocommerce_frontend_styles');
            wp_dequeue_style('woocommerce_fancybox_styles');
            wp_dequeue_style('woocommerce_chosen_styles');
            wp_dequeue_style('woocommerce_prettyPhoto_css');
            # Scripts
            wp_dequeue_script('wc_price_slider');
            wp_dequeue_script('wc-single-product');
            wp_dequeue_script('wc-add-to-cart');
            wp_dequeue_script('wc-cart-fragments');
            wp_dequeue_script('wc-checkout');
            wp_dequeue_script('wc-add-to-cart-variation');
            wp_dequeue_script('wc-single-product');
            wp_dequeue_script('wc-cart');
            wp_dequeue_script('wc-chosen');
            wp_dequeue_script('woocommerce');
            wp_dequeue_script('prettyPhoto');
            wp_dequeue_script('prettyPhoto-init');
            wp_dequeue_script('jquery-blockui');
            wp_dequeue_script('jquery-placeholder');
            wp_dequeue_script('fancybox');
            wp_dequeue_script('jqueryui');
        }
    }
}

/***************
 * Adding additional tab in product page.
 **************/

add_filter('woocommerce_product_tabs', 'woo_new_product_tab');
function woo_new_product_tab($tabs)
{
    // Adds the new tab
    $tabs['faqs'] = array(
        'title'     => __('faqs', 'woocommerce'),
        'callback'  => 'woo_new_product_tab_content'
    );
    return $tabs;
}
function woo_new_product_tab_content()
{

    if (get_field('show_faqs')) {
        if (get_field('faqs')) {
            echo '<div id="faqs" class="my-single">';
            echo '<div class="mx-auto">';
            websima_faqs($term = null, $title = 'false');
            echo '</div>';
            echo '</div>';
        }
    }
}
add_filter('woocommerce_product_tabs', 'woo_new_product_tab_2');
function woo_new_product_tab_2($tabs)
{
    // Adds the new tab
    $tabs['catalog'] = array(
        'title'     => __('catalog', 'woocommerce'),
        'callback'  => 'woo_new_product_tab_content_2'
    );
    return $tabs;
}
function woo_new_product_tab_content_2()
{
    if (get_field('catalog')) {
        if (get_field('catalog')) {
            echo '<div id="catalog">';
            foreach (get_field('catalog') as $ctlg) {
                echo '<div class="file-catalog">';
                echo ' <p>' . $ctlg['title'] . '</p>';
                echo '<a href="' . wp_get_attachment_url($ctlg['ctlg']) . '" class="button" download>download</a> ';
                echo '</div>';
            }
            echo '</div>';
        }
    }
}
/**
 * Rename product data tabs
 */
add_filter('woocommerce_product_tabs', 'woo_rename_tabs', 98);
function woo_rename_tabs($tabs)
{

    $tabs['description']['title'] = __('mroe detail');        // Rename the description tab
    $tabs['reviews']['title'] = __('User comments');                // Rename the reviews tab
    $tabs['additional_information']['title'] = __('Product Specifications');    // Rename the additional information tab

    return $tabs;
}
/**
 * Reorder product data tabs
 */
add_filter('woocommerce_product_tabs', 'woo_reorder_tabs', 98);
function woo_reorder_tabs($tabs)
{

    $tabs['faqs']['priority'] = 25;
    $tabs['reviews']['priority'] = 20;
    $tabs['catalog']['priority'] = 15;
    $tabs['description']['priority'] = 10;
    $tabs['additional_information']['priority'] = 5;

    return $tabs;
}

/**
 * Disable plugins update capability
 */
//https://wordpress.stackexchange.com/questions/20580/disable-update-notification-for-individual-plugins
add_filter('site_transient_update_plugins', 'websima_site_transient_update_plugins');
function websima_site_transient_update_plugins($value)
{
    if (array_key_exists("woocommerce-delivery-notes/woocommerce-delivery-notes.php", $value->response)) {
        unset($value->response['woocommerce-delivery-notes/woocommerce-delivery-notes.php']);
    }
    return $value;
}



/**
 * ACF Add shop page
 */
//https://support.advancedcustomfields.com/forums/topic/trying-to-add-field-group-to-the-woocommerce-shop-page-only/
add_filter('acf/location/rule_values/page_type', 'websima_acf_location_rules_values_woo_shop');
function websima_acf_location_rules_values_woo_shop($choices)
{
    $choices['woo-shop-page'] = 'shop page';
    return $choices;
}

add_filter('acf/location/rule_match/page_type', 'websima_acf_location_rules_match_woo_shop', 10, 3);
function websima_acf_location_rules_match_woo_shop($match, $rule, $options)
{
    if (is_admin()) {
        $screen = get_current_screen();
        if (is_object($screen)) {
            if ($rule['param'] == 'page_type' && $rule['value'] == 'woo-shop-page' && in_array($screen->post_type, array('page'))) {
                $post_id = $options['post_id'];
                $woo_shop_id = get_option('woocommerce_shop_page_id');

                if ($rule['operator'] == "==") {
                    $match = $post_id == $woo_shop_id;
                } elseif ($rule['operator'] == "!=") {
                    $match = $post_id != $woo_shop_id;
                }
            }
        }
    }

    return $match;
}

add_filter('woocommerce_get_availability_text', 'customizing_stock_availability_text', 1, 2);
function customizing_stock_availability_text($availability, $product)
{
    if (!$product->is_in_stock()) {
        $availability = __('Not available in stock.', 'woocommerce');
    } elseif ($product->managing_stock() && $product->is_on_backorder(1)) {
        $availability = $product->backorders_require_notification() ? __('Available on backorder', 'woocommerce') : '';
    } elseif ($product->managing_stock()) {
        $availability = __('number', 'woocommerce');
        $stock_amount = $product->get_stock_quantity();

        switch (get_option('woocommerce_stock_format')) {
            case 'low_amount':
                if ($stock_amount <= get_option('woocommerce_notify_low_stock_amount')) {
                    /* translators: %s: stock amount */
                    $availability = sprintf(__(' %s number', 'woocommerce'), wc_format_stock_quantity_for_display($stock_amount, $product));
                }
                break;
            case '':
                /* translators: %s: stock amount */
                $availability = sprintf(__('%s number', 'woocommerce'), wc_format_stock_quantity_for_display($stock_amount, $product));
                break;
        }

        if ($product->backorders_allowed() && $product->backorders_require_notification()) {
            $availability .= ' ' . __('(can be backordered)', 'woocommerce');
        }
    } else {
        $availability = '';
    }

    return $availability;
}

/**
 * Disable Payment Gateway for a Specific User Role | WooCommerce
 */

// add_filter( 'woocommerce_available_payment_gateways', 'backordered_items_hide_cod', 90, 1 );
function backordered_items_hide_cod($available_gateways)
{
    // Not in backend (admin)
    if (is_admin())
        return $available_gateways;

    $has_a_backorder = false;

    // Loop through cart items
    foreach (WC()->cart->get_cart() as $cart_item) {
        if ($cart_item['data']->is_on_backorder($cart_item['quantity'])) {
            $has_a_backorder = true;
            break;
        }
    }

    if ($has_a_backorder && is_checkout() && !is_wc_endpoint_url()) {
        unset($available_gateways['payir']);
        unset($available_gateways['cod']);
    } elseif (is_wc_endpoint_url('order-pay')) {
        // Get an instance of the WC_Order Object
        $order = wc_get_order(get_query_var('order-pay'));

        // Loop through payment gateways 'pending', 'on-hold', 'processing'
        foreach ($available_gateways as $gateways_id => $gateways) {
            // Keep paypal only for "pending" order status
            if ($gateways_id !== 'payir' && $order->has_status('pending')) {
                unset($available_gateways[$gateways_id]);
            }
        }
    } elseif (is_checkout()) {

        if (isset($available_gateways['cheque'])) {
            unset($available_gateways['cheque']);
        }
    }

    return $available_gateways;
}
/* backorder text on single product page */



function change_backorder_message($text, $product)
{
    if ($product->managing_stock() && $product->is_on_backorder(1)) {
        $text = __('Add the product to your cart to receive pre-invoice.', 'your-textdomain');
    }
    return $text;
}
add_filter('woocommerce_get_availability_text', 'change_backorder_message', 10, 2);

// Add back to store button on WooCommerce cart page
add_action('woocommerce_before_cart_table', 'woo_add_continue_shopping_button_to_cart');

function woo_add_continue_shopping_button_to_cart()
{
    $shop_page_url = get_permalink(woocommerce_get_page_id('shop'));

    echo '<div class="woocommerce-message woocommerce-return-message">';
    echo '<a href="' . $shop_page_url . '" class="button">return to store</a> Do you want to buy more goods?';
    echo '</div>';
}


function websima_checkout_change_errors($fields, $errors)
{

    if (!empty($errors->get_error_codes())) {
        foreach ($errors->get_error_codes() as $error_code) {
            if ($error_code == 'billing_first_name_required') {
                $errors->remove($error_code);
                $errors->add('billing_first_name_required', 'Please enter your name.');
            }
            if ($error_code == 'billing_last_name_required') {
                $errors->remove($error_code);
                $errors->add('billing_last_name_required', 'Please enter your last name.');
            }
            if ($error_code == 'billing_address_1_required') {
                $errors->remove($error_code);
                $errors->add('billing_address_1_required', 'Please enter your address.');
            }
            if ($error_code == 'billing_address_2_required') {
                $errors->remove($error_code);
                $errors->add('billing_address_2_required', 'Please enter your date of birth.');
            }
            if ($error_code == 'billing_postcode_required') {
                $errors->remove($error_code);
                $errors->add('billing_postcode_required', 'Please enter your zip code.');
            }
            if ($error_code == 'billing_phone_required') {
                $errors->remove($error_code);
                $errors->add('billing_phone_required', 'Please enter your phone number.');
            }
            if ($error_code == 'billing_city_required') {
                $errors->remove($error_code);
                $errors->add('billing_city_required', 'Please select your city.');
            }
            if ($error_code == 'billing_state_required') {
                $errors->remove($error_code);
                $errors->add('billing_state_required', 'Please enter your province.');
            }
            if ($error_code == 'billing_email_required') {
                $errors->remove($error_code);
                $errors->add('billing_email_required', 'Please enter your email in the payment details section.');
            }
            if ($error_code == 'terms') {
                $errors->remove($error_code);
                $errors->add('terms', ' Please read and accept the terms and conditions to continue your order. ');
            }
        }
    }
}
add_action('woocommerce_after_checkout_validation', 'websima_checkout_change_errors', 999, 2);
