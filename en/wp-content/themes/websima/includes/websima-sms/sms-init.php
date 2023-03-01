<?php
add_action('init', 'websima_sms_init');
function websima_sms_init()
{
    if (function_exists('acf_add_options_page')) {
        acf_add_options_sub_page(array(
            'page_title'     => 'Order SMS',
            'menu_title'    => 'Order SMS',
            'menu_slug'        => 'websima-package-sms-settings',
            'parent_slug'    => 'websima-package-general-settings',
            'capability'    => 'edit_posts'
        ));
    }
}

function websima_sms_send_farapayamak($mobile, $num, $msg)
{
    $client = new SoapClient('http://api.payamak-panel.com/post/send.asmx?wsdl', array('encoding' => 'UTF-8'));
    $parameters['username'] = get_field('sms_username', 'option');
    $parameters['password'] = get_field('sms_password', 'option');
    $parameters['bodyId'] = esc_attr($num);
    $parameters['to'] = esc_attr($mobile);
    $parameters['text'] = esc_attr($msg);

    $client->SendByBaseNumber2($parameters);
}

function websima_sms_send_kavenegar_old($mobile, $template, $token1, $token2 = null, $token3 = null, $token10 = null, $token20 = null)
{
    $api = get_field('sms_token', 'option');
    $url = 'https://api.kavenegar.com/v1/' . $api . '/verify/lookup.json?receptor=' . $mobile . '&template=' . $template . '&token=' . urlencode($token1) . '&token2=' . urlencode($token2) . '&token3=' . urlencode($token3) . '&token10=' . urlencode($token10) . '&token20=' . urlencode($token20);
    $update = file_get_contents($url);
}

function websima_sms_send_kavenegar($mobile, $template, $token1, $token2 = null, $token3 = null, $token10 = null, $token20 = null)
{
    $api = get_field('sms_token', 'option');
    $url = 'https://api.kavenegar.com/v1/' . $api . '/verify/lookup.json?receptor=' . $mobile . '&template=' . $template . '&token=' . urlencode($token1) . '&token2=' . urlencode($token2) . '&token3=' . urlencode($token3) . '&token10=' . urlencode($token10) . '&token20=' . urlencode($token20);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
}

$order_sms_customer_status = get_field('order_sms_customer_status', 'option');
if (!empty($order_sms_customer_status)) {
    foreach ($order_sms_customer_status as $customer_status) {
        $customer_action_name = 'woocommerce_order_status_' . esc_attr($customer_status);
        add_action(esc_attr($customer_action_name), 'websima_sms_customer_order_change_status');
    }
}

function websima_sms_customer_order_change_status($order_id)
{
    $order = wc_get_order(esc_attr($order_id));
    $user_id = $order->get_user_id();
    $status = $order->get_status();

    $billing_phone = get_user_meta(esc_attr($user_id), 'billing_phone', true);
    $billing_first_name = get_user_meta(esc_attr($user_id), 'billing_first_name', true);
    $template = get_field('order_sms_customer_template', 'option');
    $sms_company = get_field('sms_company', 'option');

    $status_txt = '';
    if ($status == 'pending') {
        $status_txt = 'pending';
    } elseif ($status == 'processing') {
        $status_txt = 'processing';
    } elseif ($status == 'on-hold') {
        $status_txt = 'on-hold';
    } elseif ($status == 'completed') {
        $status_txt = 'Completed';
    } elseif ($status == 'cancelled') {
        $status_txt = 'canceled';
    } elseif ($status == 'refunded') {
        $status_txt = 'refunded';
    } elseif ($status == 'failed') {
        $status_txt = 'failed';
    }

    if ($sms_company == 'kavenegar') {
        websima_sms_send_kavenegar(esc_attr($billing_phone), esc_attr($template), esc_attr($order_id), '', '', esc_attr($billing_first_name), esc_attr($status_txt));
    } elseif ($sms_company == 'farapayamak') {
        $msg = esc_attr($billing_first_name) . ';' . esc_attr($status_txt) . ';' . esc_attr($order_id);
        websima_sms_send_farapayamak(esc_attr($billing_phone), esc_attr($template), esc_attr($msg));
    }
}

$order_sms_manager_status = get_field('order_sms_manager_status', 'option');
if (!empty($order_sms_manager_status)) {
    foreach ($order_sms_manager_status as $manager_status) {
        $manager_action_name = 'woocommerce_order_status_' . esc_attr($manager_status);
        add_action(esc_attr($manager_action_name), 'websima_sms_manager_order_change_status');
    }
}

function websima_sms_manager_order_change_status($order_id)
{
    $order = wc_get_order(esc_attr($order_id));
    $status = $order->get_status();

    $status_txt = '';
    if ($status == 'pending') {
        $status_txt = 'pending';
    } elseif ($status == 'processing') {
        $status_txt = 'processing';
    } elseif ($status == 'on-hold') {
        $status_txt = 'on-hold';
    } elseif ($status == 'completed') {
        $status_txt = 'completed';
    } elseif ($status == 'cancelled') {
        $status_txt = 'cancelled';
    } elseif ($status == 'refunded') {
        $status_txt = 'refunded';
    } elseif ($status == 'failed') {
        $status_txt = 'failed';
    }

    $template = get_field('order_sms_manager_template', 'option');
    $sms_company = get_field('sms_company', 'option');
    if (have_rows('order_sms_manager', 'option')) :
        while (have_rows('order_sms_manager', 'option')) : the_row();
            $name = get_sub_field('name');
            $mobile = get_sub_field('mobile');

            if ($sms_company == 'kavenegar') {
                websima_sms_send_kavenegar(esc_attr($mobile), esc_attr($template), esc_attr($order_id), '', '', esc_attr($name), esc_attr($status_txt));
            } elseif ($sms_company == 'farapayamak') {
                $msg = esc_attr($name) . ';' . esc_attr($status_txt) . ';' . esc_attr($order_id);
                websima_sms_send_farapayamak(esc_attr($mobile), esc_attr($template), esc_attr($msg));
            }
        endwhile;
    endif;
}
