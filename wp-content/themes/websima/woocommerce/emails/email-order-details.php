<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email );

?>

<h2>
    <?php
    if ( $sent_to_admin ) {
        $before = '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
        $after  = '</a>';
    } else {
        $before = '';
        $after  = '';
    }
    /* translators: %s: Order ID. */
    echo wp_kses_post( $before . sprintf( __( '[Order #%s]', 'woocommerce' ) . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );
    ?>
</h2>
<?php

$oid = $order->get_order_number();
$shipping_method = $order->get_items('shipping');
$shipping_method = reset($shipping_method);

$shipping_first_name = get_post_meta(esc_attr($oid) ,'_shipping_first_name', true);
$shipping_last_name = get_post_meta(esc_attr($oid) ,'_shipping_last_name', true);
$shipping_phone = get_post_meta(esc_attr($oid) ,'_billing_phone', true);
$shipping_postcode = get_post_meta(esc_attr($oid) ,'_shipping_postcode', true);
$shipping_address_1 = get_post_meta(esc_attr($oid) ,'_shipping_address_1', true);
$shipping_city = get_post_meta(esc_attr($oid) ,'_shipping_city', true);
$shipping_state = get_post_meta(esc_attr($oid) ,'_shipping_state', true);
$customer_type = get_post_meta(esc_attr($oid),'customer-type',true);
$national_code = get_post_meta(esc_attr($oid),'national-code',true);
$company = get_post_meta(esc_attr($oid),'company',true);
$economic_code = get_post_meta(esc_attr($oid),'economic-code',true);
$register_number = get_post_meta(esc_attr($oid),'register-number',true);
$national_id = get_post_meta(esc_attr($oid),'national-id',true);
$date_of_birth = get_post_meta(esc_attr($oid) ,'date_of_birth', true);
$phone_number = get_post_meta(esc_attr($oid) ,'phone_number', true);



echo '<div id="delivery-note" class="box delivery-note-box white-bg without-padding">';

echo '<div class="receiver-client-info">';
echo '<div class="row no-gutters">';
echo '<div class="column column-8">';
echo '<div class="receiver-info">';
echo '<ul>';
echo '<li class="list-item">';
echo '<strong class="title">گیرنده:</strong>';
echo '<span class="value">'.websima_invoice_iran_state($shipping_state).' '.esc_html($shipping_city).' '.esc_html($shipping_address_1).'</span>';
echo '</li>';
echo '<li class="list-item">';
echo '<strong class="title">کد پستی:</strong>';
echo '<span class="value">'.esc_html($shipping_postcode).'</span>';
echo '</li>';
echo '</ul>';
echo '<ul>';
echo '<li class="list-item">';
echo '<strong class="title">نام کامل:</strong>';
echo '<span class="value">'.esc_html($shipping_first_name).' '.esc_html($shipping_last_name).'</span>';
echo '</li>';
echo '<li class="list-item">';
echo '<strong class="title">تلفن:</strong>';
echo '<span class="value">'.esc_html($shipping_phone).'</span>';
echo '</li>';
echo '<li class="list-item">';
echo '<strong class="title">تلفن ثابت:</strong>';
echo '<span class="value">'.esc_html($phone_number).'</span>';
echo '</li>';
if($national_code){
    echo '<li class="list-item">';
    echo '<strong class="title">کد ملی:</strong>';
    echo '<span class="value">'.esc_html($national_code).'</span>';
    echo '</li>';
}
if($date_of_birth){
    echo '<li class="list-item">';
    echo '<strong class="title">تاریخ تولد:</strong>';
    echo '<span class="value">'.esc_html($date_of_birth).'</span>';
    echo '</li>';
}
if($company){
    echo '<li class="list-item">';
    echo '<strong class="title">نام شرکت:</strong>';
    echo '<span class="value">'.esc_html($company).'</span>';
    echo '</li>';
}
if($economic_code){
    echo '<li class="list-item">';
    echo '<strong class="title">کد اقتصادی:</strong>';
    echo '<span class="value">'.esc_html($economic_code).'</span>';
    echo '</li>';
}
if($national_id){
    echo '<li class="list-item">';
    echo '<strong class="title">شناسه ملی:</strong>';
    echo '<span class="value">'.esc_html($national_id).'</span>';
    echo '</li>';
}

echo '</ul>';
echo '</div>';


echo '<div class="order-meta-info">';
echo '<ul>';
$date_modified = $order->get_date_modified();
$time_order = $date_modified->date("H:m");
echo '<li><strong>ساعت سفارش: </strong><span class="d-inline-block">'.esc_html($time_order).'</span></li>';
if( $order->get_used_coupons() ) {

    $coupons_count = count( $order->get_used_coupons() );

    $i = 1;
    $coupons_list = '';

    foreach( $order->get_used_coupons() as $coupon) {
        $coupons_list .=  $coupon;
        if( $i < $coupons_count )
            $coupons_list .= ', ';
        $i++;
    }
    echo '<li><strong>کد تخفیف (' . $coupons_count . ') :</strong> ' . $coupons_list . '</li>';
}

$fields = apply_filters( 'wcdn_order_info_fields', wcdn_get_order_info( $order ), $order );
unset($fields['billing_email']);
unset($fields['billing_phone']);
if($shipping_method){
    $fields['shipping_method']['label'] = 'روش حمل و نقل';
    $fields['shipping_method']['value'] = $shipping_method->get_name();
}
foreach ( $fields as $key => $field ) :
    echo '<li class="list-item '.esc_attr($key).'">';
    echo '<strong class="title">'.wp_kses_post( apply_filters( 'wcdn_order_info_name', $field['label'], $field ) ).':</strong>';
    if($field['label'] == 'تاریخ سفارش'){
        $order_date = wp_kses_post( apply_filters( 'wcdn_order_info_content', $field['value'], $field ) );
        $order_date = websima_invoice_convert_number(implode('/',array_reverse(explode("/",$order_date))));
        echo '<span class="value">'.esc_html($order_date).'</span>';
    }else{
        echo '<span class="value">'.wp_kses_post( apply_filters( 'wcdn_order_info_content', $field['value'], $field ) ).'</span>';
    }
    echo '</li>';
endforeach;


echo '</ul>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
?>
<div style="margin-bottom: 40px;">
    <table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
        <thead>
        <tr>
            <th class="td" scope="col-3" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
            <th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
            <th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>

        </tr>
        </thead>
        <tbody>
        <?php
        echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            $order,
            array(
                'show_sku'      => $sent_to_admin,
                'show_image'    => false,
                'image_size'    => array( 32, 32 ),
                'plain_text'    => $plain_text,
                'sent_to_admin' => $sent_to_admin,
            )
        );
        ?>
        </tbody>

        <tfoot>
        <?php
        $item_totals = $order->get_order_item_totals();

        if ( $item_totals ) {
            $i = 0;
            foreach ( $item_totals as $total ) {
                $i++;
                ?>
                <tr>
                    <th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['label'] ); ?></th>
                    <td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['value'] ); ?></td>
                </tr>
                <?php
            }
        }
        if ( $order->get_customer_note() ) {
            ?>
            <tr>
                <th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
                <td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
            </tr>
            <?php
        }
        ?>
        </tfoot>
    </table>
</div>
<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
