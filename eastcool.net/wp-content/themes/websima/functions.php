<?php
 require_once "includes/template-functions.php";
require_once "includes/template-config.php";
 require_once "includes/admin-rules.php";
require_once "includes/search-ajax.php";
require_once "includes/validation.php";
require_once "includes/xss_clean.php";
require_once "includes/websima-map/websima-map.php";
require_once "includes/websima-map-location/websima-map-location.php";
require_once "includes/acf-image-select/acf-image-select.php";
require_once "woocommerce/woocommerce-functions.php";
require_once "includes/websima-delivery/delivery-init.php";
require_once "includes/websima-sms/sms-init.php";
require_once "includes/websima-auth/websima-auth-init.php";
require_once "includes/websima-compare/compare-init.php";
require_once "includes/websima-wishlist/websima-wishlist.php";
require_once "includes/websima-menu/menu-init.php";
require_once "includes/websima-newsletter/websima-newsletter-init.php";
require_once "includes/websima-request/request-init.php";
require_once "includes/websima-wallet/wallet-init.php";
require_once "includes/websima-multi-step-cart/websima-multi-step-cart-init.php";
require_once "includes/websima-step-discount/websima-step-discount.php";
require_once "includes/user-tools.php";
require_once "includes/websima-captcha/captcha.php";

add_filter( 'wpseo_breadcrumb_links', 'wbs_breadcrumb_detail' );

function wbs_breadcrumb_detail( $links ) {

    if(is_singular( 'post' )){
        $breadcrumb[] = array(
            'url' => get_permalink( websima_find_page_id('templates/template-blog.php') ),
            'text' => 'آرشیو مقالات',
        );
        array_splice( $links, 1, -2, $breadcrumb );
    }
    
    return $links;
}


if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title' => 'Websima package',
        'menu_title' => 'Websima package',
        'menu_slug' => 'websima-package-general-settings',
        'capability' => 'edit_posts'
    ));
}

@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );

function remove_devadmin_js_code() {
?>
    <script type="text/javascript">
		jQuery(document).ready(function(){
		jQuery("#user-8").remove();
		});
    </script>
<?php
}
add_action( 'admin_footer', 'remove_devadmin_js_code' ); // For back-end


