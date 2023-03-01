<?php

/**
 * Theme option.
 */
add_action('init', 'websima_map_location_init');
function websima_map_location_init()
{
    if (function_exists('acf_add_options_page')) {
        acf_add_options_sub_page(array(
            'page_title'     => 'map',
            'menu_title'    => 'map',
            'menu_slug'        => 'websima-package-map-location-settings',
            'parent_slug'    => 'websima-package-general-settings',
            'capability'    => 'edit_posts'
        ));
    }
}

/**
 * ACF fields.
 */
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_600e8fc10e549',
        'title' => 'map settings',
        'fields' => array(
            array(
                'key' => 'field_600e8fcdb8f1c',
                'label' => 'API key',
                'name' => 'map_api_key',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'websima-package-map-location-settings',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
endif;

/**
 * Main file.
 */
include 'acf/index.php';
