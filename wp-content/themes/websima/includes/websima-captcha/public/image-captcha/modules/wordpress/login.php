<?php
/**
 * The functionality that handles the display of the Captcha form on the WordPress Login Form.
 * @package  	WP Captcha
 * @subpackage  WP Captcha/modules/wordpress/class-c4wp-login
 * @author   	Devnath verma <devnathverma@gmail.com>
 */

/**
 * Class responsible for generating the display of the Captcha on WordPress Login Form.
 * Gets called only if the "display captcha on WordPress Login Form" option is checked in the back-end
 * @package  	WP Captcha
 * @version  	1.0.0
 * @author   	Devnath verma <devnathverma@gmail.com>
 */
class C4WP_Wordpress_Login {
	
	// @type defaults variables
	public $c4wp_plugin_options;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		
		$this->c4wp_plugin_options 	=  	$c4wp_plugin_options;
		
		// adds the required HTML for the captcha to the WordPress Login Form
		add_action( 'login_form', array( $this, 'c4wp_display_captcha' ) );
		
		// validate the captcha answer on WordPress Login Form
		add_filter( 'wp_authenticate_user', array( $this, 'c4wp_validation_check' ), 10, 2 );	
	}
	
	/**
	 * Generate captcha to Provide a public-facing view for the plugin on WordPress Login Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_display_captcha( ) {
		
		WP_CAPTCHA()->c4wp_object->c4wp_display_captcha();
	}
	
	/**
	 * This function checks validations of the captcha posted with WordPress Login Form.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_validation_check( $user, $password ) {
		
		if( !empty( $_POST ) ) {
		
			$c4wp_empty_messages  = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages'];
			$c4wp_errors_messages = '<strong>' . __( 'Error', 'wp-captcha' ) . '</strong> : '.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages'];
		
				
			if ( ! isset( $_POST['c4wp_user_input_captcha'] ) || empty( $_POST['c4wp_user_input_captcha'] ) ) {
		
				return new WP_Error( 'c4wp_error_messages', $c4wp_empty_messages );
				
			} else if ( !in_array( strtolower($_POST['c4wp_user_input_captcha']), $_SESSION['c4wp_random_input_captcha'], true ) ) {
				
				return new WP_Error( 'c4wp_error_messages', $c4wp_errors_messages );
			
			} else {
				
				unset($_SESSION['c4wp_random_input_captcha']);
				return $user;
			}
			
		}	
	}
}