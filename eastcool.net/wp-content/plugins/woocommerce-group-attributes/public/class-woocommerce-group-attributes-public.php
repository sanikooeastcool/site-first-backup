<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://woocommerce.db-dzine.de
 * @since      1.0.0
 *
 * @package    WooCommerce_Group_Attributes
 * @subpackage WooCommerce_Group_Attributes/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WooCommerce_Group_Attributes
 * @subpackage WooCommerce_Group_Attributes/public
 * @author     Daniel Barenkamp <contact@db-dzine.de>
 */
class WooCommerce_Group_Attributes_Public extends WooCommerce_Group_Attributes {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version;

	/**
	 * options of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $options
	 */
	protected $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		global $woocommerce_group_attributes_options;

		$this->options = $woocommerce_group_attributes_options;

		if (!$this->get_option('enable') || !$this->get_option('enableFrontend')) {
			return;
		}

		wp_enqueue_style( $this->plugin_name.'-public', plugin_dir_url( __FILE__ ) . 'css/woocommerce-group-attributes-public.css', array(), $this->version, 'all' );

		$css = ".shop_attributes tr, .shop_attributes tr td { 
					background-color: " . $woocommerce_group_attributes_options['oddBackgroundColor'] . " !important;
					color: " . $woocommerce_group_attributes_options['oddTextColor'] . " !important;
				} 
				.shop_attributes tr.alt, .shop_attributes tr.alt td { 
					background-color: " . $woocommerce_group_attributes_options['evenBackgroundColor'] . " !important;
					color: " . $woocommerce_group_attributes_options['evenTextColor'] . " !important;
				}
				";

		$customCSS = $this->get_option('customCSS');
		if(!empty($customCSS)) {
			$css = $css . $customCSS;
		}

		file_put_contents( __DIR__  . '/css/woocommerce-group-attributes-custom.css', $css);

		wp_enqueue_style( $this->plugin_name.'-custom', plugin_dir_url( __FILE__ ) . 'css/woocommerce-group-attributes-custom.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $woocommerce_group_attributes_options;

		$this->options = $woocommerce_group_attributes_options;

		if (!$this->get_option('enable') || !$this->get_option('enableFrontend')) {
			return;
		}

		wp_enqueue_script( $this->plugin_name.'-public', plugin_dir_url( __FILE__ ) . 'js/woocommerce-group-attributes-public.js', array( 'jquery' ), $this->version, false );

		$customJS = $this->get_option('customJS');
		if(empty($customJS)) {
			return false;
		}

		file_put_contents( __DIR__  . '/js/woocommerce-group-attributes-custom.js', $customJS);

		wp_enqueue_script( $this->plugin_name.'-custom', plugin_dir_url( __FILE__ ) . 'js/woocommerce-group-attributes-custom.js', array( 'jquery' ), $this->version, false );
		
	}

	/**
	 * Inits Group Attributes
	 *
	 * @since    1.0.0
	 */
    public function init()
    {

		global $woocommerce_group_attributes_options;

		$this->options = $woocommerce_group_attributes_options;

		if (!$this->get_option('enable') || !$this->get_option('enableFrontend')) {

			add_shortcode('woocommerce_group_attributes_table', array($this, 'get_woocommerce_group_attributes_table'));
			
			return false;
		}

		add_filter( 'wc_get_template', array($this, 'modify_attribute_template'), 10, 5 );
		add_shortcode('woocommerce_group_attributes_table', array($this, 'get_woocommerce_group_attributes_table'));

    }
	
	public function modify_attribute_template( $located, $template_name)
	{
		global $post;

		if( 'single-product/product-attributes.php' === $template_name){

			$layout = $this->get_option('layout');
			$layout = apply_filters('woocommerce_group_attributes_layout', $layout, $post->ID);

			return  __DIR__  . '/partials/woocommerce-group-attributes-output-layout-' . $layout . '.php';
		}

		return $located;
	}

	public function get_woocommerce_group_attributes_table($atts = array())
    {
        global $post, $product;

        ob_start();

        $args = shortcode_atts(array(
            'layout' => '',
            'product_id' => '',
        ), $atts);

        $layout = $args['layout'];
        $product_id = $args['product_id'];

        if(!empty($product_id)) {
        	$product = wc_get_product($product_id);
    	}

    	if(!is_object($product)) {
    		echo __('No product found or specified!', 'woocommerce-group-attributes');
    		return;
    	}

        if(empty($layout)) {
			$layout = $this->get_option('layout');
        }

        $layout = apply_filters('woocommerce_group_attributes_layout', $layout, $product->get_id());
        $table = include( __DIR__  . '/partials/woocommerce-group-attributes-output-layout-' . $layout . '.php' );

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
        
    }

}