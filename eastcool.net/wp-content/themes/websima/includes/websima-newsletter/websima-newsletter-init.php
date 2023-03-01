<?php
/**
 * Newsletter initialize.
 */
add_action('init','websima_newsletter_init');
function websima_newsletter_init(){
    if(function_exists('acf_add_options_page')){
        acf_add_options_sub_page(array(
            'page_title' 	=> 'خبرنامه',
            'menu_title'	=> 'خبرنامه',
            'menu_slug'	    => 'websima-package-newsletter-settings',
            'parent_slug'	=> 'websima-package-general-settings',
            'capability'	=> 'edit_posts'
        ));
    }
}

/**
 * Enqueue styles and scripts.
 */
add_action('wp_enqueue_scripts','websima_newsletter_enqueue_scripts');
function websima_newsletter_enqueue_scripts(){
    wp_enqueue_script('jquery.validate', get_template_directory_uri() . '/includes/websima-newsletter/assets/js/jquery.validate.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('websima_newsletter-script', get_template_directory_uri() . '/includes/websima-newsletter/assets/js/script.js', array('jquery'), '1.0.0', true);
    wp_localize_script('websima_newsletter-script', 'newsletter_script_dyn_data',
        array(
            'admin_ajax'  =>  admin_url('admin-ajax.php'),
        )
    );
}

/**
 * Newsletter form.
 */
function websima_newsletter_form(){ ?>
    <form method="post" id="newsletter-form" novalidate="novalidate" class="footer-newsletter">

            <input type="email" name="email" id="email" class="form-control ltr" placeholder="جهت عضویت در خبرنامه ایمیل خود را وارد کنید">
            <?php wp_nonce_field('websima_newsletter_nonce','websima_newsletter_nonce_field'); ?>
            <input type="hidden" name="action" value="websima_newsletter_submission"/>
            <button type="submit" >ارسال کنید</button>


    </form>
<?php }


/**
 * Newsletter submission.
 */
add_action('wp_ajax_websima_newsletter_submission','websima_newsletter_submission');
add_action('wp_ajax_nopriv_websima_newsletter_submission','websima_newsletter_submission');
function websima_newsletter_submission(){
    if($_POST['email']){
        if(is_email($_POST['email'])){
            $allowed_html   =   array();
            $email = wp_kses(websima_xss_clean($_POST['email']) ,$allowed_html);

            $websima_newsletter_nonce_field = websima_xss_clean($_POST['websima_newsletter_nonce_field']);
            if(!wp_verify_nonce($websima_newsletter_nonce_field,'websima_newsletter_nonce')){
                $status = 0;
                $error = 'خطا امنیتی رخ داده است';
            }else{
                $newsletter_company = get_field('newsletter_company','option');
                if($newsletter_company == 'mailerlite'){
                    $group_id = get_field('newsletter_group_id','option');
                    $response = websima_newsletter_mailerlite($email,$group_id);
                }else{
                    $list_id = get_field('newsletter_group_id','option');
                    $response = websima_newsletter_mailchimp($email,$list_id);
                }

                $status = esc_attr($response['flag']);
                $error = esc_html($response['message']);
            }
        }else{
            $status = 0;
            $error = 'ایمیل وارد شده معتبر نمی باشد';
        }
    }else{
        $status = 0;
        $error = 'لطفا ایمیل خود را وارد نمایید';
    }

    $resp = array('status' => $status, 'msg' => $error);
    header( "Content-Type: application/json" );
    echo json_encode($resp);
    die();
}

/**
 * User register.
 */
$auth_email_active = get_field('auth_email_active', 'option');
$newsletter_user_register_capability = get_field('newsletter_user_register_capability','option');
if($auth_email_active and $newsletter_user_register_capability){
    add_action('websima_auth_account_profile_completed','websima_newsletter_user_register');
}
function websima_newsletter_user_register(){
    $args = array();
    $user_id = get_current_user_id();

    $newsletter_company = get_field('newsletter_company','option');
    $group_id = get_field('newsletter_user_register_group_id','option');
    $auth_fullname_active = get_field('auth_fullname_active', 'option');

    $mobile = get_user_meta(esc_attr($user_id),'mobile',true);
    $email = get_user_meta(esc_attr($user_id),'billing_email',true);

    if($newsletter_company == 'mailerlite'){
        if($auth_fullname_active){
            $args['name'] = get_user_meta(esc_attr($user_id),'first_name',true);
            $args['last_name'] = get_user_meta(esc_attr($user_id),'last_name',true);
        }
        $args['phone'] = esc_html($mobile);
        $response = websima_newsletter_mailerlite($email,$group_id,$args);
    }else{
        if($auth_fullname_active){
            $args['FNAME'] = get_user_meta(esc_attr($user_id),'first_name',true);
            $args['LNAME'] = get_user_meta(esc_attr($user_id),'last_name',true);
        }
        $args['PHONE'] = esc_html($mobile);
        $response = websima_newsletter_mailchimp($email,$group_id,$args);
    }
}

/**
 * Shop Order.
 */
$newsletter_shop_order_capability = get_field('newsletter_shop_order_capability','option');
if($newsletter_shop_order_capability){
    add_action('woocommerce_order_status_completed','websima_newsletter_order_operation');
    add_action('woocommerce_order_status_refunded','websima_newsletter_order_operation');
    add_action('woocommerce_order_status_failed','websima_newsletter_order_operation');
    add_action('woocommerce_order_status_cancelled','websima_newsletter_order_operation');
}
function websima_newsletter_order_operation($order_id){
    $args = array();
    $total = 0;
    $count = 0;

    $order = new WC_Order(esc_attr($order_id));
    $user_id = $order->get_user_id();
    $email = get_user_meta(esc_attr($user_id),'billing_email',true);

    if($email){
        $customer_orders = get_posts( array(
            'numberposts' => - 1,
            'meta_key'    => '_customer_user',
            'meta_value'  => esc_attr($user_id),
            'post_type'   => array('shop_order'),
            'post_status' => array('wc-completed')
        ) );

        if(!empty($customer_orders)){
            foreach($customer_orders as $customer_order){
                $order = wc_get_order(esc_attr($customer_order->ID));
                $total += $order->get_total();
                $count++;
            }
        }

        if(substr($email,0,2) != '09'){
            $newsletter_company = get_field('newsletter_company','option');
            $group_id = get_field('newsletter_shop_order_group_id','option');
            if($newsletter_company == 'mailerlite'){
                $args['order_count'] = esc_html($count);
                $args['order_total'] = esc_html($total);
                $response = websima_newsletter_mailerlite($email, $group_id, $args);
            }else{
                $args['OCOUNT'] = esc_html($count);
                $args['OTOTAL'] = esc_html($total);
                $response = websima_newsletter_mailchimp($email, $group_id, $args);
            }
        }
    }
}

/**
 * Mailerlite.
 */
function websima_newsletter_mailerlite($email,$group_id,$args=null){
    $result = array();
    $api_key = get_field('newsletter_api_key','option');
    $data = array('email' => sanitize_email($email));
    if(!empty($args)){ $data['fields'] = $args; }
    $url = 'https://api.mailerlite.com/api/v2/groups/'.esc_attr($group_id).'/subscribers';
    $response = wp_remote_post(esc_url($url), array(
            'headers' => array(
                'content-type' => 'application/json',
                'x-mailerlite-apikey' => esc_attr($api_key)
            ),
            'body' => wp_json_encode($data),
        )
    );

    if(!is_wp_error($response)){
        $response_code = wp_remote_retrieve_response_code($response);
        if($response_code == 200){
            $response_body = wp_remote_retrieve_body($response);
            $response_object = json_decode($response_body);
            $result['flag'] = 1;
            $result['message'] = 'عملیات با موفقیت انجام شد';
        }elseif($response_code == 401){
            $result['flag'] = 0;
            $result['message'] = 'API Key وارد شده معتبر نمی باشد';
        }elseif($response_code == 404){
            $result['flag'] = 0;
            $result['message'] = 'گروه وارد شده معتبر نمی باشد';
        }else{
            $result['flag'] = 0;
            $result['message'] = 'خطا رخ داده است';
        }
    }else{
        $result['flag'] = 0;
        $result['message'] = 'خطا رخ داده است';
    }

    return $result;
}

/**
 * Mailchimp.
 */
function websima_newsletter_mailchimp($email,$list_id,$args=null){
    $result = array();
    $api_key = get_field('newsletter_api_key','option');
    $resource = sprintf('/lists/%s/members/%s', esc_attr($list_id), md5(strtolower(trim(sanitize_email($email)))));
    $api_url = 'https://api.mailchimp.com/3.0/';
    $data = array('status' => 'subscribed', 'email_address' => sanitize_email($email));
    if(!empty($args)){ $data['merge_fields'] = $args; }
    if($api_key){
        $dash_position = strpos( $api_key, '-' );
        if($dash_position !== false){
            $api_url = str_replace( '//api.', '//' . substr($api_key,$dash_position + 1 ) . '.api.', $api_url );
            $url    = $api_url . ltrim( $resource, '/' );
            $response = wp_remote_request(esc_url($url), array(
                    'method' => 'PUT',
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        //'Authorization' => sprintf( 'Basic %s', base64_encode('mc4wp:'.$api_key)),
                        'Authorization' => 'Bearer '.esc_attr($api_key),
                    ),
                    'body' => wp_json_encode($data),
                )
            );
            
            if(!is_wp_error($response)){
                $response_code = wp_remote_retrieve_response_code($response);
                if($response_code == 200) {
                    $response_body = wp_remote_retrieve_body($response);
                    $response_object = json_decode($response_body);
                    //echo $response_object->id;
                    $result['flag'] = 1;
                    $result['message'] = 'عملیات با موفقیت انجام شد';
                }elseif($response_code == 400){
                    $result['flag'] = 0;
                    $result['message'] = 'ایمیل داده شده قبلاً مشترک شده است';
                }elseif($response_code == 401){
                    $result['flag'] = 0;
                    $result['message'] = 'API Key وارد شده معتبر نمی باشد';
                }elseif($response_code == 404){
                    $result['flag'] = 0;
                    $result['message'] = 'لیست وارد شده معتبر نمی باشد';
                }else{
                    $result['flag'] = 0;
                    $result['message'] = 'خطا رخ داده است';
                }
            }else{
                $result['flag'] = 0;
                $result['message'] = 'خطا رخ داده است';
            }
        }else{
            $result['flag'] = 0;
            $result['message'] = 'API Key وارد شده معتبر نمی باشد';
        }
    }else{
        $result['flag'] = 0;
        $result['message'] = 'API Key وارد نشده است';
    }

    return $result;
}