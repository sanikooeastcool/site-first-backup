<?php

/**
 * For full documentation, please visit: http://docs.reduxframework.com/
 * For a more extensive sample-config file, you may look at:
 * https://github.com/reduxframework/redux-framework/blob/master/sample/sample-config.php
 */

if (!class_exists('Redux')) {
    return;
}

// This is your option name where all the Redux data is stored.
$opt_name = "woocommerce_group_attributes_options";

/**
 * ---> SET ARGUMENTS
 * All the possible arguments for Redux.
 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
 * */

$theme = wp_get_theme(); // For use with some settings. Not necessary.

$args = array(
    'opt_name' => 'woocommerce_group_attributes_options',
    'use_cdn' => TRUE,
    'dev_mode' => FALSE,
    'display_name' => 'woocommerce group attributes',
    'display_version' => '1.7.2',
    'page_title' => 'woocommerce group attributes',
    'update_notice' => TRUE,
    'intro_text' => '',
    'footer_text' => '&copy; ' . date('Y') . ' weLaunch',
    'admin_bar' => TRUE,
    'menu_type' => 'submenu',
    'menu_title' => 'group attributes',
    'allow_sub_menu' => TRUE,
    'page_parent' => 'woocommerce',
    'page_parent_post_type' => 'your_post_type',
    'customizer' => FALSE,
    'default_mark' => '*',
    'hints' => array(
        'icon_position' => 'right',
        'icon_color' => 'lightgray',
        'icon_size' => 'normal',
        'tip_style' => array(
            'color' => 'light',
        ),
        'tip_position' => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect' => array(
            'show' => array(
                'duration' => '500',
                'event' => 'mouseover',
            ),
            'hide' => array(
                'duration' => '500',
                'event' => 'mouseleave unfocus',
            ),
        ),
    ),
    'output' => TRUE,
    'output_tag' => TRUE,
    'settings_api' => TRUE,
    'cdn_check_time' => '1440',
    'compiler' => TRUE,
    'page_permissions' => 'manage_options',
    'save_defaults' => TRUE,
    'show_import_export' => TRUE,
    'database' => 'options',
    'transient_time' => '3600',
    'network_sites' => TRUE,
);

Redux::setArgs($opt_name, $args);

/*
     * ---> END ARGUMENTS
     */

/*
     * ---> START HELP TABS
     */

$tabs = array(
    array(
        'id'      => 'help-tab',
        'title'   => __('Information', 'woocommerce-group-attributes'),
        'content' => __('<p>Need support? Please use the comment function on codecanyon.</p>', 'woocommerce-group-attributes')
    ),
);
Redux::setHelpTab($opt_name, $tabs);

// Set the help sidebar
// $content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'woocommerce-group-attributes' );
// Redux::setHelpSidebar( $opt_name, $content );


/*
     * <--- END HELP TABS
     */


/*
     *
     * ---> START SECTIONS
     *
     */

Redux::setSection($opt_name, array(
    'title'  => __('Group Attributes', 'woocommerce-group-attributes'),
    'id'     => 'general',
    'desc'   => __('Need support? Please use the comment function on codecanyon.', 'woocommerce-group-attributes'),
    'icon'   => 'el el-home',
));

Redux::setSection($opt_name, array(
    'title'      => __('General', 'woocommerce-group-attributes'),
    // 'desc'       => __( '', 'woocommerce-group-attributes' ),
    'id'         => 'general-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'enable',
            'type'     => 'checkbox',
            'title'    => __('Enable', 'woocommerce-group-attributes'),
            'subtitle' => __('Enable group attributes.', 'woocommerce-group-attributes'),
            'default' => 1
        ),
        array(
            'id'       => 'enableFrontend',
            'type'     => 'checkbox',
            'title'    => __('Enable Frontend', 'woocommerce-group-attributes'),
            'subtitle' => __('Change attribute layout in frontend. Disable that to only use backend functionality.', 'woocommerce-group-attributes'),
            'default' => 1
        ),
        array(
            'id'       => 'enableAttributeGroupCategories',
            'type'     => 'checkbox',
            'title'    => __('Enable Attribute Group Categories', 'woocommerce-group-attributes'),
            'subtitle' => __('Attribute group categories can contain multiple attribute groups. These categories can be loaded in the backend when you edit a product. To create categories, simply edit a attribute group and you will see categories in the right sidebar.', 'woocommerce-group-attributes'),
            'default' => 1
        ),
        array(
            'id'       => 'multipleAttributesInGroups',
            'type'     => 'checkbox',
            'title'    => __('Multiple Attributes', 'woocommerce-group-attributes'),
            'subtitle' => __('Allow Attributes to be in multiple attribute groups. <br/>E.g. the color attribute can be in more than 1 attribute group!', 'woocommerce-group-attributes'),
            'default' => 0
        ),
        array(
            'id'       => 'showWeight',
            'type'     => 'checkbox',
            'title'    => __('Show Weight', 'woocommerce-group-attributes'),
            'default' => 1
        ),
        array(
            'id'       => 'showDimensions',
            'type'     => 'checkbox',
            'title'    => __('Show Dimensions', 'woocommerce-group-attributes'),
            'default' => 1
        ),
        array(
            'id'       => 'moreText',
            'type'     => 'text',
            'title'    => __('More Text', 'woocommerce-group-attributes'),
            'subtitle' => __('Text for more attribute group.', 'woocommerce-group-attributes'),
            'default'  => __('More', 'woocommerce-group-attributes'),
        ),
    )
));

Redux::setSection($opt_name, array(
    'title'      => __('Styling', 'woocommerce-group-attributes'),
    // 'desc'       => __( '', 'woocommerce-group-attributes' ),
    'id'         => 'styling-settings',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'layout',
            'type'     => 'image_select',
            'title'    => __('Select Layout', 'woocommerce-group-attributes'),
            'options'  => array(
                '1'      => array('img'   => plugin_dir_url(__FILE__) . 'img/1.jpg'),
                '2'      => array('img'   => plugin_dir_url(__FILE__) . 'img/2.jpg'),
                '3'      => array('img'   => plugin_dir_url(__FILE__) . 'img/3.jpg'),
                '4'      => array('img'   => plugin_dir_url(__FILE__) . 'img/4.jpg'),
            ),
            'default' => '1'
        ),
        array(
            'id'       => 'layout4Columns',
            'type'     => 'spinner',
            'title'    => __('columns', 'wordpress-store-locator'),
            'subtitle'     => __('Number of columns to display properties in the number layout 4'),
            'min'      => '1',
            'step'     => '1',
            'max'      => '12',
            'default'  => '3',
            'required' => array('layout', 'equals', '4'),
        ),
        array(
            'id'       => 'enableAccordion',
            'type'     => 'checkbox',
            'title'    => __('Enable Accordion', 'woocommerce-group-attributes'),
            'subtitle' => __('Attribute Groups will be hidden in accordions.', 'woocommerce-group-attributes'),
        ),
        array(
            'id'     => 'attributeValueDivider',
            'type' => 'select',
            'title' => __('Attribute Value Divider', 'woocommerce-group-attributes'),
            'options' => array(
                ', ' => __('Comma', 'woocommerce-group-attributes'),
                '<br>' => __('New Line', 'woocommerce-group-attributes'),
                ' | ' => __('Pipe', 'woocommerce-group-attributes'),
            ),
            'default' => ', ',
        ),
        array(
            'id'     => 'oddBackgroundColor',
            'type' => 'color',
            'title' => __('Odd Background Color', 'woocommerce-group-attributes'),
            'validate' => 'color',
            'default' => '#FFFFFF',
        ),
        array(
            'id'     => 'oddTextColor',
            'type' => 'color',
            'title' => __('Odd Text Color', 'woocommerce-group-attributes'),
            'validate' => 'color',
            'default' => '#FFFFFF',
        ),
        array(
            'id'     => 'evenBackgroundColor',
            'type' => 'color',
            'title' => __('Even Background color', 'woocommerce-group-attributes'),
            'validate' => 'color',
            'default' => '#EAEAEA',
        ),
        array(
            'id'     => 'evenTextColor',
            'type' => 'color',
            'title' => __('Even Text color', 'woocommerce-group-attributes'),
            'validate' => 'color',
            'default' => '#FFFFFF',
        ),
    )
));

Redux::setSection($opt_name, array(
    'title'      => __('Advanced settings', 'woocommerce-group-attributes'),
    'desc'       => __('Custom stylesheet / javascript.', 'woocommerce-group-attributes'),
    'id'         => 'advanced',
    'subsection' => true,
    'fields'     => array(
        array(
            'id'       => 'customCSS',
            'type'     => 'ace_editor',
            'mode'     => 'css',
            'title'    => __('Custom CSS', 'woocommerce-group-attributes'),
            'subtitle' => __('Add some stylesheet if you want.', 'woocommerce-group-attributes'),
        ),
        array(
            'id'       => 'customJS',
            'type'     => 'ace_editor',
            'mode'     => 'javascript',
            'title'    => __('Custom JS', 'woocommerce-group-attributes'),
            'subtitle' => __('Add some javascript if you want.', 'woocommerce-group-attributes'),
        ),
    )
));


    /*
     * <--- END SECTIONS
     */
