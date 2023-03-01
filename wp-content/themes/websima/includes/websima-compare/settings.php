<?php
class WC_Settings_Tab_compare {

    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_settings_tab_compare', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_settings_tab_compare', __CLASS__ . '::update_settings' );

        //add custom type
        add_action( 'woocommerce_admin_field_custom_type', __CLASS__ . '::output_custom_type', 10, 1 );
    }

    public static function output_custom_type($value){
        //you can output the custom type in any format you'd like
        echo $value['desc'];
    }


    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_compare'] = 'مقایسه';
        return $settings_tabs;
    }


    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }


    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }


    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {

        $settings = array(
            'compare_settings_general' => array(
                'name'     => 'تنظیمات مقایسه محصولات',
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'compare_settings_general'
            ),
            'compare_unique_id' => array(
                'name' => 'نام برند',
                'type' => 'text',
                'desc' => 'نام برند خود را به انگلیسی و بدون فاصله وارد کنید. (این نام برای ذخیره‌سازی اطلاعات مقایسه استفاده می‌شود)',
                'id'   => 'compare_unique_id'
            ),
            'compare_add_new_image' => array(
                'name' => 'تصویر افزودن محصول جدید',
                'type' => 'url',
                'desc' => 'لینک تصویر "افزودن محصول جدید" را وارد کنید.',
                'id'   => 'compare_add_new_image'
            ),
            'compare_tooltip_in' => array(
                'name' => 'tooltip برای محصولات انتخاب شده',
                'type' => 'text',
                'desc' => 'متن tooltip برای محصولاتی که برای مقایسه انتخاب شده‌اند.',
                'id'   => 'compare_tooltip_in'
            ),
            'compare_tooltip_out' => array(
                'name' => 'tooltip برای محصولات انتخاب نشده',
                'type' => 'text',
                'desc' => 'متن tooltip برای محصولاتی که انتخاب نشده‌اند.',
                'id'   => 'compare_tooltip_out'
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'compare_settings_general_end'
            )
        );

        return apply_filters( 'wc_settings_tab_compare_settings', $settings );
    }

}

WC_Settings_Tab_compare::init();