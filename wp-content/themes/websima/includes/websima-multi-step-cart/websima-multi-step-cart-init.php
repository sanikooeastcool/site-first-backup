<?php
add_action('init','websima_msc_init');
function websima_msc_init(){
    if( function_exists('acf_add_options_page') ) {
        acf_add_options_sub_page(array(
            'page_title' 	=> 'سبد خرید چند مرحله ای',
            'menu_title'	=> 'سبد خرید چند مرحله ای',
            'menu_slug'	    => 'websima-package-multi-step-cart-settings',
            'parent_slug'	=> 'websima-package-general-settings',
            'capability'	=> 'edit_posts'
        ));
    }
}

add_action('woocommerce_before_cart', 'websima_msc_before_cart_checkout',0,1);
add_action('woocommerce_before_checkout_form', 'websima_msc_before_cart_checkout',0,1);
add_action('woocommerce_before_thankyou', 'websima_msc_before_cart_checkout',0,1);
add_action('woocommerce_before_order_receipt', 'websima_msc_before_cart_checkout',0,1);
function websima_msc_before_cart_checkout() {
    $first_step_title = get_field('msc_first_step_title', 'option');
    $first_step_img = get_field('msc_first_step_img', 'option');
    $second_step_title = get_field('msc_second_step_title', 'option');
    $second_step_img = get_field('msc_second_step_img', 'option');
    $third_step_title = get_field('msc_third_step_title', 'option');
    $third_step_img = get_field('msc_third_step_img', 'option');
    $fourth_step_title = get_field('msc_fourth_step_title', 'option');
    $fourth_step_img = get_field('msc_fourth_step_img', 'option');

    echo '<div class="route-bar">';
        echo '<div class="row">';

            echo '<div class="col-3 step-box first-step">';
                echo '<div class="wrapper">';
                    echo '<a href="'.wc_get_cart_url().'" class="icon">';
                        echo '<img src="'.esc_url(websima_msc_img_url($first_step_img)).'" alt="'.esc_attr($first_step_title).'" class="icon-svg"/>';
                    echo '</a>';
                    echo '<b class="title"><a href="'.wc_get_cart_url().'">'.esc_html($first_step_title).'</a></b>';
                echo '</div>';
            echo '</div>';

            echo '<div class="col-3 step-box second-step">';
                echo '<div class="wrapper">';
                    echo '<a href="'.wc_get_checkout_url().'" class="icon">';
                        echo '<img src="'.esc_url(websima_msc_img_url($second_step_img)).'" alt="'.esc_attr($second_step_title).'" class="icon-svg"/>';
                    echo '</a>';
                    echo '<b class="title"><a href="'.wc_get_checkout_url().'">'.esc_html($second_step_title).'</a></b>';
                echo '</div>';
            echo '</div>';

            echo '<div class="col-3 step-box third-step">';
                echo '<div class="wrapper">';
                    echo '<span class="icon">';
                        echo '<img src="'.esc_url(websima_msc_img_url($third_step_img)).'" alt="'.esc_attr($third_step_title).'" class="icon-svg"/>';
                    echo '</span>';
                    echo '<b class="title">'.esc_html($third_step_title).'</b>';
                echo '</div>';
            echo '</div>';

            echo '<div class="col-3 step-box fourth-step">';
                echo '<div class="wrapper">';
                    echo '<span class="icon">';
                        echo '<img src="'.esc_url(websima_msc_img_url($fourth_step_img)).'" alt="'.esc_attr($fourth_step_title).'" class="icon-svg"/>';
                    echo '</span>';
                    echo '<b class="title">'.esc_html($fourth_step_title).'</b>';
                echo '</div>';
            echo '</div>';

        echo '</div>';
    echo '</div>';
}

function websima_msc_img_url($image_id){
    return wp_get_attachment_image_src(esc_attr($image_id),'thumbnail')[0];
}