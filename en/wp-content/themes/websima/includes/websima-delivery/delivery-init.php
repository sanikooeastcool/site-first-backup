<?php
add_action('init', 'websima_delivery_init');
function websima_delivery_init()
{
    if (function_exists('acf_add_options_page')) {
        acf_add_options_sub_page(array(
            'page_title'     => 'Order delivery time',
            'menu_title'    => 'Order delivery time',
            'menu_slug'        => 'websima-package-delivery-settings',
            'parent_slug'    => 'websima-package-general-settings',
            'capability'    => 'edit_posts'
        ));
    }
}

if (get_field('delivery_time_status', 'option') == 1) {
    add_action('wp_enqueue_scripts', 'websima_delivery_enqueue_scripts');
}
function websima_delivery_enqueue_scripts()
{
    if (is_checkout()) {
        wp_enqueue_script('websima_delivery-script', get_template_directory_uri() . '/includes/websima-delivery/assets/js/script.js', array('jquery'), '1.0.0', true);
    }
}

add_action('woocommerce_before_order_notes', 'websima_delivery_before_order_notes');
function websima_delivery_before_order_notes($checkout)
{
    echo '<div class="delivery-wrapper">';
    $delivery_description = get_field('delivery_description', 'option');
    echo '<h3 class="title">Day and time of order delivery</h3>';
    if ($delivery_description) {
        echo '<p class="description">' . esc_html($delivery_description) . '</p>';
    }

    $holidays = array();
    if (have_rows('holidays', 'option')) :
        while (have_rows('holidays', 'option')) : the_row();
            if (get_sub_field('date')) {
                $holidays[] = get_sub_field('date');
            }
        endwhile;
    endif;

    $allow_order_time = get_field('allow_order_time', 'option');
    $delivery_time_status = get_field('delivery_time_status', 'option');
    $minimum_preparation_day = get_field('minimum_preparation_day', 'option');
    $business_days = get_field('business_days', 'option');
    $max = get_field('allow_order_days', 'option');
    $i = 0 + (int)$minimum_preparation_day;
    $j = $max + (int)$minimum_preparation_day;
    $day_options = array('0' => 'Choose day');
    $time_options = array('0' => 'Choose time');
    for ($i; $i < $j; $i++) {
        $date = parsidate("Y-m-d", strtotime("+" . $i . " day"), 'en');
        if (!in_array($date, $holidays)) {
            $name = parsidate("l (d F)", strtotime("+" . $i . " day"));
            $value = parsidate("Y-m-d", strtotime("+" . $i . " day"), 'en');
            if (in_array(parsidate("l", strtotime("+" . $i . " day")), $business_days)) {

                if ($i == 0) {
                    $today = false;
                    $timezone = get_option('timezone_string');
                    $dt = new DateTime("now", new DateTimeZone($timezone));
                    $current_hour = $dt->setTimestamp(time())->format('H');

                    $available_time = $current_hour + $allow_order_time;

                    if ($delivery_time_status == 1) {
                        if (have_rows('delivery_time', 'option')) {
                            while (have_rows('delivery_time', 'option')) {
                                the_row();
                                $start = get_sub_field('start');
                                $end = get_sub_field('end');
                                if ($available_time < $start) {
                                    $today = true;
                                }
                            }
                        }
                    } else {
                        $allow_order_end_time_today = get_field('allow_order_end_time_today', 'option');
                        if ($available_time < $allow_order_end_time_today) {
                            $today = true;
                        }
                    }

                    if ($today) {
                        $day_options[esc_attr($value)] = 'today، ' . esc_html($name);
                    } else {
                        $j++;
                    }
                } else {
                    if ($i == 1) {
                        $name = 'tommorrow، ' . esc_html($name);
                    }
                    $day_options[esc_attr($value)] = esc_html($name);
                }
            } else {
                $j++;
            }
        } else {
            $j++;
        }
    }

    woocommerce_form_field(
        'delivery_day',
        array(
            'type'          => 'select',
            'class'         => array('delivery_day_pickup form-row-first'),
            'label'         => __('Determine the day of delivery of the order'),
            'required'    => true,
            'options'     => $day_options
        ),
        $checkout->get_value('delivery_day')
    );

    if ($delivery_time_status == 1) {
        woocommerce_form_field(
            'delivery_time',
            array(
                'type'          => 'select',
                'class'         => array('delivery_time_pickup form-row-last'),
                'label'         => __('Determine the delivery time of the order'),
                'required'    => true,
                'options'     => $time_options
            ),
            $checkout->get_value('delivery_time')
        );
    }
    echo '</div>';
}


add_action('woocommerce_checkout_process', 'websima_delivery_checkout_process');
function websima_delivery_checkout_process()
{
    if ($_POST['delivery_day'] == 0) {
        wc_add_notice(esc_html('Determine the delivery time of the order.'), 'error');
    }

    if (get_field('delivery_time_status', 'option') == 1) {
        if ($_POST['delivery_time'] == 0) {
            wc_add_notice(esc_html('Determine the delivery time of the order.'), 'error');
        }
    }
}


add_action('woocommerce_checkout_update_order_meta', 'websima_delivery_checkout_update_order_meta');
function websima_delivery_checkout_update_order_meta($order_id)
{
    if (!empty($_POST['delivery_day'])) {
        update_post_meta(esc_attr($order_id), 'delivery_day', sanitize_text_field($_POST['delivery_day']));
    }

    if (get_field('delivery_time_status', 'option') == 1) {
        if (!empty($_POST['delivery_time'])) {
            update_post_meta(esc_attr($order_id), 'delivery_time', sanitize_text_field($_POST['delivery_time']));
        }
    }
}


add_action('woocommerce_admin_order_data_after_billing_address', 'websima_delivery_admin_order_data_after_billing_address', 10, 1);
function websima_delivery_admin_order_data_after_billing_address($order)
{
    $delivery_day = get_post_meta(esc_attr($order->id), 'delivery_day', true);
    $delivery_time = get_post_meta(esc_attr($order->id), 'delivery_time', true);

    if ($delivery_day) {
        echo '<p><strong>Order delivery date: </strong><span style="display: inline-block;">' . esc_html($delivery_day) . '</span></p>';
    }

    if ($delivery_time) {
        $delivery_time_array = explode("-", $delivery_time);
        $start = $delivery_time_array[0];
        $end = $delivery_time_array[1];
        $final_time = 'from ' . esc_html($start) . ' to ' . esc_html($end);
        echo '<p><strong>Order delivery time: </strong><span>' . esc_html($final_time) . '</span></p>';
    }
}

add_action('woocommerce_thankyou', 'websima_delivery_view_order_and_thankyou_page', 5);
add_action('woocommerce_view_order', 'websima_delivery_view_order_and_thankyou_page', 5);
function websima_delivery_view_order_and_thankyou_page($order_id)
{
    $delivery_day = get_post_meta(esc_attr($order_id), 'delivery_day', true);
    $delivery_time = get_post_meta(esc_attr($order_id), 'delivery_time', true);

    if ($delivery_day or $delivery_time) {
        echo '<div class="delivery-wrap">';
        echo '<h2>Day and time of order delivery</h2>';
        echo '<ul>';
        if ($delivery_day) {
            echo '<li><strong>Order delivery date: </strong><span class="d-inline-block">' . esc_html($delivery_day) . '</span></li>';
        }
        if ($delivery_time) {
            $delivery_time_array = explode("-", $delivery_time);
            $start = $delivery_time_array[0];
            $end = $delivery_time_array[1];
            $final_time = 'from ' . esc_html($start) . ' to ' . esc_html($end);
            echo '<li><strong>Order delivery time: </strong><span>' . esc_html($final_time) . '</span></li>';
        }
        echo '</ul>';
        echo '</div>';
    }
}

add_action('wcdn_after_info', 'websima_delivery_wcdn_after_info');
function websima_delivery_wcdn_after_info($order)
{
    websima_delivery_view_order_and_thankyou_page(esc_attr($order->get_id()));
}

if (get_field('delivery_time_status', 'option') == 1) {
    add_action('wp_ajax_websima_delivery_time_options', 'websima_delivery_time_options');
    add_action('wp_ajax_nopriv_websima_delivery_time_options', 'websima_delivery_time_options');
}
function websima_delivery_time_options()
{
    $allowed_html   =   array();
    $day = wp_kses($_POST['day'], $allowed_html);
    $times = array();
    $times[0] = 'select time';

    if (get_field('delivery_time_status', 'option') == 1) {
        if ($day) {
            if (parsidate("Y-m-d", strtotime("+0 day"), 'en') == $day) {
                $allow_order_time = get_field('allow_order_time', 'option');

                $timezone = get_option('timezone_string');
                $dt = new DateTime("now", new DateTimeZone($timezone));
                $current_hour = $dt->setTimestamp(time())->format('H');

                $available_time = $current_hour + $allow_order_time;


                if (have_rows('delivery_time', 'option')) {
                    while (have_rows('delivery_time', 'option')) {
                        the_row();
                        $start = get_sub_field('start');
                        $end = get_sub_field('end');
                        if ($available_time < $start) {
                            $times[esc_attr($start) . '-' . esc_attr($end)] = 'from ' . esc_html($start) . ' to ' . esc_html($end);
                        }
                    }
                }
            } else {
                if (have_rows('delivery_time', 'option')) {
                    while (have_rows('delivery_time', 'option')) {
                        the_row();
                        $start = get_sub_field('start');
                        $end = get_sub_field('end');
                        $times[esc_attr($start) . '-' . esc_attr($end)] = 'from ' . esc_html($start) . ' to ' . esc_html($end);
                    }
                }
            }

            $status = 1;
            $error = 'mission accomplished';
        } else {
            $status = 0;
            $error = 'Select the day first';
        }
    } else {
        $status = 0;
        $error = 'The time selection option is not enabled';
    }


    $resp = array('status' => $status, 'msg' => $error, 'times' => $times);
    header("Content-Type: application/json");
    echo json_encode($resp);
    die();
}
