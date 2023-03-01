<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://woocommerce.db-dzine.de
 * @since      1.0.0
 *
 * @package    WooCommerce_Group_Attributes
 * @subpackage WooCommerce_Group_Attributes/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WooCommerce_Group_Attributes
 * @subpackage WooCommerce_Group_Attributes/includes
 * @author     Daniel Barenkamp <contact@db-dzine.de>
 */
class WooCommerce_Group_Attributes {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WooCommerce_Group_Attributes_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */

	public function __construct($version) {

		$this->plugin_name = 'woocommerce-group-attributes';
		$this->version = $version;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WooCommerce_Group_Attributes_Loader. Orchestrates the hooks of the plugin.
	 * - WooCommerce_Group_Attributes_i18n. Defines internationalization functionality.
	 * - WooCommerce_Group_Attributes_Admin. Defines all hooks for the admin area.
	 * - WooCommerce_Group_Attributes_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-group-attributes-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woocommerce-group-attributes-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-group-attributes-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woocommerce-group-attributes-post-type.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woocommerce-group-attributes-public.php';

		$this->loader = new WooCommerce_Group_Attributes_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WooCommerce_Group_Attributes_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$this->plugin_i18n = new WooCommerce_Group_Attributes_i18n();

		$this->loader->add_action( 'plugins_loaded', $this->plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$this->plugin_admin = new WooCommerce_Group_Attributes_Admin( $this->get_plugin_name(), $this->get_version() );

		//$this->loader->add_action( 'plugins_loaded', $this->plugin_admin, 'load_redux' );

		$this->loader->add_action('admin_head', $this->plugin_admin, 'add_admin_js_vars', 10);
		$this->loader->add_action('admin_enqueue_scripts', $this->plugin_admin, 'enqueue_styles', 999);
		$this->loader->add_action('admin_enqueue_scripts', $this->plugin_admin, 'enqueue_scripts', 999);

		$this->group_attributes_post_type = new WooCommerce_Group_Attributes_Post_Type( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'init', $this->group_attributes_post_type, 'init' );
		$this->loader->add_filter( 'manage_attribute_group_posts_columns', $this->group_attributes_post_type, 'columns_head');
		$this->loader->add_action( 'manage_attribute_group_posts_custom_column', $this->group_attributes_post_type, 'columns_content', 10, 1);

		$this->loader->add_action( 'woocommerce_product_options_attributes', $this->group_attributes_post_type, 'show_attribute_group_toolbar');
		$this->loader->add_action( 'wp_ajax_get_attributes_by_attribute_group_id', $this->group_attributes_post_type, 'get_attributes_by_attribute_group_id');

		$this->loader->add_action( 'pre_get_posts', $this->group_attributes_post_type, 'attribute_group_order', 10 );
        $this->loader->add_action( 'add_meta_boxes', $this->group_attributes_post_type, 'add_custom_metaboxes', 10, 2);
        $this->loader->add_action( 'save_post', $this->group_attributes_post_type, 'save_custom_metaboxes', 1, 2);

        //$this->loader->add_action( 'admin_menu', $this->group_attributes_post_type, 'add_attribute_categories_menu', 1, 150);

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$this->plugin_public = new WooCommerce_Group_Attributes_Public( $this->get_plugin_name(), $this->get_version() );

		/*$this->loader->add_action( 'wp_enqueue_scripts', $this->plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $this->plugin_public, 'enqueue_scripts' );*/

		$this->loader->add_action( 'woocommerce_init', $this->plugin_public, 'init' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WooCommerce_Group_Attributes_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

		/**
	 * Gets options
	 *
	 * @since    1.0.0
	 */
    protected function get_option($option)
    {
        $woocommerce_group_attributes_options = get_option('woocommerce_group_attributes_options');
    	if(!is_array($woocommerce_group_attributes_options)) {
    		return false;
    	}
    	if(!array_key_exists($option, $woocommerce_group_attributes_options))
    	{
    		return false;
    	}
    	return $woocommerce_group_attributes_options[$option];
    }
}
