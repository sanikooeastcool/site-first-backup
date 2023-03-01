<?php
add_action('init','websima_wallet_init');
function websima_wallet_init(){
    if( function_exists('acf_add_options_page') ) {
        acf_add_options_sub_page(array(
            'page_title' 	=> 'کیف پول اعتباری',
            'menu_title'	=> 'کیف پول اعتباری',
            'menu_slug'	    => 'websima-package-wallet-settings',
            'parent_slug'	=> 'websima-package-general-settings',
            'capability'	=> 'edit_posts'
        ));
    }
}


$wallet_status = get_field('wallet_status', 'option');
if($wallet_status == 1){
    require_once "wallet-functions.php";
}
?>