<?php

/**
 * Fired during plugin activation
 *
 * @link       http://woocommerce.db-dzine.de
 * @since      1.0.0
 *
 * @package    WooCommerce_Group_Attributes
 * @subpackage WooCommerce_Group_Attributes/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    WooCommerce_Group_Attributes
 * @subpackage WooCommerce_Group_Attributes/includes
 * @author     Daniel Barenkamp <contact@db-dzine.de>
 */
class WooCommerce_Group_Attributes_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        $woocommerce_group_attributes_options = array(
            'last_tab' => '',
            'enable' => '1',
            'enableFrontend' => '1',
            'enableAttributeGroupCategories' => '0',
            'multipleAttributesInGroups' => '0',
            'showWeight' => '1',
            'showDimensions' => '1',
            'moreText' => 'بیشتر',
            'layout' => '1',
            'layout4Columns' => '3',
            'enableAccordion' => '',
            'attributeValueDivider' => ', ',
            'oddBackgroundColor' => '#FFFFFF',
            'oddTextColor' => '#FFFFFF',
            'evenBackgroundColor' => '#EAEAEA',
            'evenTextColor' => '#FFFFFF',
            'customCSS' => '',
            'customJS' => '',
        );
        update_option('woocommerce_group_attributes_options',$woocommerce_group_attributes_options);
	}
}
