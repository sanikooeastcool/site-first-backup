<?php
/**
 * Post type.
 */
add_action('init', 'websima_request_cpt_init');
function websima_request_cpt_init(){
    $request_labels = array(
        'name' => esc_html('درخواست مشاوره'),
        'singular_name' => esc_html('درخواست مشاوره'),
        'all_items' => esc_html('همه درخواست ها'),
        'add_new' => esc_html('افزودن درخواست جدید'),
        'add_new_item' => esc_html('افزودن درخواست جدید'),
        'edit_item' => esc_html('ویرایش درخواست'),
        'new_item' => esc_html('درخواست جدید'),
        'view_item' => esc_html('مشاهده درخواست'),
        'search_items' => esc_html('جستجو در درخواست های مشاوره'),
        'not_found' =>  esc_html('درخواست مشاوره ای یافت نشد'),
        'not_found_in_trash' => esc_html('درخواست مشاوره ای در زباله دان وجود ندارد'),
        'parent_item_colon' => ''
    );
    $request_args = array(
        'labels' => $request_labels,
        'public' => true,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 100,
        'menu_icon' => 'dashicons-media-text',
        'supports' => array('title'),
        'has_archive' => false,
        'map_meta_cap'        => true,
        'capabilities' => array(
            'create_posts' => false
        )
    );
    register_post_type('request',$request_args);


    $request_log_labels = array(
        'name' => esc_html('لاگ درخواست مشاوره'),
        'singular_name' => esc_html('لاگ درخواست مشاوره'),
        'all_items' => esc_html('همه لاگ درخواست ها'),
        'add_new' => esc_html('افزودن لاگ درخواست جدید'),
        'add_new_item' => esc_html('افزودن لاگ درخواست جدید'),
        'edit_item' => esc_html('ویرایش لاگ درخواست'),
        'new_item' => esc_html('لاگ درخواست جدید'),
        'view_item' => esc_html('مشاهده لاگ درخواست'),
        'search_items' => esc_html('جستجو در لاگ درخواست های مشاوره'),
        'not_found' =>  esc_html('لاگ درخواست مشاوره ای یافت نشد'),
        'not_found_in_trash' => esc_html('لاگ درخواست مشاوره ای در زباله دان وجود ندارد'),
        'parent_item_colon' => ''
    );
    $request_log_args = array(
        'labels' => $request_log_labels,
        'public' => true,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => false,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 100,
        'menu_icon' => 'dashicons-database-add',
        'supports' => array('title'),
        'has_archive' => false,
        'map_meta_cap'        => true,
        'capabilities' => array(
            'create_posts' => false
        )
    );
    register_post_type('request_log',$request_log_args);
}

/**
 * Settings.
 */
add_action('init','websima_request_init');
function websima_request_init(){
    if( function_exists('acf_add_options_page') ) {
        acf_add_options_sub_page(array(
            'page_title' 	=> 'درخواست مشاوره',
            'menu_title'	=> 'درخواست مشاوره',
            'menu_slug'	    => 'websima-package-request-settings',
            'parent_slug'	=> 'websima-package-general-settings',
            'capability'	=> 'edit_posts'
        ));
    }
}


/**
 * Request form.
 */
function websima_request_form(){ ?>
    <form method="post" id="requestform" novalidate="novalidate">
        <?php $full_name_switch = get_field('request_full_name_switch', 'option'); ?>
        <?php if($full_name_switch == 1){ ?>
        <div class="form-row">
            <div class="form-group col-12 col-md-12">
                <label class="text-muted">نام و نام خانوادگی</label>
                <input type="text" name="full_name" id="full_name" class="form-control" placeholder="نام و نام خانوادگی خود را وارد کنید">
            </div>
        </div>
        <?php } ?>
        <div class="form-row">
            <div class="form-group col-12 col-md-12">
                <label class="text-muted">شماره موبایل</label>
                <input type="text" name="mobile" id="mobile" class="form-control ltr" placeholder="شماره موبایل خود را وارد کنید">
            </div>
        </div>

        <?php wp_nonce_field( 'websima_request_nonce', 'websima_request_nonce_field' ); ?>
        <?php $page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
        <input type="hidden" name="action" value="websima_request_submit"/>
        <input type="hidden" name="page_url" value="<?php echo esc_url($page_url); ?>"/>
        <button type="submit" class="button">ثبت درخواست</button>
    </form>
<?php }

/**
 * Enqueue scripts.
 */
add_action('wp_enqueue_scripts', 'websima_request_scripts');
function websima_request_scripts(){
    $request_rules = array();
    $request_messages = array();

    $request_rules['mobile']['required'] = true;
    $request_rules['mobile']['custommobile'] = true;
    $request_messages['mobile']['required'] = 'لطفا شماره موبایل خود را وارد نمایید';
    $request_messages['mobile']['custommobile'] = 'شماره موبایل وارد شده معتبر نمی باشد';

    if (get_field('request_full_name_switch', 'option') == 1) {
        $request_rules['full_name']['required'] = true;
        $request_messages['full_name']['required'] = 'لطفا نام و نام خانوادگی خود را وارد نمایید';
    }

    wp_localize_script('customjs', 'request_dyn_data',
        array(
            'admin_ajax' => admin_url('admin-ajax.php'),
            'request_rules' => $request_rules,
            'request_messages' => $request_messages,
        )
    );
}

/**
 * Mobile validation.
 */
function websima_request_mobile_validation($mobile){
    if(strlen($mobile) == '11'){
        if(is_numeric($mobile)){
            if(preg_match( '/^09[0-9]{9}$/', $mobile )){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }else{
        return false;
    }
}

/**
 * Request submit.
 */
add_action('wp_ajax_websima_request_submit', 'websima_request_submit');
add_action('wp_ajax_nopriv_websima_request_submit', 'websima_request_submit');
function websima_request_submit(){
    $full_name_switch = get_field('request_full_name_switch', 'option');

    $allowed_html   =   array();
    $mobile = wp_kses(websima_xss_clean($_POST['mobile']) ,$allowed_html);
    $page_url = wp_kses(websima_xss_clean($_POST['page_url']) ,$allowed_html);
    $full_name = '';
    if($full_name_switch == 1){
        if($_POST['full_name']){
            $full_name = wp_kses(websima_xss_clean($_POST['full_name']) ,$allowed_html);
            $full_name_validation = true;
        }else{
            $full_name_validation = false;
        }
    }else{
        $full_name_validation = true;
    }

    if($mobile){
        if(websima_request_mobile_validation($mobile)){
            if($full_name_validation){
                if(wp_verify_nonce( websima_xss_clean($_POST['websima_request_nonce_field']), 'websima_request_nonce' )){
                    $websima_request_counter = get_option('websima_request_counter');
                    if(!$websima_request_counter){ $websima_request_counter = 0; }
                    $websima_request_counter++;
                    $new_request = array(
                        'post_title' => 'درخواست جدید',
                        'post_status' => 'draft',
                        'post_type' => 'request'
                    );
                    $request_id = wp_insert_post($new_request);

                    update_post_meta(esc_attr($request_id),'mobile',esc_attr($mobile));
                    update_post_meta(esc_attr($request_id),'full_name',esc_attr($full_name));
                    update_post_meta(esc_attr($request_id),'status','pending');
                    update_post_meta(esc_attr($request_id),'page_url',esc_url($page_url));
                    update_post_meta(esc_attr($request_id),'log_count','0');
                    update_post_meta(esc_attr($request_id),'tracking_code',esc_attr($websima_request_counter));

                    update_option('websima_request_counter',esc_attr($websima_request_counter));
					$email_admin = get_field('email_admin','option');
					if($email_admin){
                    $headers[] = 'From: شرکت ایستکول <info@eastcool.com>';
					$mobile = get_post_meta(esc_attr($request_id),'mobile',true);
					if (get_field('request_full_name_switch', 'option') == 1) {
                       $full_name = get_post_meta(esc_attr($request_id),'full_name',true);
					}
					$subject ='درخواست مشاوره';
					$message2 = 'درخواست جدید با اطلاعات'. "\r\n";
					if (get_field('request_full_name_switch', 'option') == 1) {
					    $message2 .= $full_name . "\r\n";
					}
					$message2 .= $mobile . "\r\n";
					$message2 .= 'در سایت ثبت شد.';
					wp_mail($email_admin , $subject, $message2,$headers);
					}
                    $status = 1;
                    $error =  'درخواست شما با موفقیت ثبت شد.';
                }else{
                    $status = 0;
                    $error =  'خطای امنیتی رخ داده است، لطفا بعدا تلاش نمایید.';
                }
            }else{
                $status = 0;
                $error =  'لطفا نام و نام خانوادگی خود را وارد نمایید';
            }
        }else{
            $status = 0;
            $error =  'شماره موبایل وارد شده معتبر نمی باشد.';
        }
    }else{
        $status = 0;
        $error =  'لطفا شماره موبایل خود را وارد نمایید.';
    }


    $resp = array('status' => $status, 'msg' => $error);
    header( "Content-Type: application/json" );
    echo json_encode($resp);
    die();
}

/**
 * Request status translate.
 */
function websima_request_status_translate($status){
    $status_txt = '';
    if($status == 'pending'){
        $status_txt = 'در انتظار بررسی';
    }elseif($status == 'need_to_follow'){
        $status_txt = 'نیاز به پیگیری';
    }elseif($status == 'customer_non_response'){
        $status_txt = 'عدم پاسخگویی مشتری';
    }else{
        $status_txt = 'بررسی شده';
    }

    return esc_html($status_txt);
}

/**
 * Request columns.
 */
add_filter( 'manage_request_posts_columns', 'websima_request_manage_posts_columns' );
function websima_request_manage_posts_columns($columns) {
    unset($columns['date']);
    $columns['title'] = esc_html('شماره درخواست');
    if(get_field('request_full_name_switch', 'option') == 1){
        $columns['full_name'] = esc_html('نام و نام خانوادگی');
    }
    $columns['mobile'] = esc_html('شماره موبایل');
    $columns['status'] = esc_html('وضعیت');
    $columns['log_count'] = esc_html('رونوشت');
    $columns['log_submit'] = esc_html('ثبت رونوشت');
    $columns['date'] = esc_html('تاریخ');
    return $columns;
}

add_action( 'manage_request_posts_custom_column' , 'websima_request_manage_posts_custom_column', 10, 2 );
function websima_request_manage_posts_custom_column( $column, $post_id ) {
    $full_name = get_post_meta(esc_attr($post_id),'full_name',true);
    $mobile = get_post_meta(esc_attr($post_id),'mobile',true);
    $status = get_post_meta(esc_attr($post_id),'status',true);
    $log_count = get_post_meta(esc_attr($post_id),'log_count',true);
    if(!$log_count){ $log_count = 0; }

    switch($column){
        case 'full_name':
            if($full_name){ echo esc_html($full_name); }else{ echo '-'; }
            break;

        case 'mobile':
            echo esc_html($mobile);
            break;

        case 'status':
            echo websima_request_status_translate(esc_attr($status));
            break;

        case 'log_count':
            echo esc_html($log_count);
            break;

        case 'log_submit':
            echo '<a href="'.admin_url().'admin.php?page=websima-request-log-submission&request_id='.esc_attr($post_id).'">';
                echo 'افزودن';
            echo '</a>';
            break;
    }
}

add_action('admin_head-edit.php', 'websima_request_admin_head_edit');
function websima_request_admin_head_edit() {
    $screen = get_current_screen();
    if($screen->id == "edit-request"){
        add_filter('the_title', 'websima_request_change_admin_column_post_title', 100, 2);
    }
}

function websima_request_change_admin_column_post_title($title,$i ){
    $tracking_code = get_post_meta(esc_attr($i),'tracking_code',true);
    return sprintf("%06d", esc_attr($tracking_code));
}

/**
 * Request export.
 */
add_action('admin_footer', 'websima_request_export_button');
function websima_request_export_button(){
    $screen = get_current_screen();
    if($screen->id == "edit-request"){
        ?>
        <script type="text/javascript">
            jQuery(document).ready( function($){
                jQuery('.tablenav.top .clear, .tablenav.bottom .clear').before('<form action="#" method="POST" id="request-export-form"> <input type="hidden" name="request-export-csv" id="request-export-csv" value="1"/> <input type="submit" class="button button-primary" value="خروجی csv"/> </form>');
            });
        </script>
        <?php
    }
}

add_action('admin_init', 'websima_request_export_action');
function websima_request_export_action(){
    if($_POST['request-export-csv'] == 1){
        if(current_user_can('manage_options')){
            $full_name_switch = get_field('request_full_name_switch', 'option');

            $csv_fields = array();
            $csv_fields[] = 'شماره درخواست';
            if($full_name_switch == 1){
            $csv_fields[] = 'نام و نام خانوادگی';
            }
            $csv_fields[] = 'شماره موبایل';
            $csv_fields[] = 'وضعیت';
            $csv_fields[] = 'لینک';

            $output_filename = 'request_export_'.date('YmdHis').'.csv';
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
            //echo "\xEF\xBB\xBF"; // UTF-8 BOM
            // Insert header row
            fputcsv( $output_handle, $csv_fields );


            $args = array();
            $args['post_type'] = 'request';
            $args['posts_per_page'] = -1;
            $requests = new WP_Query( $args );
            if($requests->have_posts()): while($requests->have_posts()): $requests->the_post();
                $leadArray = array();

                $full_name = get_post_meta(get_the_ID(),'full_name',true);
                $mobile = get_post_meta(get_the_ID(),'mobile',true);
                $status = get_post_meta(get_the_ID(),'status',true);
                $page_url = get_post_meta(get_the_ID(),'page_url',true);
                if(!$full_name){ $full_name = '-'; }

                $leadArray[] = get_the_title();
                if($full_name_switch == 1){
                $leadArray[] = esc_html($full_name);
                }
                $leadArray[] = esc_html($mobile);
                $leadArray[] = websima_request_status_translate(esc_attr($status));
                $leadArray[] = esc_url($page_url);

                fputcsv($output_handle, $leadArray);
            endwhile; endif; wp_reset_postdata();

            fclose( $output_handle );
            die();
        }
    }
}

/**
 * Meta box for request.
 */
add_action('add_meta_boxes','websima_request_exclusive_meta_box');
function websima_request_exclusive_meta_box(){
    $screens = array('request');
    foreach($screens as $screen){
        add_meta_box(
            'request-exclusive-meta-box',
            'اطلاعات ثبت شده',
            'websima_request_exclusive_meta_box_callback',
            $screen,
            'normal'
        );
    }
}

function websima_request_exclusive_meta_box_callback($post){
    $full_name_switch = get_field('request_full_name_switch', 'option');
    $full_name = get_post_meta(esc_attr($post->ID),'full_name',true);
    $mobile = get_post_meta(esc_attr($post->ID),'mobile',true);
    $status = get_post_meta(esc_attr($post->ID),'status',true);
    $page_url = get_post_meta(esc_attr($post->ID),'page_url',true);
    $log = get_post_meta(esc_attr($post->ID),'log',true);
    if(!$full_name){ $full_name = '-'; }
    ?>
    <style>
        #request-exclusive-meta-box .inside{ padding: 15px; }
        .request-table { width: 100%; text-align: right; }
        .request-table td.ltr{ direction: ltr; }
        .request-table th , .request-table td{ width: 20%; padding: 10px 10px; }
        .request-info-table th:last-child , .request-info-table td:last-child{ width: 40%; }
        .request-log-table th:last-child , .request-log-table td:last-child{ width: 60%; }
        .request-table-title{ padding: 15px; margin: 0 0 5px 0; background: #f1f1f1; }
        .request-log-wrapper{ margin: 50px 0 0 0; }
        #request-exclusive-meta-box .log-button{ margin: 30px 0 0 0 !important; }
    </style>

    <div class="request-info-wrapper">
        <h4 class="request-table-title">اطلاعات درخواست دهنده</h4>
        <table class="table request-table request-info-table">
            <thead>
                <tr>
                    <?php if($full_name_switch == 1){ ?>
                    <th>نام و نام خانوادگی</th>
                    <?php } ?>
                    <th>شماره موبایل</th>
                    <th>وضعیت</th>
                    <th>لینک</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <?php if($full_name_switch == 1){ ?>
                    <td><?php echo esc_html($full_name); ?></td>
                    <?php } ?>
                    <td><?php echo esc_html($mobile); ?></td>
                    <td><?php echo websima_request_status_translate(esc_attr($status)); ?></td>
                    <?php if($page_url){ ?>
                        <td><a href="<?php echo esc_url($page_url); ?>" target="_blank"><?php echo esc_html($page_url); ?></a></td>
                    <?php } else{ ?>
                        <td>-</td>
                    <?php } ?>
                </tr>
            </tbody>
        </table>
    </div>

    <?php if(!empty($log)){ ?>
        <div class="request-log-wrapper">
            <h4 class="request-table-title">رونوشت های ثبت شده</h4>
            <table class="table request-table request-log-table">
                <thead>
                    <tr>
                        <th>کاربر</th>
                        <th>تاریخ</th>
                        <th>دیدگاه</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach($log as $log_id){
                    $description = get_post_meta(esc_attr($log_id),'description',true);
                    $user_id = get_post_meta(esc_attr($log_id),'user',true);
                    $userdata = get_userdata(esc_attr($user_id));
                    ?>
                    <tr>
                        <td><?php echo esc_html($userdata->data->display_name); ?></td>
                        <td class="ltr"><?php echo get_the_date('Y-m-d H:i',esc_attr($log_id)); ?></td>
                        <td><?php echo esc_html($description); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php }

    echo '<a href="'.admin_url().'admin.php?page=websima-request-log-submission&request_id='.esc_attr($post->ID).'" class="button button-primary button-large log-button">';
        echo 'ثبت رونوشت';
    echo '</a>';
}

/**
 * Request status filter.
 */
add_action('restrict_manage_posts','websima_request_filter_by_status');
function websima_request_filter_by_status(){
    global $typenow;
    global $wp_query;
    if($typenow == 'request'){
        $current_status = '';
        if( isset( $_GET['status'] ) ) { $current_status = $_GET['status']; }
        ?>
        <select name="status" id="status">
            <option value="all">همه</option>
            <option value="pending" <?php if($current_status == 'pending'){ echo 'selected'; } ?>>در انتظار بررسی</option>
            <option value="need_to_follow" <?php if($current_status == 'need_to_follow'){ echo 'selected'; } ?>>نیاز به پیگیری</option>
            <option value="customer_non_response" <?php if($current_status == 'customer_non_response'){ echo 'selected'; } ?>>عدم پاسخگویی مشتری</option>
            <option value="checked" <?php if($current_status == 'checked'){ echo 'selected'; } ?>>بررسی شده</option>
        </select>
        <?php
    }
}

add_filter('parse_query', 'websima_request_filter_by_status_query');
function websima_request_filter_by_status_query($query){
    global $pagenow;
    $post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
    if(is_admin() && $pagenow=='edit.php' && $post_type == 'request' && isset($_GET['status']) && $_GET['status'] != 'all'){
        $query->query_vars['meta_key'] = 'status';
        $query->query_vars['meta_value'] = $_GET['status'];
        $query->query_vars['meta_compare'] = '=';
    }
}


/* Request log*/
add_action('admin_menu', 'websima_request_admin_menu');
function websima_request_admin_menu(){
    add_menu_page(
        'ثبت رونوشت درخواست مشاوره',
        'ثبت رونوشت درخواست مشاوره',
        'manage_options',
        'websima-request-log-submission',
        'websima_request_log_submission_callback'
    );

    remove_menu_page('websima-request-log-submission');
}

add_action('admin_enqueue_scripts','websima_request_admin_enqueue_scripts');
function websima_request_admin_enqueue_scripts() {
    global $pagenow;
    if($pagenow == 'admin.php' and (isset($_GET['page']) && $_GET['page'] == 'websima-request-log-submission')){
        wp_enqueue_script('acf-input');
    }
}

function websima_request_log_submission_callback() {
    if(!current_user_can('manage_options' )){
        return;
    }

    $request_id = $_GET['request_id'];
    if($request_id){
        if(get_post_type(esc_attr($request_id)) == 'request'){
            echo '<div class="websima-request-ls-wrapper">';
                echo '<div class="websima-request-ls-wrap">';
                    $referer =  $_SERVER['HTTP_REFERER'];
                    $referer = str_replace(admin_url(),'',$referer);
                    $referer_array = explode("?",$referer);
                    $referer_type = $referer_array[0];
                    if($referer_type == 'post.php'){
                        $return_url = admin_url().'post.php?post='.esc_attr($request_id).'&action=edit';
                    }else{
                        $return_url = admin_url().'edit.php?post_type=request';
                    }

                    echo '<h2>ثبت رونوشت درخواست مشاوره</h2>';
                    acf_form_head();
                    acf_form(array(
                        'post_id'       => 'new_post',
                        'new_post'      => array(
                            'post_type'     => 'request_log',
                            'post_title'     => 'لاگ درخواست '.get_the_title($request_id),
                            'post_status'   => 'publish',
                            'meta_input'   => array(
                                'user' => get_current_user_id(),
                                'request' => esc_attr($request_id),
                            ),
                        ),
                        'form_attributes' => array(
                            'id' => 'request-log',
                            'class' => 'acf-form',
                            'action' => '',
                            'method' => 'post',
                        ),
                        'fields' => array('description'),
                        'post_title'    => false,
                        'post_content'  => false,
                        'return'  => $return_url,
                        'submit_value'  => 'ثبت'
                    ));

                echo '</div>';
            echo '</div>';
        }else{
            echo '<p>مشکلی پیش آمده است. لطفا بعدا تلاش نمایید.</p>';
        }
    }else{
        echo '<p>مشکلی پیش آمده است. لطفا بعدا تلاش نمایید.</p>';
    }
}

add_action('acf/save_post','websima_request_log_save_post');
function websima_request_log_save_post($post_id){
    if(get_post_type(esc_attr($post_id)) == 'request_log'){
        $request_log_id = $post_id;
        $request_id = get_post_meta(esc_attr($request_log_id),'request',true);

        $log_count = get_post_meta(esc_attr($request_id),'log_count',true);
        $log = get_post_meta(esc_attr($request_id),'log',true);

        if(!$log_count){ $log_count = 0; }
        if(empty($log)){ $log = array(); }

        $log_count++;
        $log[] = esc_attr($request_log_id);
        update_post_meta(esc_attr($request_id),'log_count',esc_attr($log_count));
        update_post_meta(esc_attr($request_id),'log',$log);
    }
}