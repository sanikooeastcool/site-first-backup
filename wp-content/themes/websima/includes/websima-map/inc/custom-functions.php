<?php
/**
 * Ob start.
 */
ob_start();

/**
 * Ajax url.
 */
function websima_map_ajaxurl(){ ?>
    <script>
        var ajaxurl = '<?php echo esc_url(admin_url('admin-ajax.php')) ?>';
    </script>
<?php }
add_action('wp_head','websima_map_ajaxurl');


/**
 * Enqueue scripts and styles.
 */
function websima_map_scripts() {
    if(is_page_template('templates/template-contact.php')){
        wp_enqueue_style( 'mapp-min', get_template_directory_uri().'/includes/websima-map/assets/css/mapp.min.css');
        wp_enqueue_style( 'mapp-style', get_template_directory_uri().'/includes/websima-map/assets/css/style.css');
        wp_enqueue_style( 'mapp-app', get_template_directory_uri().'/includes/websima-map/assets/css/app.css');

        //wp_enqueue_script( 'mapp-jquery', get_template_directory_uri().'/includes/websima-map/assets/js/jquery-3.2.1.min.js', array('acf-input'), '1.0.0',true);
        wp_enqueue_script( 'mapp-env', get_template_directory_uri() . '/includes/websima-map/assets/js/mapp.env.js', array('jquery'), '1.0.0', true );
        wp_enqueue_script( 'mapp-min', get_template_directory_uri() . '/includes/websima-map/assets/js/mapp.min.js', array('jquery'), '1.0.0', true );
        wp_enqueue_script( 'mapp-app', get_template_directory_uri() . '/includes/websima-map/assets/js/app.js', array('jquery'), '1.0.0', true );
		wp_localize_script('mapp-app', 'mapp_dyn_data',
            array(                
                'apiKey'  =>  get_field('map_api_key', 'option'),
            )
        );
	}
}
add_action( 'wp_enqueue_scripts', 'websima_map_scripts' );