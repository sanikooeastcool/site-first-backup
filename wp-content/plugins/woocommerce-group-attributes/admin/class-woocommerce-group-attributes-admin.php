<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://woocommerce.db-dzine.de
 * @since      1.0.0
 *
 * @package    WooCommerce_Group_Attributes
 * @subpackage WooCommerce_Group_Attributes/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WooCommerce_Group_Attributes
 * @subpackage WooCommerce_Group_Attributes/admin
 * @author     Daniel Barenkamp <contact@db-dzine.de>
 */
class WooCommerce_Group_Attributes_Admin extends WooCommerce_Group_Attributes  {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function load_redux(){
	    // Load the theme/plugin options
	    if ( file_exists( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/options-init.php' ) ) {
	        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/options-init.php';
	    }
	}

    /**
     * Enqueue Admin Styles
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    http://plugins.db-dzine.com
     * @return  boolean
     */
    public function enqueue_styles()
    {
        $screen = get_current_screen();
        if ( $screen->post_type != 'attribute_group' ) {
            return;
        }
        wp_enqueue_style($this->plugin_name.'-select2', plugin_dir_url(__FILE__).'css/select2.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name.'-select2-sortable', plugin_dir_url(__FILE__).'css/select2.sortable.css', array(), $this->version, 'all');
    }

    /**
     * Enqueue Admin Scripts
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    http://woocommerce.db-dzine.de
     * @return  boolean
     */
    public function enqueue_scripts()
    {
    	wp_enqueue_media();

        global $woocommerce;
        if( version_compare( $woocommerce->version, '3.6', ">=" ) ) {
            wp_enqueue_script($this->plugin_name.'-admin', plugin_dir_url(__FILE__).'js/woocommerce-group-attributes-admin.js', array('jquery'), $this->version, true);
        } else {
            wp_enqueue_script($this->plugin_name.'-admin', plugin_dir_url(__FILE__).'js/woocommerce-group-attributes-admin-old.js', array('jquery'), $this->version, true);
        }

        $screen = get_current_screen();
        if ( $screen->post_type != 'attribute_group' ) {
            return;
        }
        wp_enqueue_script($this->plugin_name.'-select2', plugin_dir_url(__FILE__).'js/select2.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name.'-select2-sortable', plugin_dir_url(__FILE__).'js/select2.sortable.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name.'-html5-sortable', plugin_dir_url(__FILE__).'js/html.sortable.min.js', array('jquery'), $this->version, true);
    }

    /**
     * Add admin JS vars
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    http://plugins.db-dzine.com
     * @return  boolean
     */
    public function add_admin_js_vars()
    {
    ?>
    <script type='text/javascript'>
        var woocommerce_group_attribute_settings = <?php echo json_encode(array(
            'ajax_url' => admin_url('admin-ajax.php')
        )); ?>
    </script>
    <?php
    }
}