<?php

/**
 * Account detection.
 */
add_action('wp_ajax_websima_auth_account_detection', 'websima_auth_account_detection');
add_action('wp_ajax_nopriv_websima_auth_account_detection', 'websima_auth_account_detection');
function websima_auth_account_detection()
{
    $rules[] = "required,mobile," . 'Please enter your mobile number';
    $rules[] = "valid_mobile,mobile," . 'The entered mobile number is not valid';

    $action = '';
    $strategy = get_field('auth_password_strategy', 'option');
    unset($_SESSION['websima_auth']);
    $errors = validateFields($_POST, $rules);
    if (!empty($errors)) {

        foreach ($errors as $error) {
            $status = 0;
            $error = "$error";
        }
        $resp = array('status' => $status, 'msg' => $error, 'action' => $action, 'strategy' => $strategy);
        header("Content-Type: application/json");
        echo json_encode($resp);
        die();
    } else {
        $allowed_html   =   array();
        $mobile = wp_kses(websima_xss_clean($_POST['mobile']), $allowed_html);

        if (!wp_verify_nonce(websima_xss_clean($_POST['account_detection_nonce_field']), 'account_detection_nonce')) {
            $status = 0;
            $error = 'A security error has occurred';
        } else {
            if (!is_user_logged_in()) {
                $user = get_user_by('login', esc_attr($mobile));
                if (!$user) {
                    $status = 1;
                    $error = 'The verification code was texted to your mobile number';
                    $action = 'register';

                    $verify_mobile_template = get_field('auth_sms_verify_mobile_template', 'option');
                    $verification_code = websima_auth_generate_password();
                    websima_auth_send_sms(esc_attr($mobile), esc_attr($verify_mobile_template), esc_attr($verification_code));

                    $_SESSION['websima_auth']['status'] = 'not-registered';
                    $_SESSION['websima_auth']['mobile'] = esc_attr($mobile);
                    $_SESSION['websima_auth']['verification_code'] = esc_attr($verification_code);
                } else {
                    $user_id = $user->data->ID;
                    $user_data = get_userdata(esc_attr($user_id));
                    if (in_array('customer', $user_data->roles)) {
                        $success_message = 'User info found';
                        $action = 'login';

                        $_SESSION['websima_auth']['status'] = 'registered';
                        $_SESSION['websima_auth']['user_id'] = esc_attr($user_id);
                        $_SESSION['websima_auth']['mobile'] = esc_attr($mobile);

                        $auth_password_strategy = get_field('auth_password_strategy', 'option');
                        if ($auth_password_strategy == 'otp') {
                            $otp_template = get_field('auth_sms_otp_template', 'option');
                            $generate_password = websima_auth_generate_password();
                            wp_set_password(esc_attr($generate_password), esc_attr($user_id));
                            websima_auth_send_sms(esc_attr($mobile), esc_attr($otp_template), esc_attr($generate_password));
                            $success_message = 'One-time password was texted to your mobile number';
                        }

                        $status = 1;
                        $error = esc_html($success_message);
                    } else {
                        $status = 0;
                        $error = 'Your access level is not enough to use this system';
                    }
                }
            } else {
                $status = 0;
                $error = 'You are currently logged in';
            }
        }


        $resp = array('status' => $status, 'msg' => $error, 'action' => $action, 'strategy' => $strategy);
        header("Content-Type: application/json");
        echo json_encode($resp);
        die();
    }
}

add_action('wp_ajax_websima_auth_account_login', 'websima_auth_account_login');
add_action('wp_ajax_nopriv_websima_auth_account_login', 'websima_auth_account_login');
function websima_auth_account_login()
{
    $rules[] = "required,password," . 'Please enter your password';
    $rules[] = "digits_only,password," . 'Password must be numeric';
    $rules[] = "length=6,password," . 'Password length must be 6 digits';

    $errors = validateFields($_POST, $rules);
    if (!empty($errors)) {

        foreach ($errors as $error) {
            $status = 0;
            $error = "$error";
        }
        $resp = array('status' => $status, 'msg' => $error);
        header("Content-Type: application/json");
        echo json_encode($resp);
        die();
    } else {
        $allowed_html   =   array();
        $password = wp_kses(websima_xss_clean($_POST['password']), $allowed_html);

        if (!wp_verify_nonce(websima_xss_clean($_POST['account_login_nonce_field']), 'account_login_nonce')) {
            $status = 0;
            $error = 'A security error has occurred';
        } else {
            if (!is_user_logged_in()) {
                if (isset($_SESSION['websima_auth'])) {
                    if ($_SESSION['websima_auth']['status'] == 'registered') {
                        if ($_SESSION['websima_auth']['user_id']) {
                            $user_id = $_SESSION['websima_auth']['user_id'];
                            $user = get_user_by('ID', esc_attr($user_id));
                            $user_data = get_userdata(esc_attr($user_id));
                            if (in_array('customer', $user_data->roles)) {
                                $user_check = apply_filters('authenticate', '', $user->user_login, $password);
                                if (!is_wp_error($user_check)) {
                                    if (wp_check_password($password, $user->user_pass, $user->ID)) {
                                        wp_set_current_user($user->ID, $user->user_login);
                                        do_action('set_current_user');
                                        wp_set_auth_cookie($user->ID, false);

                                        unset($_SESSION['websima_auth']);

                                        $status = 1;
                                        $error = 'You have successfully logged in to your account';
                                    } else {
                                        $status = 0;
                                        $error = 'The password entered is incorrect';
                                    }
                                } else {
                                    $status = 0;
                                    $error = 'The password entered is incorrect';
                                }
                            } else {
                                $status = 0;
                                $error = 'Your access level is not enough to use this system';
                            }
                        } else {
                            $status = 0;
                            $error = 'A security error has occurred';
                        }
                    } else {
                        $status = 0;
                        $error = 'A security error has occurred';
                    }
                } else {
                    $status = 0;
                    $error = 'A security error has occurred';
                }
            } else {
                $status = 0;
                $error = 'You are currently logged in';
            }
        }


        $resp = array('status' => $status, 'msg' => $error);
        header("Content-Type: application/json");
        echo json_encode($resp);
        die();
    }
}

add_action('wp_ajax_websima_auth_account_resend_code', 'websima_auth_account_resend_code');
add_action('wp_ajax_nopriv_websima_auth_account_resend_code', 'websima_auth_account_resend_code');
function websima_auth_account_resend_code()
{
    $rules[] = "required,type," . 'An error has occurred';

    $errors = validateFields($_POST, $rules);
    if (!empty($errors)) {

        foreach ($errors as $error) {
            $status = 0;
            $error = "$error";
        }
        $resp = array('status' => $status, 'msg' => $error);
        header("Content-Type: application/json");
        echo json_encode($resp);
        die();
    } else {
        $allowed_html   =   array();
        $type = wp_kses(websima_xss_clean($_POST['type']), $allowed_html);

        if (!wp_verify_nonce(websima_xss_clean($_POST['nonce']), 'auth_nonce')) {
            $status = 0;
            $error = 'An error has occurred';
        } else {
            if ($type == 'otp-password' or $type == 'reset-password') {
                $auth_password_strategy = get_field('auth_password_strategy', 'option');
                if (($type == 'otp-password' and $auth_password_strategy == 'otp') or ($type == 'reset-password' and $auth_password_strategy != 'otp')) {
                    if (!is_user_logged_in()) {
                        if (isset($_SESSION['websima_auth'])) {
                            if ($_SESSION['websima_auth']['status'] == 'registered') {
                                if ($_SESSION['websima_auth']['user_id']) {
                                    $user_id = $_SESSION['websima_auth']['user_id'];
                                    $mobile = $_SESSION['websima_auth']['mobile'];
                                    $user_data = get_userdata(esc_attr($user_id));
                                    if (in_array('customer', $user_data->roles)) {
                                        if ($mobile) {
                                            $otp_template = get_field('auth_sms_otp_template', 'option');
                                            $reset_password_template = get_field('auth_sms_reset_password_template', 'option');
                                            if ($type == 'otp-password') {
                                                $template = $otp_template;
                                            } elseif ($type == 'reset-password') {
                                                $template = $reset_password_template;
                                            }
                                            $generate_password = websima_auth_generate_password();
                                            wp_set_password(esc_attr($generate_password), esc_attr($user_id));
                                            websima_auth_send_sms(esc_attr($mobile), esc_attr($template), esc_attr($generate_password));

                                            $status = 1;
                                            $error = 'password sended';
                                        } else {
                                            $status = 0;
                                            $error = 'An error has occurred';
                                        }
                                    } else {
                                        $status = 0;
                                        $error = 'Your access level is not enough to use this system';
                                    }
                                } else {
                                    $status = 0;
                                    $error = 'An error has occurred';
                                }
                            } else {
                                $status = 0;
                                $error = 'An error has occurred';
                            }
                        } else {
                            $status = 0;
                            $error = 'An error has occurred';
                        }
                    } else {
                        $status = 0;
                        $error = 'You are currently logged in';
                    }
                } else {
                    $status = 0;
                    $error = 'An error has occurred';
                }
            } elseif ($type == 'verification-code') {
                if (!is_user_logged_in()) {
                    if (isset($_SESSION['websima_auth'])) {
                        if ($_SESSION['websima_auth']['status'] == 'not-registered') {
                            $mobile = $_SESSION['websima_auth']['mobile'];
                            if ($mobile) {
                                $verify_mobile_template = get_field('auth_sms_verify_mobile_template', 'option');
                                $verification_code = websima_auth_generate_password();
                                websima_auth_send_sms(esc_attr($mobile), esc_attr($verify_mobile_template), esc_attr($verification_code));
                                $_SESSION['websima_auth']['verification_code'] = esc_attr($verification_code);

                                $status = 1;
                                $error = 'SMS verification code';
                            } else {
                                $status = 0;
                                $error = 'An error has occurred';
                            }
                        } else {
                            $status = 0;
                            $error = 'An error has occurred';
                        }
                    } else {
                        $status = 0;
                        $error = 'An error has occurred';
                    }
                } else {
                    $status = 0;
                    $error = 'You are currently logged in';
                }
            } else {
                $status = 0;
                $error = 'An error has occurred';
            }
        }


        $resp = array('status' => $status, 'msg' => $error);
        header("Content-Type: application/json");
        echo json_encode($resp);
        die();
    }
}

add_action('wp_ajax_websima_auth_account_register', 'websima_auth_account_register');
add_action('wp_ajax_nopriv_websima_auth_account_register', 'websima_auth_account_register');
function websima_auth_account_register()
{
    $rules[] = "required,verification_code," . 'Please enter your verification code';
    $rules[] = "digits_only,verification_code," . 'The verification code must be numeric';
    $rules[] = "length=6,verification_code," . 'The length of the verification code must be 6 digits';

    $extra_step = false;
    $errors = validateFields($_POST, $rules);
    if (!empty($errors)) {

        foreach ($errors as $error) {
            $status = 0;
            $error = "$error";
        }
        $resp = array('status' => $status, 'msg' => $error, 'extra_step' => $extra_step);
        header("Content-Type: application/json");
        echo json_encode($resp);
        die();
    } else {
        $allowed_html   =   array();
        $verification_code = wp_kses(websima_xss_clean($_POST['verification_code']), $allowed_html);

        if (!wp_verify_nonce(websima_xss_clean($_POST['account_register_nonce_field']), 'account_register_nonce')) {
            $status = 0;
            $error = 'A security error has occurred';
        } else {
            if (!is_user_logged_in()) {
                if (isset($_SESSION['websima_auth'])) {
                    if ($_SESSION['websima_auth']['status'] == 'not-registered') {
                        if ($_SESSION['websima_auth']['verification_code']) {
                            $mobile = $_SESSION['websima_auth']['mobile'];
                            $valid_verification_code = $_SESSION['websima_auth']['verification_code'];
                            if ($verification_code == $valid_verification_code) {
                                $website_domain = get_field('auth_website_domain', 'option');
                                $email = esc_attr($mobile) . '@' . esc_attr($website_domain);
                                $password = websima_auth_generate_password();

                                $user_id = wp_insert_user(array(
                                    'user_pass' => apply_filters('pre_user_user_pass', $password),
                                    'user_login' => apply_filters('pre_user_user_login', $mobile),
                                    'user_email' => apply_filters('pre_user_user_email', $email),
                                    'show_admin_bar_front' => 'false',
                                    'role' => 'customer'
                                ));
                                if (is_wp_error($user_id)) {
                                    $status = 0;
                                    $error = 'A user with this mobile number is already a member of the site';
                                } else {
                                    //do_action('user_register', esc_attr($user_id));
                                    update_user_meta(esc_attr($user_id), 'mobile', esc_attr($mobile));
                                    //update_user_meta(esc_attr($user_id), 'show_admin_bar_front', false);

                                    if (websima_auth_register_extra_step()) {
                                        $_SESSION['websima_auth']['status'] = 'registered-now';
                                        $_SESSION['websima_auth']['user_id'] = esc_attr($user_id);
                                    } else {
                                        $user = get_user_by('ID', esc_attr($user_id));
                                        wp_set_current_user($user->ID, $user->user_login);
                                        do_action('set_current_user');
                                        wp_set_auth_cookie($user->ID, false);

                                        unset($_SESSION['websima_auth']);
                                    }


                                    $password_strategy = get_field('auth_password_strategy', 'option');
                                    $simple_register_template = get_field('auth_sms_simple_register_template', 'option');
                                    $advanced_register_template = get_field('auth_sms_advanced_register_template', 'option');
                                    if ($password_strategy == 'system') {
                                        $success_message = 'Your subscription was successful and your password was texted';
                                        websima_auth_send_sms(esc_attr($mobile), esc_attr($advanced_register_template), esc_attr($password));
                                    } else {
                                        $success_message = 'Your subscription was successful';
                                        websima_auth_send_sms(esc_attr($mobile), esc_attr($simple_register_template), 'شد');
                                    }

                                    $status = 1;
                                    $error = esc_html($success_message);
                                    $extra_step = websima_auth_register_extra_step();
                                }
                            } else {
                                $status = 0;
                                $error = 'The verification code entered is incorrect';
                            }
                        } else {
                            $status = 0;
                            $error = 'An error has occurred';
                        }
                    } else {
                        $status = 0;
                        $error = 'An error has occurred';
                    }
                } else {
                    $status = 0;
                    $error = 'An error has occurred';
                }
            } else {
                $status = 0;
                $error = 'You are currently logged in';
            }
        }


        $resp = array('status' => $status, 'msg' => $error, 'extra_step' => $extra_step);
        header("Content-Type: application/json");
        echo json_encode($resp);
        die();
    }
}

add_action('wp_ajax_websima_auth_account_profile', 'websima_auth_account_profile');
add_action('wp_ajax_nopriv_websima_auth_account_profile', 'websima_auth_account_profile');
function websima_auth_account_profile()
{
    $fullname_active = get_field('auth_fullname_active', 'option');
    $email_active = get_field('auth_email_active', 'option');
    $password_strategy = get_field('auth_password_strategy', 'option');

    if ($fullname_active) {
        $rules[] = "required,first_name," . 'enter your name';
        $rules[] = "required,last_name," . 'enter your last name';
    }
    if ($email_active) {
        $rules[] = "required,email," . 'enter your email';
        $rules[] = "valid_email,email," . 'The entered email is not valid';
    }
    if ($password_strategy == 'user_choice') {
        $rules[] = "required,new_password," . 'enter your password';
        $rules[] = "digits_only,new_password," . 'Password must be numeric';
        $rules[] = "length=6,new_password," . 'Password length must be 6 digits';
    }

    $errors = validateFields($_POST, $rules);
    if (!empty($errors)) {

        foreach ($errors as $error) {
            $status = 0;
            $error = "$error";
        }
        $resp = array('status' => $status, 'msg' => $error);
        header("Content-Type: application/json");
        echo json_encode($resp);
        die();
    } else {
        $allowed_html   =   array();

        if (!wp_verify_nonce(websima_xss_clean($_POST['account_profile_nonce_field']), 'account_profile_nonce')) {
            $status = 0;
            $error = 'A security error has occurred';
        } else {
            if (!is_user_logged_in()) {
                if (isset($_SESSION['websima_auth'])) {
                    if ($_SESSION['websima_auth']['status'] == 'registered-now') {
                        if ($_SESSION['websima_auth']['user_id']) {
                            if (websima_auth_register_extra_step()) {
                                $user_id = $_SESSION['websima_auth']['user_id'];
                                $user_data = get_userdata(esc_attr($user_id));
                                if (in_array('customer', $user_data->roles)) {
                                    if ($fullname_active) {
                                        $first_name = wp_kses(websima_xss_clean($_POST['first_name']), $allowed_html);
                                        $last_name = wp_kses(websima_xss_clean($_POST['last_name']), $allowed_html);

                                        update_user_meta(esc_attr($user_id), 'first_name', sanitize_text_field($first_name));
                                        update_user_meta(esc_attr($user_id), 'billing_first_name', sanitize_text_field($first_name));
                                        update_user_meta(esc_attr($user_id), 'last_name', sanitize_text_field($last_name));
                                        update_user_meta(esc_attr($user_id), 'billing_last_name', sanitize_text_field($last_name));

                                        $user_args = array(
                                            'ID'           => esc_attr($user_id),
                                            'display_name' => esc_html($first_name) . ' ' . esc_html($last_name),
                                        );
                                        wp_update_user($user_args);
                                    }

                                    if ($email_active) {
                                        $email = wp_kses(websima_xss_clean($_POST['email']), $allowed_html);
                                        update_user_meta(esc_attr($user_id), 'billing_email', sanitize_email($email));
                                    }

                                    if ($password_strategy == 'user_choice') {
                                        $new_password = wp_kses(websima_xss_clean($_POST['new_password']), $allowed_html);
                                        wp_set_password(esc_attr($new_password), esc_attr($user_id));
                                    }

                                    $user = get_user_by('ID', esc_attr($user_id));
                                    wp_set_current_user($user->ID, $user->user_login);
                                    do_action('set_current_user');
                                    wp_set_auth_cookie($user->ID, false);

                                    do_action('websima_auth_account_profile_completed');

                                    unset($_SESSION['websima_auth']);

                                    $status = 1;
                                    $error = 'Your information has been successfully registered';
                                } else {
                                    $status = 0;
                                    $error = 'Your access level is not enough to use this system';
                                }
                            } else {
                                $status = 0;
                                $error = 'A security error has occurred';
                            }
                        } else {
                            $status = 0;
                            $error = 'A security error has occurred';
                        }
                    } else {
                        $status = 0;
                        $error = 'A security error has occurred';
                    }
                } else {
                    $status = 0;
                    $error = 'A security error has occurred';
                }
            } else {
                $status = 0;
                $error = 'You are currently logged in';
            }
        }


        $resp = array('status' => $status, 'msg' => $error);
        header("Content-Type: application/json");
        echo json_encode($resp);
        die();
    }
}
