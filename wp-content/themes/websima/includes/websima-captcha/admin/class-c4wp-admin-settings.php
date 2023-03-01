<?php
 
class C4WP_Admin_Settings {
	
	public $c4wp_plugin_options;
	public $c4wp_messages = '';
	public $c4wp_setting_messages;


	public function __construct( $c4wp_plugin_options ) {
		
		// add "WP Captcha" Admin Menus page
		add_action( 'admin_menu', array( $this, 'c4wp_register_settings_menu' ) );
		
		$this->c4wp_plugin_options 	=  $c4wp_plugin_options;
		$this->c4wp_setting_messages = array(
			'save_settings'	 	=> 		__( 'تنظیمات ذخیره شد.', 'wp-captcha' ),
		);
	}


	public function c4wp_register_settings_menu() {
		
		$c4wp_menu_page = add_menu_page(
			__( 'کپچا', 'wp-captcha' ), 
			__( 'کپچا', 'wp-captcha' ), 
			'manage_options', 
			'captcha-settings', 
			array( &$this, 'c4wp_menu_page' ),
			'dashicons-privacy'
		);

	}
	
	public function c4wp_menu_page() { 

		$c4wp_messages = $this->c4wp_settings_validate( $this->c4wp_messages );
		include_once('view/class-c4wp-admin-wizard.php');
		C4WP_Admin_Wizard::c4wp_wizard( WP_CAPTCHA()->c4wp_get_plugin_options(), $c4wp_messages );
	}
	

	public function c4wp_settings_validate( $c4wp_messages ) {
	
		if ( isset( $_POST['c4wp_submit'] ) ) { 
			
			$c4wp_options 	= 	array(
				'c4wp_options' 	=> 	array(
					'image_captcha_setting' => 	array(
							'widht' 				=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['widht'] ),
							'height' 				=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['height'] ),
							'characters_on_image' 	=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['characters_on_image'] ),	
							'random_dots' 			=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['random_dots'] ),
							'random_lines' 			=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['random_lines'] ),
							'text_color' 			=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['text_color'] ),
							'noise_color' 			=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['noise_color'] ),
							'character_types' 		=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['character_types'] ),					
							'text_case' 			=> 	strip_tags( $_POST['c4wp_options']['image_captcha_setting']['text_case'] )
					),
					
					'enable_form_settings'		=> 	array(
							'wp_login_form' 			=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wp_login_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wp_login_form'] : false,
							'wp_registration_form' 	   	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wp_registration_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wp_registration_form'] : false,
							'wp_reset_password_form' 	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wp_reset_password_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wp_reset_password_form'] : false,
							'wp_comment_form' 			=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wp_comment_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wp_comment_form'] : false,
							'wc_login_form' 			=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wc_login_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wc_login_form'] : false,
							'wc_registration_form' 	   	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wc_registration_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wc_registration_form'] : false,
							'wc_reset_password_form' 	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wc_reset_password_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wc_reset_password_form'] : false,
							'wc_checkout_form' 		   	=> 	isset( $_POST['c4wp_options']['enable_form_settings']['wc_checkout_form'] ) ? (bool) $_POST['c4wp_options']['enable_form_settings']['wc_checkout_form'] : false
					),
					'other_settings'	=> 	array(
							'captcha_title' 			=> 	isset( $_POST['c4wp_options']['other_settings']['captcha_title'] ) ? strip_tags( $_POST['c4wp_options']['other_settings']['captcha_title'] ) : '',
							'captcha_empty_messages' 	=> 	isset( $_POST['c4wp_options']['other_settings']['captcha_empty_messages'] ) ? strip_tags( $_POST['c4wp_options']['other_settings']['captcha_empty_messages'] ) : '',
							'captcha_error_messages' 	=> 	isset( $_POST['c4wp_options']['other_settings']['captcha_error_messages'] ) ? strip_tags( $_POST['c4wp_options']['other_settings']['captcha_error_messages'] ) : '',
							'hide_for_logged_users' 	=> 	isset( $_POST['c4wp_options']['other_settings']['hide_for_logged_users'] ) ? (bool) $_POST['c4wp_options']['other_settings']['hide_for_logged_users'] : false
					),
				), 
			);
			 
            update_option( 'c4wp_default_settings', $c4wp_options );
            return $c4wp_messages = $this->c4wp_setting_messages['save_settings'];
			
		} 
	}
	 

	public function c4wp_list_files( $folder = '', $levels = 100, $exclusions = array() ) {
			
		if ( empty( $folder ) ) {
			return false;
		}
	
		$folder = trailingslashit( $folder );
	
		if ( ! $levels ) {
			return false;
		}
	
		$files = array();
	
		$dir = @opendir( $folder );
		
		if ( $dir ) {
			
			while ( ( $file = readdir( $dir ) ) !== false ) {
				
				// Skip current and parent folder links.
				if ( in_array( $file, array( '.', '..' ), true ) ) {
					continue;
				}
	
				// Skip hidden and excluded files.
				if ( '.' === $file[0] || in_array( $file, $exclusions, true ) ) {
					continue;
				}
				
				$is_ttf = explode('.', $file);
				$files[$file] = $is_ttf[0];
			}

			closedir( $dir );
		}
	
		return $files;
	}
}