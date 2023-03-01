<?php

/************ websima settings **************/
add_action('init','websima_step_discount_init');
function websima_step_discount_init(){
    if( function_exists('acf_add_options_page') ) {
        acf_add_options_sub_page(array(
            'page_title' => 'تخفیف پلکانی',
            'menu_title' => 'تخفیف پلکانی',
            'menu_slug' => 'websima-package-step-discount',
            'parent_slug' => 'websima-package-general-settings',
            'capability' => 'edit_posts'
        ));
    }
}
/************ GLOBAL VARIALBES **************/
$sd_active = get_field('sd_active', 'option');
$sd_coupon = get_field('sd_coupon', 'option');
$sd_discounts = get_field('sd_discounts', 'option');
$sd_against = get_field('sd_against', 'option') ? get_field('sd_against', 'option') : [];
$sd_global_active_discount = [];
$sd_global_total = 0;
$sd_global_discount = 0;
$sd_global_notice = false;


/*********** core **************/
function websima_sd_disable() {
    global $sd_active;
    $sd_active = false;
}
add_action( 'admin_init', 'websima_sd_disable' );

add_action('wp', 'websima_sd_core');
function websima_sd_core(){
    global $woocommerce,$sd_active,$sd_coupon,$sd_discounts,$sd_against,$sd_global_discount,$sd_global_active_discount,$sd_global_notice;
    $sd_notice = get_field('sd_notice', 'option');
    $sd_subtotal = $woocommerce->cart->subtotal;
    $sd_reduced = 0;
    $sd_hascoupon = $woocommerce->cart->applied_coupons;
    if($sd_active && sizeof($sd_against)) {
        foreach (WC()->cart->get_cart() as $cart_item) {
            $sd_active = in_array($cart_item['product_id'], $sd_against) ? false : $sd_active;
        }
    }

    /** discounts loop **/
    if($sd_active && $sd_subtotal):
        for ($x = count($sd_discounts) - 1; $x >= 0; $x--) {
            $sd_discount = $sd_discounts[$x];
            $active = false;
            if($sd_discount['sd_min_price'] <= $sd_subtotal){
                if($sd_coupon){
                    $active = true;
                }elseif(!$sd_hascoupon){
                    $active = true;
                }
                if($active){
                    if($sd_discount['sd_type'] == 'percent'){
                        $sd_reduced = round($sd_subtotal * ($sd_discount['sd_discount_percent']) / 100);
                        if($sd_notice){
                            $sd_notice = $sd_discount['sd_discount_percent'];
                            $sd_notice .= ' درصد تخفیف مختص سفارشات بیشتر از ';
                            $sd_notice .= wc_price($sd_discount['sd_min_price']);
                            $sd_notice .= ' برای شما فعال گردید.';
                        }
                    }elseif($sd_discount['sd_type'] == 'price'){
                        $sd_reduced = $sd_discount['sd_discount_fee'];
                        if($sd_notice){
                            $sd_notice = wc_price($sd_discount['sd_discount_fee']);
                            $sd_notice .= ' تخفیف مختص سفارشات بیشتر از ';
                            $sd_notice .= wc_price($sd_discount['sd_min_price']);
                            $sd_notice .= ' برای شما فعال گردید.';
                        }
                    }
                    $sd_global_active_discount = $sd_discount;
                    $sd_global_discount = $sd_reduced;
                    $sd_global_notice = $sd_notice;
                }
                break;
            }
        }
    endif;
}
/*********** update total **************/
add_action( 'woocommerce_calculated_total', 'websima_sd_update_total', 10, 1 );
function websima_sd_update_total($sd_total)
{
    global $woocommerce,$sd_active,$sd_coupon,$sd_discounts,$sd_against,$sd_global_total,$sd_global_discount,$sd_global_active_discount,$sd_global_notice;
    return $sd_total - $sd_global_discount;
}
/*********** notice **************/
//add_action('wc_add_to_cart_message', 'my_custom_message_after_add_to_cart',10,1);
//function my_custom_message_after_add_to_cart($message){
//    global $sd_global_notice;
//    if($sd_global_notice) {
//        $message = $sd_global_notice;
//    }
//    return $message;
//}
add_action('woocommerce_before_cart', 'websima_sd_checkout_message');
add_action('woocommerce_before_checkout_form', 'websima_sd_checkout_message');
function websima_sd_checkout_message() {
    global $sd_global_notice;
    if($sd_global_notice) {
        wc_print_notice($sd_global_notice, 'success');
    }
}
/*********** update cart **************/
add_action( 'woocommerce_cart_totals_before_shipping', 'websima_sd_add_row', 20 );
add_action( 'woocommerce_review_order_before_shipping', 'websima_sd_add_row', 20 );
function websima_sd_add_row() {
    global $sd_global_active_discount,$sd_global_discount;
    if($sd_global_discount):
    echo '<tr class="cart-total-volume"><th>تخفیف<br><span>(سفارشات بالای '.wc_price($sd_global_active_discount['sd_min_price']).')</span></th><td>'.'-'.wc_price($sd_global_discount).'</td></tr>';
    endif;
}
/********* add meta **********/
add_action('woocommerce_checkout_create_order', 'websima_sd_add_meta', 20, 2);
function websima_sd_add_meta( $order, $data ) {
    global $sd_global_discount;
    if($sd_global_discount) {
        $order->update_meta_data('_step_discount', $sd_global_discount);
    }
}
/********* admin order page **********/
add_action('woocommerce_admin_order_totals_after_tax', 'websima_sd_admin_order_page', 10, 1 );
function websima_sd_admin_order_page( $order_id ) {
    $label = __( 'تخفیف پلکانی', 'woocommerce' );
    $value = get_post_meta($order_id,'_step_discount');
    if($value){
    ?>
    <tr>
        <td class="label"><?php echo $label; ?>:</td>
        <td width="1%"></td>
        <td class="custom-total"><b><?php echo '-'.wc_price($value[0]); ?></b></td>
    </tr>
    <?php
    }
}
