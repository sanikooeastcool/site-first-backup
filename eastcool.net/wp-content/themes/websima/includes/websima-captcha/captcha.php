<?php

function wpdocs_enqueue_custom_admin_style() {
	wp_enqueue_style( 'c4wp-admin', get_template_directory_uri().'/includes/websima-captcha/assets/css/c4wp-admin.css' );
}
add_action( 'admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_style' );
	
class WP_CAPTCHA {
	
	public static $_c4wp_instance;
	public $c4wp_plugin_options;
	public $c4wp_return_setting_object;
	public $c4wp_object;
	public static function c4wp_instance() {
		
		// If the single instance hasn't been set, set it now.
		if ( self::$_c4wp_instance === null )
		self::$_c4wp_instance = new self();

		return self::$_c4wp_instance;
	}
	public function __construct() {

		
		// return plugin setting page links on install plugins
		//add_action( 'plugin_action_links', array( $this, 'c4wp_plugin_setting_links' ), 10, 2 );
		
		// add CSS and JS to make sure the Captcha fits nicely
		add_action( 'wp_enqueue_scripts', array( $this, 'c4wp_public_print_scripts_styles' ) );
		
		// add CSS and JS to make sure the Captcha fits nicely
		add_action( 'login_enqueue_scripts', array( $this, 'c4wp_public_print_scripts_styles' ) );
		
		
		// Load the Required files in admin section.
		$this->c4wp_admin_hooks();
		
		// Load the Required files in public section.
		$this->c4wp_public_hooks();
	}
		
	public function c4wp_admin_hooks() { 
		
		include_once('admin/class-c4wp-admin-settings.php');
		$this->c4wp_return_setting_object = new C4WP_Admin_Settings( $this->c4wp_get_plugin_options() );
	}
	public function c4wp_public_hooks() { 

		include_once('public/image-captcha/class-c4wp-image.php');
		$this->c4wp_object = new C4WP_Image( $this->c4wp_get_plugin_options() );

	}

	public function c4wp_plugin_setting_links( $c4wp_links, $c4wp_file ) {
			
		$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php' ) . '?page=captcha-settings', __( 'Settings', 'wp-captcha' ) );
		array_unshift( $c4wp_links, $settings_link );

		return $c4wp_links;
	}
			
	public function c4wp_public_print_scripts_styles() {
		
		wp_enqueue_script( 'c4wp-public', get_template_directory_uri().'/includes/websima-captcha/assets/js/c4wp-public.js', array('jquery') );
		wp_enqueue_style( 'c4wp-public', get_template_directory_uri().'/includes/websima-captcha/assets/css/c4wp-public.css' );
	}
	
	
	public function c4wp_get_plugin_options() {
		
		if( get_option('c4wp_default_settings') )
		$this->c4wp_plugin_options = get_option('c4wp_default_settings');
		
		return $this->c4wp_plugin_options;
	}
	
	public function c4wp_needed( $enabled_form, $user_loggged_in ) {
		
		if( isset( $this->c4wp_plugin_options['c4wp_options']['enable_form_settings'][$enabled_form] ) )
		return $this->c4wp_plugin_options['c4wp_options']['enable_form_settings'][$enabled_form] && ( ! $user_loggged_in || empty( $this->c4wp_plugin_options['c4wp_options']['other_settings']['hide_for_logged_users'] ) );
	}
}

	function WP_CAPTCHA() {
		
		static $c4wp_instance;

		//first call to c4wp_instance() initializes the plugin
		if ( $c4wp_instance === null || ! ($c4wp_instance instanceof WP_CAPTCHA) )
		$c4wp_instance = WP_CAPTCHA::c4wp_instance();
		
		return $c4wp_instance;
	}
	
	WP_CAPTCHA();
	