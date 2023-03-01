<?php 
class C4WP_Image {
	
	// @type defaults variables
	public $c4wp_plugin_options;
	
	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $c4wp_plugin_options ) {
		if ( '' === session_id() ) {
			
			@session_start();
		}
		
		$this->c4wp_plugin_options 	=  $c4wp_plugin_options;
		
		// Hook to the 'init' action, which is called Image Captcha actions and filters.
		add_action( 'init', array( $this, 'c4wp_iamge_actions_filters' ), 9 );
		add_action( 'wp_ajax_c4wp_refresh_captcha', array( $this, 'c4wp_refresh_captcha' ) );
		add_action( 'wp_ajax_nopriv_c4wp_refresh_captcha', array( $this, 'c4wp_refresh_captcha' ) );
	}
	
	/**
	 * Refresh captcha for the plugin.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_refresh_captcha( ) {

		if ( isset( $_REQUEST['c4wp_random_input_captcha'] ) ) {
			
		    $c4wp_options = get_option('c4wp_default_settings');
			$c4wp_captcha_type	= $c4wp_options['c4wp_options']['image_captcha_setting']['character_types'];
            $c4wp_text_case		= $c4wp_options['c4wp_options']['image_captcha_setting']['text_case'];
			switch ( $c4wp_captcha_type ) {
				case 'only_numbers':
					
					$c4wp_possible_letters = '0123456789';
					
					break;

				case 'only_alphabets':
					
					if ( 'mixed' == $c4wp_text_case ) {
						
						$c4wp_possible_letters = 'ABCDEFGHKLMNPRSTUVWYZabcdefghklmnprstuvwyz';
					
					} else if ( 'upper_case' == $c4wp_text_case ) {
					
						$c4wp_possible_letters = 'ABCDEFGHKLMNPRSTUVWYZ';
					
					} else {
					
						$c4wp_possible_letters = 'abcdefghklmnprstuvwyz';
					}
					
					break;

				case 'alphabets_and_numbers':
					
					if ( 'mixed' == $c4wp_text_case ) {
					
						$c4wp_possible_letters = 'ABCDEFGHKLMNPRSTUVWYZabcdefghklmnprstuvwyz0123456789';
					
					} else if ( 'upper_case' == $c4wp_text_case ) {
					
						$c4wp_possible_letters = 'ABCDEFGHKLMNPRSTUVWYZ0123456789';
					
					} else {
					
						$c4wp_possible_letters = 'abcdefghklmnprstuvwyz0123456789';
					}
					
					break;
			}

			$c4wp_imgcaptcha_paramenters['c4wp_image_width'] 	  = $c4wp_options['c4wp_options']['image_captcha_setting']['widht'];
			$c4wp_imgcaptcha_paramenters['c4wp_image_height'] 	  = $c4wp_options['c4wp_options']['image_captcha_setting']['height'];
			$c4wp_imgcaptcha_paramenters['c4wp_fonts']			  = dirname( dirname( dirname( __FILE__ ) ) ) .'/assets/fonts/Jura.ttf';
			$c4wp_imgcaptcha_paramenters['c4wp_char_on_image'] 	  = $c4wp_options['c4wp_options']['image_captcha_setting']['characters_on_image'];
			$c4wp_imgcaptcha_paramenters['c4wp_random_dots'] 	  = $c4wp_options['c4wp_options']['image_captcha_setting']['random_dots'];
			$c4wp_imgcaptcha_paramenters['c4wp_random_lines'] 	  = $c4wp_options['c4wp_options']['image_captcha_setting']['random_lines'];
			$c4wp_imgcaptcha_paramenters['c4wp_text_color'] 	  = $c4wp_options['c4wp_options']['image_captcha_setting']['text_color'];
			$c4wp_imgcaptcha_paramenters['c4wp_noice_color'] 	  = $c4wp_options['c4wp_options']['image_captcha_setting']['noise_color'];
			$c4wp_imgcaptcha_paramenters['c4wp_possible_letters'] = $c4wp_possible_letters;
			$c4wp_return_imgobj = new C4WP_Create_Image_Captcha( $c4wp_imgcaptcha_paramenters );
			$c4wp_return_imgobj->createCaptcha();
		}
		
		exit;
	}
	
	/**
	 * Apply required filters.
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
	public function c4wp_iamge_actions_filters( ) {
		
		$c4wp_user_loggged_in = is_user_logged_in();
		
		// IF captcha enabled for " Wordpress Login Form " 
		if ( isset( $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_login_form'] ) && $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_login_form'] ) {
			
			include_once( 'modules/wordpress/login.php' );
			new C4WP_Wordpress_Login( $this->c4wp_plugin_options );
		}
		
		
		// IF captcha enabled for " Wordpress Comments Form " 
		if ( WP_CAPTCHA()->c4wp_needed( 'wp_comment_form', $c4wp_user_loggged_in ) ) {
		
			include_once( 'modules/wordpress/comments.php' );
			new C4WP_Wordpress_Comments( $this->c4wp_plugin_options );
		}
		
		
		// IF captcha enabled for " Woocommerce Checkout Form " 
		if ( WP_CAPTCHA()->c4wp_needed( 'wc_checkout_form', $c4wp_user_loggged_in ) ) {
			include_once( 'modules/woocommerce/checkout.php' );  
			new C4WP_Woocommerce_Checkout( $this->c4wp_plugin_options );	
		}
		
		// IF captcha enabled for " Wordpress Reset Password Form " 
		if( isset( $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_reset_password_form'] ) && $this->c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_reset_password_form'] ) {
			include_once( 'modules/wordpress/reset-password.php' );
			new C4WP_Wordpress_Reset_Password( $this->c4wp_plugin_options );				
		}
	}

	/**
	 * Display captcha 
	 
	 */
	public function c4wp_display_captcha( $mode = '' ) {
		
		echo $this->c4wp_generate_captcha( $mode );
	}
	
	/**
	 * Generate captcha 
	 */
	public function c4wp_generate_captcha( $mode = '' ) {
		$create_image_url   = admin_url( 'admin-ajax.php' ) . '?action=c4wp_refresh_captcha&c4wp_random_input_captcha=';
		$return  = '';
		if($mode == 'af-form'){ $return .= '<div class="acf-field"><div class="acf-input">'; }
		$return .= '<p class="c4wp-display-captcha-form">';
		
		if( isset($this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title']) && ! empty( $this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title'] ) ) {
		
			$return .= '<label for="'.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title'].'">'.$this->c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title'].'</label>';
		}
		$return .= '<a href="javascript:void(0);" class="refresh_captcha" data-imgsrc="'.$create_image_url . rand( 111, 99999 ) . '" rel="nofollow noopener noreferrer"><img src="'.get_template_directory_uri().'/includes/websima-captcha/assets/images/c4wp-refresh-captcha.png" class="c4wp-refresh-captcha"/></a>';
		$return .= '<img src="'.$create_image_url . rand( 111, 99999 ).'" class="c4wp_image"/>';
		
		$return .= '<input id="c4wp_user_input_captcha" name="c4wp_user_input_captcha" class="c4wp_user_input_captcha" type="text" placeholder="عبارت امنیتی" autocomplete="off">';
		$return .= '</p>';
		if($mode == 'af-form'){ $return .= '</div></div>'; }
						
		return $return;
	}
}


/**
Create image
 */
	
class C4WP_Create_Image_Captcha {

    // Configuration Options
	public $c4wp_image_width;
	public $c4wp_image_height;
	public $c4wp_fonts;
	public $c4wp_char_on_image;
	public $c4wp_random_dots;
	public $c4wp_random_lines;
	public $c4wp_text_color;
	public $c4wp_noice_color;
	public $c4wp_possible_letters;
    
	/**
	 * Initialize the class and set its properties.
	 */
    public function __construct( $c4wp_paramenters ) {
        
        $this->c4wp_image_width 		=  $c4wp_paramenters['c4wp_image_width'];
		$this->c4wp_image_height 		=  $c4wp_paramenters['c4wp_image_height'];
		$this->c4wp_fonts 				=  $c4wp_paramenters['c4wp_fonts'];
		$this->c4wp_char_on_image 		=  $c4wp_paramenters['c4wp_char_on_image'];
		$this->c4wp_random_dots 		=  $c4wp_paramenters['c4wp_random_dots'];
		$this->c4wp_random_lines 		=  $c4wp_paramenters['c4wp_random_lines'];
		$this->c4wp_text_color 			=  $c4wp_paramenters['c4wp_text_color'];
		$this->c4wp_noice_color 		=  $c4wp_paramenters['c4wp_noice_color'];
		$this->c4wp_possible_letters 	=  $c4wp_paramenters['c4wp_possible_letters'];
        
        if ( ! extension_loaded( 'gd' ) ) {
            
			return FALSE;
        }
    }
    
	/**
	 * The Functions Create captcha image for the plugin. 
	 * @package  WP Captcha
	 * @version  1.0.0
	 * @author   Devnath verma <devnathverma@gmail.com>
	 */
    public function createCaptcha( ){
        
		$c4wp_return_words = '';
		
		$i = 0;
		while( $i < $this->c4wp_char_on_image ) { 
		
			$c4wp_return_words .= substr( $this->c4wp_possible_letters, mt_rand( 0, strlen( $this->c4wp_possible_letters )-1 ), 1 );
			$i++;
		}
		
		$c4wp_font_size = $this->c4wp_image_height * 0.55;
		$c4wp_image = @imagecreate( $this->c4wp_image_width, $this->c4wp_image_height );
		
		// setting the background, text and noise colours here
		$c4wp_background_color = imagecolorallocate( $c4wp_image, 255, 255, 255 );
		$c4wp_arr_text_color = $this->hexrgb( $this->c4wp_text_color );
		$c4wp_text_color = imagecolorallocate( $c4wp_image, $c4wp_arr_text_color['red'], $c4wp_arr_text_color['green'], $c4wp_arr_text_color['blue'] );
		
		$c4wp_arr_noice_color = $this->hexrgb( $this->c4wp_noice_color );
		$c4wp_noice_color = imagecolorallocate($c4wp_image, $c4wp_arr_noice_color['red'], 
		$c4wp_arr_noice_color['green'], $c4wp_arr_noice_color['blue']);
		
		// generating the dots randomly in background 
		for( $i = 0; $i < $this->c4wp_random_dots; $i++ ) {
		
			imagefilledellipse($c4wp_image, mt_rand(0,$this->c4wp_image_width), mt_rand(0,$this->c4wp_image_height), 2, 3, $c4wp_noice_color);
		}
		
		// generating lines randomly in background of image 
		for( $i = 0; $i < $this->c4wp_random_lines; $i++ ) {
			imageline( $c4wp_image, mt_rand( 0, $this->c4wp_image_width ), mt_rand( 0, $this->c4wp_image_height ), mt_rand( 0, $this->c4wp_image_width ), mt_rand( 0,$this->c4wp_image_height ), $c4wp_noice_color );
		}
		
		// create a text box and add 6 letters code in it 
		$textbox = imagettfbbox( $c4wp_font_size, 0, $this->c4wp_fonts, $c4wp_return_words ); 
		$x = ( $this->c4wp_image_width - $textbox[4] ) / 2;
		$y = ( $this->c4wp_image_height - $textbox[5] ) / 2;
		imagettftext( $c4wp_image, $c4wp_font_size, 0, $x, $y, $c4wp_text_color, $this->c4wp_fonts , $c4wp_return_words );
		
		// Show captcha image in the page html page
		header( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Pragma: no-cache' );
		header( 'Content-Type: image/jpeg' );
		imagejpeg($c4wp_image,NULL,90);
        imagedestroy($c4wp_image); 
		$_SESSION['c4wp_random_input_captcha'][] =  strtolower($c4wp_return_words);
    }
	
	public function hexrgb( $hexstr ) {

	  $c4wp_int = hexdec( $hexstr );
	
	  return array( 'red' 	=> 0xFF & ( $c4wp_int >> 0x10 ),
					'green' => 0xFF & ( $c4wp_int >> 0x8 ),
					'blue' 	=> 0xFF & $c4wp_int );
	}
}	