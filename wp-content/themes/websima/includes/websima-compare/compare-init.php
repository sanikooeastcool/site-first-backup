<?php
ob_start();
/**
 * Settings.
 */
require_once( trailingslashit( get_template_directory() ). 'includes/websima-compare/settings.php' );

/**
 * Enqueue scripts and styles.
 */
function websima_compare_enqueue_scripts() {
    if(get_option('compare_unique_id')){ $unique_id = get_option('compare_unique_id'); }else{ $unique_id = 'shop'; }
    wp_enqueue_style( 'websima_compare-general', get_template_directory_uri().'/includes/websima-compare/assets/css/general.css');

    wp_enqueue_script( 'websima_compare_general', get_template_directory_uri() . '/includes/websima-compare/assets/js/general.js', array('jquery'), '1.0.0', true );
    wp_localize_script('websima_compare_general', 'wbcomp_general_data',
        array(
            'unique_id'  =>  esc_attr($unique_id),
            'tooltip_in'  =>  esc_attr(get_option('compare_tooltip_in')),
            'tooltip_out'  =>  esc_attr(get_option('compare_tooltip_out')),
            'compare_page_url'  =>  websima_compare_find_page_id('includes/websima-compare/compare.php'),
        )
    );


    if(is_page_template('includes/websima-compare/compare.php')){
        wp_enqueue_style( 'owl-carousel', get_template_directory_uri().'/includes/websima-compare/assets/css/owl.carousel.min.css');
        //wp_enqueue_style( 'owl-carousel-default', get_template_directory_uri().'/includes/websima-compare/assets/css/owl.theme.default.min.css');
        wp_enqueue_style( 'websima_compare-style', get_template_directory_uri().'/includes/websima-compare/assets/css/style.css');

        wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/includes/websima-compare/assets/js/owl.carousel.min.js', array('jquery'), '1.0.0', true );
        wp_enqueue_script( 'websima_compare_script', get_template_directory_uri() . '/includes/websima-compare/assets/js/script.js', array('jquery'), '1.0.0', true );
        wp_localize_script('websima_compare_script', 'wbcomp_script_data',
            array(
                'unique_id'  =>  esc_attr($unique_id),
                'compare_page_url'  =>  websima_compare_find_page_id('includes/websima-compare/compare.php'),
            )
        );
    }
}
add_action( 'wp_enqueue_scripts', 'websima_compare_enqueue_scripts' );


/**
 * Page template in sub-folder.
 * https://wordpress.stackexchange.com/questions/249984/page-template-in-two-level-deep-folder
 */
function websima_compare_add_templates( $post_templates, $wp_theme, $post, $post_type ) {
    $post_templates['includes/websima-compare/compare.php'] = 'مقایسه';

    return $post_templates;
}
add_filter( 'theme_page_templates', 'websima_compare_add_templates', 10, 4 );


/**
 * Find page id.
 */
function websima_compare_find_page_id($redirect_slug){
    $template_page_property_comparison_array = get_pages( array (
            'meta_key' => '_wp_page_template',
            'meta_value' => $redirect_slug
        )
    );
    if ( $template_page_property_comparison_array ) {
        return get_the_permalink($template_page_property_comparison_array[0]->ID);
    }else {
        return site_url();
    }
}

/**
 * Compare link.
 */
function websima_compare_link(){
    $compare_page_url = websima_compare_find_page_id('includes/websima-compare/compare.php');
    echo '<a href="'.esc_url($compare_page_url).'" class="compare-link">';
        echo '<i class="icon-comparison-list"></i>';
    echo '</a>';
}

/**
 * Compare button.
 */
function websima_compare_btn($pid){
    $compare_page_url = websima_compare_find_page_id('includes/websima-compare/compare.php');
    echo '<div class="compare-btn" data-id="'.esc_attr($pid).'" data-url="'.esc_url($compare_page_url).'" title="'.esc_attr(get_option('compare_tooltip_out')).'">';
        echo '<i class="icon-comparison-list"></i>';
    echo '</div>';
}