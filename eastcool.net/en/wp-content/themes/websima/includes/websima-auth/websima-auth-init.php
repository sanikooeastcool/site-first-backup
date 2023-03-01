<?php
add_action('init', 'websima_auth_init');
function websima_auth_init()
{
    if (function_exists('acf_add_options_page')) {
        acf_add_options_sub_page(array(
            'page_title'     => 'Sign-Up',
            'menu_title'    => 'Sign-Up',
            'menu_slug'        => 'websima-package-auth-settings',
            'parent_slug'    => 'websima-package-general-settings',
            'capability'    => 'edit_posts'
        ));
    }

    websima_auth_woocommerce_admin_config();

    session_start();
}

add_action('wp_head', 'websima_auth_wp_head');
function websima_auth_wp_head()
{
    if (isset($_SESSION['websima_auth'])) {
        unset($_SESSION['websima_auth']);
    }
}

function websima_auth_woocommerce_admin_config()
{
    if (get_option('websima_auth_admin_config_status') != 'yes') {
        $actions = array('new_order', 'cancelled_order', 'failed_order', 'customer_on_hold_order', 'customer_processing_order', 'customer_completed_order', 'customer_refunded_order', 'customer_note', 'customer_reset_password', 'customer_new_account');
        foreach ($actions as $action) {
            $option = 'woocommerce_' . esc_attr($action) . '_settings';
            $email_settings = get_option(esc_attr($option));
            if (!is_array($email_settings)) {
                $email_settings = array();
            }
            $email_settings['enabled'] = 'no';
            update_option(esc_attr($option), $email_settings);
        }

        update_option('woocommerce_enable_guest_checkout', 'no');
        update_option('woocommerce_enable_checkout_login_reminder', 'no');
        update_option('woocommerce_enable_signup_and_login_from_checkout', 'no');

        update_option('websima_auth_admin_config_status', 'yes');
    }
}

add_filter('woocommerce_settings_tabs_array', 'websima_auth_woocommerce_settings_tabs_array', 1000, 1);
function websima_auth_woocommerce_settings_tabs_array($tabs_array)
{
    unset($tabs_array['email']);
    unset($tabs_array['account']);
    return $tabs_array;
}

//include 'includes/validation-2.3.3.php';
//include 'includes/xss_clean.php';
include 'includes/account-forms.php';
include 'includes/account-functions.php';

add_action('wp_enqueue_scripts', 'websima_auth_wp_enqueue_scripts');
function websima_auth_wp_enqueue_scripts()
{
    $user_profile_rules = array();
    $user_profile_messages = array();

    if (websima_auth_register_extra_step()) {
        $fullname_active = get_field('auth_fullname_active', 'option');
        $email_active = get_field('auth_email_active', 'option');
        $password_strategy = get_field('auth_password_strategy', 'option');

        if ($fullname_active) {
            $user_profile_rules['first_name']['required'] = true;
            $user_profile_rules['last_name']['required'] = true;
            $user_profile_messages['first_name']['required'] = 'enter your first name';
            $user_profile_messages['last_name']['required'] = 'enter your last name';
        }
        if ($email_active) {
            $user_profile_rules['email']['required'] = true;
            $user_profile_rules['email']['email'] = true;
            $user_profile_messages['email']['required'] = 'enter your email address';
            $user_profile_messages['email']['email'] = 'The entered email is not valid';
        }
        if ($password_strategy == 'user_choice') {
            $user_profile_rules['new_password']['required'] = true;
            $user_profile_rules['new_password']['digits'] = true;
            $user_profile_rules['new_password']['minlength'] = 6;
            $user_profile_rules['new_password']['maxlength'] = 6;
            $user_profile_messages['new_password']['required'] = 'Please enter your password';
            $user_profile_messages['new_password']['digits'] = 'Password must be numeric';
            $user_profile_messages['new_password']['minlength'] = 'Password length must be 6 digits';
            $user_profile_messages['new_password']['maxlength'] = 'Password length must be 6 digits';
        }
    }


    wp_enqueue_style('websima_auth-style', get_template_directory_uri() . '/includes/websima-auth/assets/css/style.css');

    wp_enqueue_script('jquery.validate', get_template_directory_uri() . '/includes/websima-auth/assets/js/jquery.validate.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('websima_auth-user', get_template_directory_uri() . '/includes/websima-auth/assets/js/user.js', array('jquery'), '1.0.0', true);
    wp_localize_script(
        'websima_auth-user',
        'auth_user_dyn_data',
        array(
            'admin_ajax'  =>  admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('auth_nonce'),
            'user_profile_rules' => $user_profile_rules,
            'user_profile_messages' => $user_profile_messages,
        )
    );
}

function websima_auth_send_sms_old($mobile, $template, $token1, $token2 = null, $token3 = null, $token10 = null, $token20 = null)
{
    $api = get_field('auth_sms_api', 'option');
    $url = 'https://api.kavenegar.com/v1/' . $api . '/verify/lookup.json?receptor=' . $mobile . '&template=' . $template . '&token=' . urlencode($token1) . '&token2=' . urlencode($token2) . '&token3=' . urlencode($token3) . '&token10=' . urlencode($token10) . '&token20=' . urlencode($token20);
    $update = file_get_contents($url);
}

function websima_auth_send_sms($mobile, $template, $token1, $token2 = null, $token3 = null, $token10 = null, $token20 = null)
{
    $api = get_field('auth_sms_api', 'option');
    $url = 'https://api.kavenegar.com/v1/' . $api . '/verify/lookup.json?receptor=' . $mobile . '&template=' . $template . '&token=' . urlencode($token1) . '&token2=' . urlencode($token2) . '&token3=' . urlencode($token3) . '&token10=' . urlencode($token10) . '&token20=' . urlencode($token20);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
}

function websima_auth_generate_password()
{
    $string = '0123456789';
    $generated_password = substr(str_shuffle($string), 0, 6);
    return $generated_password;
}

function websima_auth_register_extra_step()
{
    $extra_step = false;

    $fullname_active = get_field('auth_fullname_active', 'option');
    $email_active = get_field('auth_email_active', 'option');
    $password_strategy = get_field('auth_password_strategy', 'option');

    if ($fullname_active or $email_active or $password_strategy == 'user_choice') {
        $extra_step = true;
    }

    return $extra_step;
}

function websima_auth_fullname()
{
    $fullname = '';
    $user_id = get_current_user_id();
    $first_name = get_user_meta(esc_attr($user_id), 'first_name', true);
    $last_name = get_user_meta(esc_attr($user_id), 'last_name', true);

    $billing_first_name = get_user_meta(esc_attr($user_id), 'billing_first_name', true);
    $billing_last_name = get_user_meta(esc_attr($user_id), 'billing_last_name', true);

    if ($first_name or $last_name) {
        $fullname = esc_html($first_name) . ' ' . esc_html($last_name);
    } elseif ($billing_first_name or $billing_last_name) {
        $fullname = esc_html($billing_first_name) . ' ' . esc_html($billing_last_name);
    }

    return $fullname;
}

function websima_auth_modal_btn()
{
    if (!is_user_logged_in()) {
        echo '<button class="websima-auth-modal-btn" data-toggle="modal" data-target="#websima-auth-modal">';
        echo '<i class="icon-user"></i>';
        echo '<span class="label">Login / Register</span>';
        echo '</button>';
    } else {
        $fullname = websima_auth_fullname();
        echo '<a href="' . get_permalink(wc_get_page_id('myaccount')) . '">';
        echo '<i class="icon-user"></i>';
        //        echo '<span>';
        //            echo 'سلام ';
        //        echo '</span>';
        //            if($fullname){ echo esc_html($fullname); }
        //        echo '</a>';
        echo '<a href="' . wp_logout_url(get_permalink(wc_get_page_id('myaccount'))) . '">';
        echo '<span class="exit-acc">';
        echo 'exit';
        echo '</span>';
        echo '</a>';
    }
}

function websima_auth_modal()
{
    if (!is_user_logged_in()) {
        echo '<div class="modal fade" id="websima-auth-modal" tabindex="-1" role="dialog" aria-labelledby="websima-auth-modal-label" aria-hidden="true">';
        echo '<div class="modal-dialog" role="document">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '<h5 class="modal-title" id="websima-auth-modal-label">Login / Register</h5>';
        echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        echo '</div>';
        echo '<div class="modal-body">';
        websima_auth_account_detection_form();
        websima_auth_account_login_form();
        websima_auth_account_register_form();
        websima_auth_account_profile_form();
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

add_filter('woocommerce_save_account_details_required_fields', 'websima_auth_save_account_details_required_fields');
function websima_auth_save_account_details_required_fields($required_fields)
{
    unset($required_fields['account_email']);
    unset($required_fields['account_display_name']);
    return $required_fields;
}

add_action('woocommerce_save_account_details_errors', 'websima_auth_woocommerce_save_account_details_errors', 10, 1);
function websima_auth_woocommerce_save_account_details_errors($validation_errors)
{
    $user = get_user_by('ID', get_current_user_id());
    $current_user_email = $user->data->user_email;
    $current_user_display_name = $user->data->display_name;

    if (empty($_POST['account_email'])) {
        $validation_errors->add('auth-error-1', 'An error has occurred');
    } elseif (!is_email($_POST['account_email'])) {
        $validation_errors->add('auth-error-2', 'An error has occurred');
    } elseif ($_POST['account_email'] != $current_user_email) {
        $validation_errors->add('auth-error-3', 'An error has occurred');
    }

    if (empty($_POST['account_display_name'])) {
        $validation_errors->add('auth-error-4', 'An error has occurred');
    } elseif ($_POST['account_display_name'] != $current_user_display_name) {
        $validation_errors->add('auth-error-5', 'An error has occurred');
    }

    if ($_POST['password_current'] != '' or $_POST['password_1'] != '' or $_POST['password_2'] != '') {
        $validation_errors->add('auth-error-6', 'An error has occurred');
    }
}

add_action('template_redirect', 'websima_auth_template_redirect');
function websima_auth_template_redirect()
{
    if (is_account_page() and !is_user_logged_in()) {
        wp_redirect(home_url('/'));
        exit;
    }
}
