<?php
add_action('websima_invoice_checkout_custom_fields', 'websima_invoice_checkout_custom_fields_html');
function websima_invoice_checkout_custom_fields_html($checkout)
{
    echo '<div id="invoice-extra-fields">';
    woocommerce_form_field('customer-type', array(
        'type'          => 'radio',
        'label'         => 'Buyer type',
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-wide'),
        'select2 '      => false,
        'options'       => array(
            'haghighi'  => 'actual',
            'hoghooghi' => 'Legal'
        ),
    ), $checkout->get_value('customer-type'));

    woocommerce_form_field('national-code', array(
        'type'          => 'text',
        'label'         => 'National Code',
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-wide'),
    ), $checkout->get_value('national-code'));

    woocommerce_form_field('company', array(
        'type'          => 'text',
        'label'         => 'company name',
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-wide'),
    ), $checkout->get_value('company'));

    woocommerce_form_field('register-number', array(
        'type'          => 'text',
        'label'         => 'register number',
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-wide'),
    ), $checkout->get_value('register-number'));

    woocommerce_form_field('economic-code', array(
        'type'          => 'text',
        'label'         => 'economic code',
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-wide'),
    ), $checkout->get_value('economic-code'));

    woocommerce_form_field('national-id', array(
        'type'          => 'text',
        'label'         => 'national id',
        'placeholder'   => '',
        'required'      => true,
        'class'         => array('form-row-wide'),
    ), $checkout->get_value('national-id'));
    echo '</div>';
}

add_action('woocommerce_checkout_process', 'websima_invoice_checkout_custom_fields_process');
function websima_invoice_checkout_custom_fields_process()
{
    if (!$_POST['customer-type']) {
        wc_add_notice('Please specify the type of buyer.', 'error');
    } else {
        if ($_POST['customer-type'] == 'haghighi') {
            if (!$_POST['national-code']) {
                wc_add_notice('Please enter your national code.', 'error');
            } elseif (!websima_invoice_national_code_validation($_POST['national-code'])) {
                wc_add_notice('The entered national code is invalid', 'error');
            }
        } elseif ($_POST['customer-type'] == 'hoghooghi') {
            if (!$_POST['company']) {
                wc_add_notice('Please enter the company name.', 'error');
            }
            if (!$_POST['register-number']) {
                wc_add_notice('Please enter the company registration number.', 'error');
            }
            if (!$_POST['economic-code']) {
                wc_add_notice('Please enter the economic code of the company.', 'error');
            }
            if (!$_POST['national-id']) {
                wc_add_notice('Please enter the national ID of the company.', 'error');
            }
        }
    }
}

add_action('woocommerce_checkout_update_order_meta', 'websima_invoice_woocommerce_checkout_update_order_meta');
function websima_invoice_woocommerce_checkout_update_order_meta($order_id)
{
    if ($_POST['customer-type']) {
        update_post_meta(esc_attr($order_id), 'customer-type', esc_attr($_POST['customer-type']));
    }
    if ($_POST['national-code']) {
        update_post_meta(esc_attr($order_id), 'national-code', esc_attr($_POST['national-code']));
    }
    if ($_POST['company']) {
        update_post_meta(esc_attr($order_id), 'company', esc_attr($_POST['company']));
    }
    if ($_POST['register-number']) {
        update_post_meta(esc_attr($order_id), 'register-number', esc_attr($_POST['register-number']));
    }
    if ($_POST['economic-code']) {
        update_post_meta(esc_attr($order_id), 'economic-code', esc_attr($_POST['economic-code']));
    }
    if ($_POST['national-id']) {
        update_post_meta(esc_attr($order_id), 'national-id', esc_attr($_POST['national-id']));
    }
}

add_action('woocommerce_admin_order_data_after_billing_address', 'websima_invoice_admin_order_data_after_billing_address', 10, 1);
function websima_invoice_admin_order_data_after_billing_address($order)
{
    $customer_type = get_post_meta(esc_attr($order->id), 'customer-type', true);
    $national_code = get_post_meta(esc_attr($order->id), 'national-code', true);
    $company = get_post_meta(esc_attr($order->id), 'company', true);
    $economic_code = get_post_meta(esc_attr($order->id), 'economic-code', true);
    $register_number = get_post_meta(esc_attr($order->id), 'register-number', true);
    $national_id = get_post_meta(esc_attr($order->id), 'national-id', true);

    echo '<p><strong>Buyer type:</strong> <span>' . (($customer_type != '') ? esc_html($customer_type) : '-') . '</span></p>';
    echo '<p><strong>National Code:</strong> <span>' . (($national_code != '') ? esc_html($national_code) : '-') . '</span></p>';
    echo '<p><strong>company name:</strong> <span>' . (($company != '') ? esc_html($company) : '-') . '</span></p>';
    echo '<p><strong>economic code:</strong> <span>' . (($economic_code != '') ? esc_html($economic_code) : '-') . '</span></p>';
    echo '<p><strong>register number:</strong> <span>' . (($register_number != '') ? esc_html($register_number) : '-') . '</span></p>';
    echo '<p><strong>national id:</strong> <span>' . (($national_id != '') ? esc_html($national_id) : '-') . '</span></p>';
}

add_action('wp_footer', 'websima_invoice_checkout_script');
function websima_invoice_checkout_script()
{
    if (is_checkout() && !is_wc_endpoint_url()) {
        WC()->session->__unset('customer-type');
?>
        <script type="text/javascript">
            jQuery(function($) {
                jQuery('form.checkout').on('change', '#customer-type_field input[type="radio"]', function() {
                    var customer_type = jQuery(this).val();

                    if (customer_type == 'haghighi') {
                        jQuery('form.checkout input#national-code').val('');
                    } else if (customer_type == 'hoghooghi') {
                        jQuery('form.checkout input#national-id').val('');
                    }

                    jQuery('form.checkout #customer-type_field').addClass('disable-field');
                    jQuery("form.checkout #customer-type_field input[type='radio']").attr('disabled', true);

                    jQuery.ajax({
                        type: 'POST',
                        url: "<?php echo esc_url(admin_url('admin-ajax.php')); ?>",
                        data: {
                            'action': 'websima_invoice_set_session',
                            'customer_type': customer_type,
                        },
                        success: function(result) {
                            jQuery('body').trigger('update_checkout');
                            jQuery('form.checkout #customer-type_field').removeClass('disable-field');
                            jQuery("form.checkout #customer-type_field input[type='radio']").attr('disabled', false);
                        },
                        error: function(error) {
                            jQuery('form.checkout #customer-type_field').removeClass('disable-field');
                            jQuery("form.checkout #customer-type_field input[type='radio']").attr('disabled', false);
                        }
                    });
                });
            });
        </script>
<?php
    }
}

add_action('wp_ajax_websima_invoice_set_session', 'websima_invoice_set_session');
add_action('wp_ajax_nopriv_websima_invoice_set_session', 'websima_invoice_set_session');
function websima_invoice_set_session()
{
    if (isset($_POST['customer_type'])) {
        $customer_type = esc_attr($_POST['customer_type']);
        WC()->session->set('customer-type', esc_attr($customer_type));
        echo json_encode(esc_attr($customer_type));
    }
}

add_action('woocommerce_cart_calculate_fees', 'websima_invoice_add_vat', 20, 1);
function websima_invoice_add_vat($cart)
{
    if (is_admin() && !defined('DOING_AJAX'))
        return;
    $invoice_tax_value = get_field('invoice_tax_value', 'option');
    $tax_factor = $cart->cart_contents_total;
    $total_factor = ($tax_factor * $invoice_tax_value) / 100;
    $customer_type = WC()->session->get('customer-type');

    if (is_checkout()) {
        if ($customer_type === 'hoghooghi') {
            $label = 'tax';
            $cost  = esc_attr($total_factor);
        } elseif ($customer_type === 'haghighi') {
            $label = 'invoice';
            $cost  = 0;
        } else {
            $label = 'invoice';
            $cost  = 0;
        }
    }
    if (isset($cost)) {
        $cart->add_fee(esc_html($label), esc_attr($cost));
    }
}
