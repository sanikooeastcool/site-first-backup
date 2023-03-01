<?php
function websima_wallet_update_points_log($user_id,$point,$mode,$action,$order=0){
    $dater = current_time('Y-m-d');
    $points_log = get_user_meta(esc_attr($user_id),'points_log',true);
    if (is_array($points_log)) {
        array_push($points_log, array('date' => esc_attr($dater),'action' => esc_attr($action),'point' => esc_attr($point),'mode' => esc_attr($mode),'order' => esc_attr($order)));
    } else {
        $points_log = array( array('date' => esc_attr($dater),'action' => esc_attr($action),'point' => esc_attr($point),'mode' => esc_attr($mode),'order' => esc_attr($order)));
    }
    update_user_meta(esc_attr($user_id),'points_log',$points_log);
}

function websima_wallet_update_points($user_id,$point,$mode){
    $points = get_user_meta(esc_attr($user_id),'points',true);

    if(is_array($points)){
        if($mode == 'increase'){
            $points['total'] = $points['total'] + $point;
            $points['remaining'] = $points['remaining'] + $point;
        }elseif($mode == 'decrease'){
            $points['used'] = $points['used'] + $point;
            $points['remaining'] = $points['remaining'] - $point;
        }elseif($mode == 'refund-decrease'){
            $points['total'] = $points['total'] - $point;
            $points['remaining'] = $points['remaining'] - $point;
        }elseif($mode == 'refund-increase'){
            $points['used'] = $points['used'] - $point;
            $points['remaining'] = $points['remaining'] + $point;
        }
    }else{
        $points = array();

        if($mode == 'increase'){
            $points['total'] = $point;
            $points['remaining'] = $point;
            $points['used'] = 0;
        }
    }

    update_user_meta(esc_attr($user_id),'points',$points);
}

if(get_field('wallet_credit_per_register', 'option') == 1){
    add_action( 'user_register', 'websima_wallet_user_register', 10, 1 );
}
function websima_wallet_user_register( $user_id ) {
    $point = get_field('wallet_credit_per_register_value', 'option');

    websima_wallet_update_points(esc_attr($user_id),esc_attr($point),'increase');
    websima_wallet_update_points_log(esc_attr($user_id),esc_attr($point),'increase','register');
}

add_filter( 'woocommerce_account_menu_items', 'websima_wallet_woocommerce_account_menu_items', 10, 1 );
function websima_wallet_woocommerce_account_menu_items( $items ) {
    $items['points_log'] = 'تاریخچه اعتبارها';
    return $items;
}

add_action( 'init', 'websima_wallet_initial' );
function websima_wallet_initial() {
    add_rewrite_endpoint( 'points_log', EP_PAGES );
}

add_action( 'woocommerce_account_points_log_endpoint', 'websima_wallet_woocommerce_account_points_log_endpoint' );
function websima_wallet_woocommerce_account_points_log_endpoint() {
    $user_id = get_current_user_id();
    $points_log = get_user_meta(esc_attr($user_id),'points_log',true);
    $points = get_user_meta(esc_attr($user_id),'points',true);
    $counter = 1;


    if($points){
        $total = number_format($points['total']);
        $used = number_format($points['used']);
        $remaining = number_format($points['remaining']);
    }else{
        $total = '-';
        $used = '-';
        $remaining = '-';
    }

    echo '<ul class="wrap-wallet">';
        echo '<li class="total">';
            echo '<span class="title">مجموع اعتبارها</span>';
            echo '<span class="value">'.esc_html($total).'</span>';
        echo '</li>';
        echo '<li class="used">';
            echo '<span class="title">اعتبار استفاده شده</span>';
            echo '<span class="value">'.esc_html($used).'</span>';
        echo '</li>';
        echo '<li class="remaining">';
            echo '<span class="title">اعتبار باقی مانده</span>';
            echo '<span class="value">'.esc_html($remaining).'</span>';
        echo '</li>';
    echo '</ul>';

    if (is_array($points_log)) {
        if(class_exists('WP_Parsidate')){ $bndate = bn_parsidate::getInstance(); }
		echo'<div id="user-points-wrapper">';
        echo '<table id="user-points"><thead><tr><th scope="col">#</th><th scope="col">اعتبار</th><th scope="col">رویداد</th><th scope="col">نوع</th><th scope="col">تاریخ</th></tr></thead><tbody>';
        foreach($points_log as $log) {
            if ($log['action'] == 'register'){
                $action_name = 'ثبت نام';
            } elseif ($log['action'] == 'order-cancel-percent') {
                $action_name = 'لغو خرید و حذف درصد';
            } elseif ($log['action'] == 'order-complete-percent') {
                $action_name = 'درصد از سبد خرید';
            } elseif ($log['action'] == 'order-cancel') {
                $action_name = 'لغو خرید و بازگشت اعتبار';
            } elseif ($log['action'] == 'order-complete') {
                $action_name = 'استفاده از اعتبار در خرید';
            } else {
                $action_name = $log['action'];
            }

            if($log['mode'] == 'increase'){
                $mode = 'افزایش';
            }elseif($log['mode'] == 'decrease'){
                $mode = 'کاهش';
            }elseif($log['mode'] == 'refund-increase'){
                $mode = 'افزایش';
            }elseif($log['mode'] == 'refund-decrease'){
                $mode = 'کاهش';
            }

            echo '<tr>';
            echo '<th scope="row">'.esc_html($counter).'</th>';
            echo '<td>'.esc_html(number_format($log['point'])).'</td>';
            if ($log['order'] != '0') {
                echo '<td>'.$action_name.' ('.$log['order'].')</td>';
            } else {
                echo '<td>'.esc_html($action_name).'</td>';
            }
            echo '<td>'.esc_html($mode).'</td>';
            echo '<td>';
                if(class_exists('WP_Parsidate')){
                    echo esc_html($bndate->persian_date('Y-m-d',esc_attr($log['date'])));
                }else{
                    echo esc_html($log['date']);
                }
            echo '</td>';
            echo '</tr>';
            $counter++;
        }
        echo '</tbody></table> </div>';
    } else {
        echo '<p>هنوز اعتبار کاربری برای شما ثبت نشده است.</p>';
    }
}

add_action('woocommerce_before_checkout_form', 'websima_wallet_woocommerce_before_checkout_form');
function websima_wallet_woocommerce_before_checkout_form() {
    if (is_user_logged_in()){
        $user_id = get_current_user_id();
        $points = get_user_meta(esc_attr($user_id),'points',true);
        if(is_array($points)){
            if ($points['remaining'] > 0) {


                $cart_total = WC()->cart->get_subtotal();
                /*
                $wallet_min_cart_switch = get_field('wallet_min_cart_switch', 'option');
                $wallet_max_cart_switch = get_field('wallet_max_cart_switch', 'option');
                $wallet_min_cart_value = get_field('wallet_min_cart_value', 'option');
                $wallet_max_cart_value = get_field('wallet_max_cart_value', 'option');
                $wallet_coupon_switch = get_field('wallet_coupon_switch', 'option');
                $wallet_discount_switch = get_field('wallet_discount_switch', 'option');

                $wallet_min_permission = false;
                if($wallet_min_cart_switch == 1){
                    if($cart_total >= $wallet_min_cart_value){
                        $wallet_min_permission = true;
                    }else{
                        $wallet_min_permission = false;
                    }
                }else{
                    $wallet_min_permission = true;
                }

                $wallet_max_permission = false;
                if($wallet_max_cart_switch == 1){
                    if($cart_total <= $wallet_max_cart_value){
                        $wallet_max_permission = true;
                    }else{
                        $wallet_max_permission = false;
                    }
                }else{
                    $wallet_max_permission = true;
                }

                $wallet_coupon_permission = false;
                if($wallet_coupon_switch == 1){
                    $wallet_coupon_permission = true;
                }else{
                    if(WC()->cart->get_coupons()){
                        $wallet_coupon_permission = false;
                    }else{
                        $wallet_coupon_permission = true;
                    }
                }

                $wallet_discount_permission = false;
                if($wallet_discount_switch == 1){
                    $wallet_discount_permission = true;
                }else{
                    $is_on_sale = false;
                    foreach(WC()->cart->get_cart() as $key => $cart_item){
                        $product = $cart_item['data'];
                        if($product->is_on_sale()){
                            $is_on_sale = true;
                        }
                    }
                    if($is_on_sale){
                        $wallet_discount_permission = false;
                    }else{
                        $wallet_discount_permission = true;
                    }
                }*/


                $available_credit = 0;
                $wallet_use_max_credit_switch = get_field('wallet_use_max_credit_switch', 'option');
                $wallet_use_max_credit_type = get_field('wallet_use_max_credit_type', 'option');
                $wallet_use_max_credit_value = get_field('wallet_use_max_credit_value', 'option');
                if($wallet_use_max_credit_switch == 1){
                    if ($wallet_use_max_credit_value > 0) {
                        if ($wallet_use_max_credit_type == 'percentage') {
                            if($wallet_use_max_credit_value <= 100){
                                $available_credit = ($cart_total * $wallet_use_max_credit_value / 100);
                            }else{
                                $available_credit = $points['remaining'];
                            }
                        } else {
                            $available_credit = $wallet_use_max_credit_value;
                        }
                    }else{
                        $available_credit = 0;
                    }
                }else{
                    $available_credit = $points['remaining'];
                }

                if($available_credit > $points['remaining']){
                    $available_credit = $points['remaining'];
                }

                if(/*$wallet_min_permission and $wallet_max_permission and $wallet_coupon_permission and $wallet_discount_permission and */$available_credit){
                    echo '<div class="checkout-points-form">';
                        echo '<div class="wrapper">';
                        echo '<span>';
                        echo '<i class="icon-chart"></i>';
                        echo 'شما می توانید از '.esc_html($available_credit).' اعتبار کاربری استفاده نمایید.';
                        echo '</span>';
                        echo '<span class="use-credit">استفاده از اعتبار کاربری</span>';
                        echo '</div>';
                    echo '</div>';
                }
            }
        }
    }
}

add_action( 'wp_footer', 'websima_wallet_wp_footer' );
function websima_wallet_wp_footer() {
    if ( is_checkout() && ! is_wc_endpoint_url() ) {
        WC()->session->__unset('credit');
        ?>
        <script type="text/javascript">
            jQuery( function($){
                jQuery('.woocommerce-checkout').on('click', '.use-credit', function(){
                    var credit_button = jQuery(this);
                    if (credit_button.hasClass('remove-credit')) {
                        jQuery.ajax({
                            type: 'POST',
                            url: wc_checkout_params.ajax_url,
                            data: {
                                'action': 'websima_wallet_woo_credit',
                                'credit': '0',
                            },
                            success: function (result) {
                                credit_button.removeClass('remove-credit');
                                credit_button.text('استفاده از اعتبار کاربری');

                                jQuery('body').trigger('update_checkout');
                            },
                            error: function(error){}
                        });
                    } else {
                        jQuery.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: wc_checkout_params.ajax_url,
                            data: {
                                'action': 'websima_wallet_woo_credit',
                                'credit': '1',
                            },
                            beforeSend : function(){
                                jQuery(".checkout-points-form").append('<div class="alert bg-primary">در حال ارسال اطلاعات...</div>');
                                $alert = jQuery(".checkout-points-form").find('.alert');
                            },
                            success: function (response) {
                                if(response.status == 0){
                                    $alert.removeClass('bg-danger bg-success bg-primary');
                                    $alert.addClass('bg-danger');
                                    $alert.text(response.msg);
                                    setTimeout(function(){ $alert.fadeOut('slow', function(){ $alert.remove(); }); }, 3000);
                                }
                                if(response.status == 1){
                                    $alert.removeClass('bg-danger bg-success bg-primary');
                                    $alert.addClass('bg-success');
                                    $alert.text(response.msg);
                                    setTimeout(function(){ $alert.fadeOut('slow', function(){ $alert.remove(); }); }, 3000);

                                    credit_button.addClass('remove-credit');
                                    credit_button.text('حذف اعتبار کاربری');
                                    jQuery('body').trigger('update_checkout');
                                }
                            },
                            error: function(error){}
                        });
                    }
                });
            });
        </script>
        <?php
    }
}

add_action( 'wp_ajax_websima_wallet_woo_credit', 'websima_wallet_woo_credit' );
function websima_wallet_woo_credit() {
    if ( isset($_POST['credit']) ){
        if ( $_POST['credit'] == '1' ){

            $cart_total = WC()->cart->get_subtotal();
            $wallet_min_cart_switch = get_field('wallet_min_cart_switch', 'option');
            $wallet_max_cart_switch = get_field('wallet_max_cart_switch', 'option');
            $wallet_min_cart_value = get_field('wallet_min_cart_value', 'option');
            $wallet_max_cart_value = get_field('wallet_max_cart_value', 'option');
            $wallet_coupon_switch = get_field('wallet_coupon_switch', 'option');
            $wallet_discount_switch = get_field('wallet_discount_switch', 'option');

            $wallet_min_permission = false;
            if($wallet_min_cart_switch == 1){
                if($cart_total >= $wallet_min_cart_value){
                    $wallet_min_permission = true;
                }else{
                    $wallet_min_permission = false;
                }
            }else{
                $wallet_min_permission = true;
            }

            $wallet_max_permission = false;
            if($wallet_max_cart_switch == 1){
                if($cart_total <= $wallet_max_cart_value){
                    $wallet_max_permission = true;
                }else{
                    $wallet_max_permission = false;
                }
            }else{
                $wallet_max_permission = true;
            }

            $wallet_coupon_permission = false;
            if($wallet_coupon_switch == 1){
                $wallet_coupon_permission = true;
            }else{
                if(WC()->cart->get_coupons()){
                    $wallet_coupon_permission = false;
                }else{
                    $wallet_coupon_permission = true;
                }
            }

            $wallet_discount_permission = false;
            if($wallet_discount_switch == 1){
                $wallet_discount_permission = true;
            }else{
                $is_on_sale = false;
                foreach(WC()->cart->get_cart() as $key => $cart_item){
                    $product = $cart_item['data'];
                    if($product->is_on_sale()){
                        $is_on_sale = true;
                    }
                }
                if($is_on_sale){
                    $wallet_discount_permission = false;
                }else{
                    $wallet_discount_permission = true;
                }
            }

            $status = 0;
            if($wallet_min_permission){
                if($wallet_max_permission){
                    if($wallet_coupon_permission){
                        if($wallet_discount_permission){
                            WC()->session->set('credit', '1' );

                            $error = 'اعتبار با موفقیت اعمال گردید.';
                            $status = 1;
                        }else{
                            $error = 'امکان استفاده همزمان اعتبار با محصول تخفیف دار وجود ندارد.';
                        }
                    }else{
                        $error = 'امکان استفاده همزمان اعتبار با کد تخفیف وجود ندارد.';
                    }
                }else{
                    $error = 'حداکثر مبلغ سبد خرید برای اعمال اعتبار باید '.number_format($wallet_max_cart_value).' تومان باشد.';
                }
            }else{
                $error = 'حداقل مبلغ سبد خرید برای اعمال اعتبار باید '.number_format($wallet_min_cart_value).' تومان باشد.';
            }

            $resp = array('status' => $status, 'msg' => $error);
            header( "Content-Type: application/json" );
            echo json_encode($resp);
            die();
        } else {
            WC()->session->set('credit', '0' );
        }
    }
    die();
}

add_action( 'woocommerce_cart_calculate_fees', 'websima_wallet_woocommerce_cart_calculate_fees', 20, 1 );
function websima_wallet_woocommerce_cart_calculate_fees( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    $tax_factor = $cart->cart_contents_total;
    $credit = WC()->session->get( 'credit' );

    if ( is_checkout() ) {
        if ( $credit == '1' ) {
            $user_id = get_current_user_id();
            $points = get_user_meta(esc_attr($user_id),'points',true);
            //$remaining_points = $points['remaining'];



            $available_credit = 0;
            $wallet_use_max_credit_switch = get_field('wallet_use_max_credit_switch', 'option');
            $wallet_use_max_credit_type = get_field('wallet_use_max_credit_type', 'option');
            $wallet_use_max_credit_value = get_field('wallet_use_max_credit_value', 'option');
            if($wallet_use_max_credit_switch == 1){
                if ($wallet_use_max_credit_value > 0) {
                    if ($wallet_use_max_credit_type == 'percentage') {
                        if($wallet_use_max_credit_value <= 100){
                            $available_credit = ($tax_factor * $wallet_use_max_credit_value / 100);
                        }else{
                            $available_credit = $points['remaining'];
                        }
                    } else {
                        $available_credit = $wallet_use_max_credit_value;
                    }
                }else{
                    $available_credit = 0;
                }
            }else{
                $available_credit = $points['remaining'];
            }

            if($available_credit > $points['remaining']){
                $available_credit = $points['remaining'];
            }

            if ($tax_factor > $available_credit) {
                $cost = $available_credit*-1;
            } else {
                $cost = $tax_factor*-1;
            }
            $label = 'استفاده از اعتبار کاربری';
            $cart->add_fee(esc_html($label),esc_attr($cost));
        }
    }
}

add_action( 'woocommerce_order_status_on-hold', 'websima_wallet_woocommerce_order_status_completed' );
add_action( 'woocommerce_order_status_pending', 'websima_wallet_woocommerce_order_status_completed' );
add_action( 'woocommerce_order_status_processing', 'websima_wallet_woocommerce_order_status_completed' );
add_action( 'woocommerce_order_status_completed', 'websima_wallet_woocommerce_order_status_completed' );
function websima_wallet_woocommerce_order_status_completed( $order_id ) {
    $order = new WC_Order(esc_attr($order_id));
    $credit_manage = get_post_meta(esc_attr($order_id), 'credit_manage', true );
    if ($credit_manage == '' || $credit_manage == 'removed') {
        //$total = $order->get_total();
        $total = $order->get_total() - $order->get_total_tax() - $order->get_total_shipping() - $order->get_shipping_tax();
        $user_id = $order->get_user_id();
        foreach( $order->get_items('fee') as $item_id => $item_fee ){
            $fee_name = $item_fee->get_name();
            if( $fee_name == 'استفاده از اعتبار کاربری') {
                $fee_total = $item_fee->get_total();
                if ($fee_total  < 0) {
                    websima_wallet_update_points(esc_attr($user_id),abs($fee_total),'decrease');
                    websima_wallet_update_points_log(esc_attr($user_id),abs($fee_total),'decrease','order-complete',esc_attr($order_id));
                }
            }
        }

        $wallet_credit_per_order_switch = get_field('wallet_credit_per_order_switch', 'option');
        $wallet_credit_per_order_pm_switch = get_field('wallet_credit_per_order_pm_switch', 'option');
        if($wallet_credit_per_order_switch == 1){
        if($total > 0){
            if(($wallet_credit_per_order_pm_switch) or ((!$wallet_credit_per_order_pm_switch) and ($order->get_payment_method() != 'cod'))){
                $wallet_credit_per_order_type = get_field('wallet_credit_per_order_type', 'option');
                $wallet_credit_per_order_value = get_field('wallet_credit_per_order_value', 'option');
                if ($wallet_credit_per_order_value > 0) {
                    if($wallet_credit_per_order_type == 'percentage'){
                        $credit = ($total*$wallet_credit_per_order_value/100);
                    }else{
                        $credit = $wallet_credit_per_order_value;
                    }

                    websima_wallet_update_points(esc_attr($user_id),abs($credit),'increase');
                    websima_wallet_update_points_log(esc_attr($user_id),abs($credit),'increase','order-complete-percent',esc_attr($order_id));
                }
            }
        }
        }

        update_post_meta(esc_attr($order_id),'credit_manage', 'added');
    }
}

add_action( 'woocommerce_order_status_refunded', 'websima_wallet_woocommerce_order_status_cancelled' );
add_action( 'woocommerce_order_status_failed', 'websima_wallet_woocommerce_order_status_cancelled' );
add_action( 'woocommerce_order_status_cancelled', 'websima_wallet_woocommerce_order_status_cancelled' );
function websima_wallet_woocommerce_order_status_cancelled( $order_id ) {
    $order = new WC_Order(esc_attr($order_id));
    $credit_manage = get_post_meta(esc_attr($order_id), 'credit_manage', true);
    if ($credit_manage == 'added') {
        //$total = $order->get_total();
        $total = $order->get_total() - $order->get_total_tax() - $order->get_total_shipping() - $order->get_shipping_tax();
        $user_id = $order->get_user_id();
        foreach( $order->get_items('fee') as $item_id => $item_fee ){
            $fee_name = $item_fee->get_name();
            if( $fee_name == 'استفاده از اعتبار کاربری') {
                $fee_total = $item_fee->get_total();
                if ($fee_total  < 0) {
                    websima_wallet_update_points(esc_attr($user_id),abs($fee_total),'refund-increase');
                    websima_wallet_update_points_log(esc_attr($user_id),abs($fee_total),'refund-increase','order-cancel',esc_attr($order_id));
                }
            }
        }

        $wallet_credit_per_order_switch = get_field('wallet_credit_per_order_switch', 'option');
        $wallet_credit_per_order_pm_switch = get_field('wallet_credit_per_order_pm_switch', 'option');
        if($wallet_credit_per_order_switch == 1){
        if($total > 0){
            if(($wallet_credit_per_order_pm_switch) or ((!$wallet_credit_per_order_pm_switch) and ($order->get_payment_method() != 'cod'))){
                $wallet_credit_per_order_type = get_field('wallet_credit_per_order_type', 'option');
                $wallet_credit_per_order_value = get_field('wallet_credit_per_order_value', 'option');
                if ($wallet_credit_per_order_value > 0) {
                    if($wallet_credit_per_order_type == 'percentage'){
                        $credit = ($total*$wallet_credit_per_order_value/100);
                    }else{
                        $credit = $wallet_credit_per_order_value;
                    }

                    websima_wallet_update_points(esc_attr($user_id),abs($credit),'refund-decrease');
                    websima_wallet_update_points_log(esc_attr($user_id),abs($credit),'refund-decrease','order-cancel-percent',esc_attr($order_id));
                }
            }
        }
        }


        update_post_meta(esc_attr($order_id),'credit_manage', 'removed');
    }
}


add_action('admin_footer', 'websima_wallet_export_button');
function websima_wallet_export_button(){
    $screen = get_current_screen();
    if($screen->id == "users"){
        ?>
        <script type="text/javascript">
            jQuery(document).ready( function($){
                jQuery('.tablenav.top .clear, .tablenav.bottom .clear').before('<form action="#" method="POST" id="user-export-form"> <input type="hidden" name="user-export-csv" id="user-export-csv" value="1"/> <input type="submit" class="button button-primary" value="خروجی csv"/> </form>');
            });
        </script>
        <?php
    }
}

add_action('admin_init', 'websima_wallet_export_action');
function websima_wallet_export_action(){
    if($_POST['user-export-csv'] == 1){
        if(current_user_can('manage_options')){

            $csv_fields = array();
            $csv_fields[] = 'شناسه کاربری';
            $csv_fields[] = 'نام و نام خانوادگی';
            $csv_fields[] = 'مجموع اعتبارها';
            $csv_fields[] = 'اعتبار استفاده شده';
            $csv_fields[] = 'اعتبار باقی مانده';

            $output_filename = 'user_export_'.date('YmdHis').'.csv';
            $output_handle = @fopen( 'php://output', 'w' );

            header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
            header( 'Content-Description: File Transfer' );
            //header( 'Content-type: text/csv' );
            header( 'Content-type: text/csv; charset=utf-8' );
            header( 'Content-Encoding: UTF-8');
            header('Content-Transfer-Encoding: binary');
            header( 'Content-Disposition: attachment; filename=' . $output_filename );
            header( 'Expires: 0' );
            header( 'Pragma: public' );
            echo "\xEF\xBB\xBF"; // UTF-8 BOM
            // Insert header row
            fputcsv( $output_handle, $csv_fields );

            $args = array (
                'order'      => 'ASC',
                'orderby'    => 'display_name',
            );

            $wp_user_query = new WP_User_Query($args);
            $users = $wp_user_query->get_results();
            if(!empty($users)){
                foreach($users as $user){
                    $leadArray = array();

                    $user_info = get_userdata(esc_attr($user->ID));
                    $first_name = $user_info->first_name;
                    $last_name = $user_info->last_name;
                    $user_login = $user_info->user_login;
                    if($first_name or $last_name){
                        $username = esc_html($first_name).' '.esc_html($last_name);
                    }else{
                        $username = esc_html($user_login);
                    }

                    $points = get_user_meta(esc_attr($user->ID),'points',true);
                    if($points){
                        $total = number_format($points['total']);
                        $used = number_format($points['used']);
                        $remaining = number_format($points['remaining']);
                    }else{
                        $total = '-';
                        $used = '-';
                        $remaining = '-';
                    }

                    $leadArray[] = esc_html($user->ID);
                    $leadArray[] = esc_html($username);
                    $leadArray[] = esc_html($total);
                    $leadArray[] = esc_html($used);
                    $leadArray[] = esc_html($remaining);

                    fputcsv($output_handle, $leadArray);
                }
            }

            fclose( $output_handle );
            die();
        }
    }
}

add_filter( 'manage_users_columns', 'websima_wallet_manage_users_columns' );
function websima_wallet_manage_users_columns($columns){
    $columns['total'] = 'مجموع اعتبارها';
    $columns['used'] = 'اعتبار استفاده شده';
    $columns['remaining'] = 'اعتبار باقی مانده';
    return $columns;

}

add_filter( 'manage_users_custom_column', 'websima_wallet_manage_users_custom_column', 10, 3 );
function websima_wallet_manage_users_custom_column($row_output, $column_id_attr, $user){
    $points = get_user_meta(esc_attr($user),'points',true);
    if($points){
        $total = number_format($points['total']);
        $used = number_format($points['used']);
        $remaining = number_format($points['remaining']);
    }else{
        $total = '-';
        $used = '-';
        $remaining = '-';
    }

    switch($column_id_attr){
        case 'total' :
            return esc_html($total);
            break;
        case 'used' :
            return esc_html($used);
            break;
        case 'remaining' :
            return esc_html($remaining);
            break;
    }

    return $row_output;
}

add_action('woocommerce_checkout_process', 'websima_wallet_woocommerce_checkout_process');
function websima_wallet_woocommerce_checkout_process(){
    $wallet_coupon_switch = get_field('wallet_coupon_switch', 'option');

    $wallet_coupon_permission = false;
    if($wallet_coupon_switch == 1){
        $wallet_coupon_permission = true;
    }else{
        if(WC()->cart->get_coupons() and (WC()->session->get( 'credit' ) == 1)){
            $wallet_coupon_permission = false;
        }else{
            $wallet_coupon_permission = true;
        }
    }

    if(!$wallet_coupon_permission){
        wc_add_notice(esc_html('امکان استفاده همزمان اعتبار با کد تخفیف وجود ندارد.'), 'error');
    }
}

function websima_wallet_scripts() {
    if(is_account_page() || is_checkout()){
        wp_enqueue_style( 'wallet', get_template_directory_uri().'/includes/websima-wallet/assets/css/wallet.css');
    }
}
add_action( 'wp_enqueue_scripts', 'websima_wallet_scripts' );