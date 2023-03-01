<?php
global $options;
define('WB_WISHLIST_NAME', 'wb_wishlist');

add_action("wp_ajax_websima_wc_wishlist_add", "websima_wc_wishlist_add");
add_action("wp_ajax_nopriv_websima_wc_wishlist_add", "websima_wc_wishlist_add");
function websima_wc_wishlist_add()
{
    $pid = $_POST['pid'];
    $uid = get_current_user_id();
    if (is_user_logged_in()) {
        $wish_user = get_user_meta($uid, WB_WISHLIST_NAME, true);
        if (!$wish_user) {
            update_user_meta($uid, WB_WISHLIST_NAME, serialize(array($pid)));
        } else {
            $wish_user_array = unserialize($wish_user);
            $wish_user_array[] = $pid;
            update_user_meta($uid, WB_WISHLIST_NAME, serialize(array_unique($wish_user_array)));
        }
    } else {
        $cookie_name = WB_WISHLIST_NAME;
        if (isset($_COOKIE[$cookie_name])) {
            $cookie_value = explode("+", $_COOKIE[$cookie_name]);
            $cookie_value[] = $pid;
        } else {
            $cookie_value = array($pid);
        }
        $value_convert = implode("+", array_unique($cookie_value));
        setcookie($cookie_name, $value_convert, time() + (86400 * 30), "/"); // 86400 = 1 day
    }
    return true;
    die();
}

add_action("wp_ajax_websima_wc_wishlist_remove", "websima_wc_wishlist_remove");
add_action("wp_ajax_nopriv_websima_wc_wishlist_remove", "websima_wc_wishlist_remove");
function websima_wc_wishlist_remove()
{
    $pid = $_POST['pid'];
    $uid = get_current_user_id();
    if (is_user_logged_in()) {
        $wish_user = get_user_meta($uid, WB_WISHLIST_NAME, true);
        if ($wish_user) {
            $wish_user_array = array_diff(unserialize($wish_user), array($pid));
            update_user_meta($uid, WB_WISHLIST_NAME, serialize($wish_user_array));
        }
    } else {
        $cookie_name = WB_WISHLIST_NAME;
        $cookie_value = explode("+", $_COOKIE[$cookie_name]);
        $cookie_value_new = array_diff($cookie_value, array($pid));
        $value_convert = implode("+", array_unique($cookie_value_new));
        setcookie($cookie_name, $value_convert, time() + (86400 * 30), "/"); // 86400 = 1 day
    }
    return true;
    die();
}


function websima_wc_wishlist()
{
    $uid = get_current_user_id();
    if (is_user_logged_in()) {
        $wish_user = get_user_meta($uid, WB_WISHLIST_NAME, true);
        if ($wish_user) {
            $id_products = unserialize($wish_user);
        }
    } else {
        $cookie_name = WB_WISHLIST_NAME;
        if (isset($_COOKIE[$cookie_name])) {
            $id_products = explode("+", $_COOKIE[$cookie_name]);
        }
    }
    if (isset($_GET['ids'])) {
        $id_products = explode(',', $_GET['ids']);
    }
    if (empty($id_products)) $id_products = array(0);
    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'post__in' => $id_products,
    );
    $my_posts = new WP_Query($args);
    if ($my_posts->have_posts()) :
        echo "<div class='wrap-wishlist'>";
        echo "<div class='row'>";
        while ($my_posts->have_posts()) : $my_posts->the_post();
            global $post;
            echo "<div class='col-lg-3 col-md-4 col-sm-6 mb-4'>";
            echo "<div class='wishlist-remove'><a class='wishlist-item wished' href='#' data-id='" . $post->ID . "'><i>×</i></a></div>";
            get_template_part('woocommerce/content', 'product');
            echo "</div>";
        endwhile;
        echo "</div>";
        echo "</div>";
    else :
        echo "<div class='woocommerce-error'>You do not have a product in the Favorites list.</div>";
    endif;
}


function websima_wc_wishlist_check_added($pid)
{
    $uid = get_current_user_id();
    if (is_user_logged_in()) {
        $wish_user = get_user_meta($uid, WB_WISHLIST_NAME, true);
        if ($wish_user) {
            $wish_user_array = unserialize($wish_user);
            if (in_array($pid, $wish_user_array)) {
                return true;
            }
        }
    } else {
        $cookie_name = WB_WISHLIST_NAME;
        if (isset($_COOKIE[$cookie_name])) {
            $cookie_value = explode("+", $_COOKIE[$cookie_name]);
            if (in_array($pid, $cookie_value)) {
                return true;
            }
        }
    }
    return false;
}


function websima_wc_wishlist_login($user_login, $user)
{
    $uid = $user->ID;
    $wish_user = get_user_meta($uid, WB_WISHLIST_NAME, true);
    if (!$wish_user) {
        $cookie_name = WB_WISHLIST_NAME;
        if (isset($_COOKIE[$cookie_name])) {
            $id_products = explode("+", $_COOKIE[$cookie_name]);
            update_user_meta($uid, WB_WISHLIST_NAME, serialize($id_products));
        }
    }
}
add_action('wp_login', 'websima_wc_wishlist_login', 10, 2);


function websima_wc_wishlist_btn($pid)
{
    echo '<a href="#" data-id="' . esc_attr($pid) . '" class="wishlist-link wishlist-item' . (websima_wc_wishlist_check_added($pid) ? ' wished' : '') . '"><i class="icon-like"></i></a>';
}


add_filter('woocommerce_account_menu_items', 'websima_wc_wishlist_account_menu_items', 40);
function websima_wc_wishlist_account_menu_items($menu_links)
{
    $menu_links = array_slice($menu_links, 0, 2, true)
        + array('wishlist' => 'Favorites list')
        + array_slice($menu_links, 2, NULL, true);

    return $menu_links;
}


add_filter('woocommerce_get_endpoint_url', 'websima_wc_wishlist_get_endpoint_url', 10, 4);
function websima_wc_wishlist_get_endpoint_url($url, $endpoint, $value, $permalink)
{
    if ($endpoint === 'wishlist') {
        $url = websima_wc_wishlist_find_page_id('includes/websima-wishlist/wishlist.php');
    }
    return $url;
}


add_filter('theme_page_templates', 'websima_wc_wishlist_add_templates', 10, 4);
function websima_wc_wishlist_add_templates($post_templates, $wp_theme, $post, $post_type)
{
    $post_templates['includes/websima-wishlist/wishlist.php'] = 'Favorites';

    return $post_templates;
}


function websima_wc_wishlist_find_page_id($redirect_slug)
{
    $template_page_property_comparison_array = get_pages(
        array(
            'meta_key' => '_wp_page_template',
            'meta_value' => $redirect_slug
        )
    );
    if ($template_page_property_comparison_array) {
        return get_the_permalink($template_page_property_comparison_array[0]->ID);
    } else {
        return site_url();
    }
}
