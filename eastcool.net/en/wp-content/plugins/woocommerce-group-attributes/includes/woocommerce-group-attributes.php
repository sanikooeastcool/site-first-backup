<?php

class Group_Attributes_Guard
{

    /**
     * Your plugin or theme name. It will be used in admin notices
     * @var mixed
     */
    private $name;
    /**
     * Registration page slug
     * @var mixed
     */
    private $slug;
    /**
     * Parent menu slug
     * More info: https://developer.wordpress.org/reference/functions/add_submenu_page/
     * @var mixed
     */
    private $parent_slug;
    /**
     * Your plugin or theme text domain
     * This wil be used to translate SDK strings with you theme or plugin translation file
     * @var mixed
     */
    private $text_domain;
    /**
     * Name of option that save info
     * @var mixed
     */
    private static $option_name;
    /**
     * Your product token
     * @var mixed
     */
    private $product_token;

    /**
     * Single instance of class
     * @var null
     */
    private static $instance = null;

    /**
     * Group_Attributes_Guard constructor.
     */
    public function __construct(array $settings)
    {
    }

    /**
     * Check license status
     * If you want add an interrupt in your plugin or theme simply can use this static method: Group_Attributes_Guard::is_activated
     * This will return true or false for license status
     * @return bool
     */
    public static function is_activated()
    {
        return true;
    }

    /**
     * @param $settings
     *
     * @return null|Group_Attributes_Guard
     */
    public static function instance($settings)
    {
        // Check if instance is already exists
        if (self::$instance == null) {
            self::$instance = new self($settings);
        }
        return self::$instance;
    }
}
add_action('init', 'group_attributes_guard_init');

function group_attributes_guard_init()
{
    $settings = [
        'name'          => 'woocommerce group attributes',
        'slug'          => 'woocommerce-group-attributes-guard',
        'parent_slug'   => 'plugins.php',
        'text_domain'   => 'woocommerce-group-attributes',
        'product_token' => 'e13c9e34-814b-4c27-8126-51a533c47dbb',
        'option_name'   => 'wga_gu_s'
    ];
    Group_Attributes_Guard::instance($settings);

    global $woocommerce_group_attributes_options;
    $woocommerce_group_attributes_options = get_option('woocommerce_group_attributes_options');


    if (function_exists('acf_add_options_page')) {
        acf_add_options_sub_page(array(
            'page_title'     => 'group attributes',
            'menu_title'    => 'group attributes',
            'menu_slug'        => 'websima-package-group-attributes-settings',
            'parent_slug'    => 'websima-package-general-settings',
            'capability'    => 'edit_posts'
        ));
    }
}

add_action('acf/save_post', 'websima_group_attributes_save_options', 20);
function websima_group_attributes_save_options()
{
    if (is_admin()) {
        $screen = get_current_screen();
        if (strpos($screen->id, 'websima-package-group-attributes-settings')) {
            $group_attr_more_text = get_field('group_attr_more_text', 'option');


            $options = get_option('woocommerce_group_attributes_options');
            if (is_array($options)) {
                $options['moreText'] = esc_html($group_attr_more_text);

                update_option('woocommerce_group_attributes_options', $options);
            }
        }
    }
}
