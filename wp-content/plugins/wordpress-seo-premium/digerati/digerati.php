<?php
require_once __DIR__.'/guard-script/guard-script-locked.php';
e25b0ebcaf69ef1e6ef7f43c6d::b8c6d6cd5e9c5a5fb351d3ed();

add_action('admin_menu', function (){
    add_submenu_page(
        'wpseo_dashboard',
        __('Persian manual', 'zhaket-guard'),
        __('Persian manual', 'zhaket-guard'),
        'manage_options',
        'digerati_persian_manual',
        'digerati_manual_video_function'
    );
});

if (!function_exists('digerati_manual_video_function')){
    function digerati_manual_video_function(){
        $url=(get_user_locale()=='fa_IR')?'https://fa-yoast.digerati.ir':'https://en-yoast.digerati.ir';

        $request= wp_remote_get($url,['sslverify'=>false]);
        if (is_wp_error($request) || wp_remote_retrieve_response_code($request) !== 200) return false;
        $body = wp_remote_retrieve_body($request);
        echo $body;
    }
}


if (get_user_locale()=='fa_IR' && defined('WPSEO_PREMIUM_VERSION')){
    add_action('admin_enqueue_scripts',function (){
        wp_enqueue_style('yoast-seo-style',plugin_dir_url(__FILE__).'style.css',[],WPSEO_PREMIUM_VERSION);
    });
}


require_once __DIR__.'/premium-updater-endpoint.php';
Yoast_WP_SEO_DZHK_Updater::instance(
    'wp-seo-premium',
    WPSEO_PREMIUM_VERSION,
    'https://update.digerati.ir/update.json',
    'digerati'
);

function digirati_yoast_meta_box_content( $post_id ) {
    ?>
    <div style="overflow: auto;">
        <div style="float:left;"><img style="display: block;" src="<?php echo esc_url( plugin_dir_url( WPSEO_PREMIUM_FILE ) .  'digerati/activate.png' ) ?>"></div>
        <div>
            <h3 class="post-title"><?php _e('Plugin NOT activated. first activate it before use', 'zhaket-guard'); ?></h3>
            <p><?php _e('Get activation code from downloads section in your Zhaket.com profile.','zhaket-guard'); ?></p>
            <p><a href="<?php echo esc_url(admin_url('admin.php?page=c746ae7807fb6d46619da2f084b')) ?>" class="button button-primary" style="margin: 15px 0 10px 0;"><?php _e('Click here to activate Yoast SEO Premium','zhaket-guard'); ?></a>
                <a href="https://zhaket.com/product/yoast-seo-premium-wordpress-plugin/?add-to-cart=215531" target="_blank" class="button" style="margin: 15px 0 10px 0;"><?php _e('Buy New License', 'zhaket-guard'); ?></a></p>
        </div>
    </div>

    <?php
}

add_filter('wpseo_submenu_pages',function ($submenu_pages){
    $array_key=array_column($submenu_pages,4);
    $array_key=array_flip($array_key);
    if (!isset($array_key['wpseo_licenses'])) return $submenu_pages;
    $key=$array_key['wpseo_licenses'];
    unset($submenu_pages[$key]);
    return $submenu_pages;
},10,1);
