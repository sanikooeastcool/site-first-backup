<?php
/*
 * Template name: Compare
 */
get_header();
?>





<?php
$add_new_image = get_option('compare_add_new_image');
if ($add_new_image == '') {
    $add_new_image = get_template_directory_uri() . '/includes/websima-compare/assets/images/add-new.jpg';
}
echo '<div class="compare-page">';
echo '<div class="container">';
echo  '<div class="title-part inner-title title-site-center">';
echo '<i class="icon-token-title"></i>';
echo  '<h1 class="title-heading">' . get_the_title() . '</h1>';
echo '</div>';
echo '<div class="row">';
echo '<div class="col-12">';
echo '<div class="compare-box-wrapper" data-href="' . get_the_permalink() . '">';
echo '<div class="compare-box-header">';
echo '<div class="compare-share">';
echo '<div class="row align-items-center">';
echo '<div class="col-12 col-md-9">';
echo '<span class="share-title">share</span>';
echo '<small class="share-sub-title">You can share your comparison list with your friends through social networks</small>';
echo '</div>';
echo '<div class="col-12 col-md-3 compare-shares-wrap">';
echo websima_shares();
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';

$compare_list = $_GET['compare_list'];
$compare_list = explode(',', $compare_list);

$compare_list_count = count($compare_list);
if ($compare_list_count < 2) {
    echo '<div class="compare-empty"><i class="icon"></i>You must select more than one product to view the comparison list.</div>';
} else {


    /*$terms = array();
                        foreach($compare_list as $pid){
                            $product_cat_array = get_the_terms(esc_attr($pid),'product_cat');
                            foreach($product_cat_array as $product_cat){
                                $terms[esc_attr($product_cat->term_id)][] = esc_attr($pid);
                            }
                        }

                        foreach($terms as $term_key => $term_value){
                            if(count($term_value) < 2){
                                unset($terms[esc_attr($term_key)]);
                            }
                        }

                        if(!empty($terms)){
                            if(count($terms) >= 2) {
                                echo '<ul class="compare-tab-list">';
                                    foreach($terms as $term_key => $term_value){
                                        $term_details = get_term_by('id',esc_attr($term_key),'product_cat');
                                        echo '<li class="tab-item active"><a href="#tab_'.esc_attr($term_key).'">'.esc_html($term_details->name).'</a></li>';
                                    }
                                echo '</ul>';
                            }
                        }*/

    echo '<div class="compare-box-main">';
    $args = array();
    $args['post_type'] = 'product';
    $args['post__in'] = $compare_list;
    $args['posts_per_page'] = -1;

    $the_query = new WP_Query($args);
    global $product;
    $i = 0;
    if ($the_query->have_posts()) {
        echo '<div class="compare-box">';
        echo '<div class="owl-carousel-compare owl-carousel owl-theme owl-rtl" data-count="' . esc_attr($compare_list_count) . '">';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            if ($i <= 5) {
                echo '<div class="compare-item" data-id="' . get_the_id() . '">';
                echo '<div class="compare-summary">';

                echo '<div class="compare-image">';
                woocommerce_template_loop_product_thumbnail();
                echo '</div>';

                echo '<h2 class="compare-title"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h2>';

                $price = $product->get_price_html();
                if ($price != '') {
                    $final_price = $price;
                } else {
                    $final_price = '-';
                }
                if ($product->is_in_stock()) {
                    $final_stock =  '<span class="in-stock">available</span>';
                } elseif (!$product->is_in_stock()) {
                    $final_stock = '<span class="out-of-stock">unavailable</span>';
                }
                echo '<ul class="list">';
                echo '<li class="price">' . $final_price . '</li>';
                echo '<li class="stock">' . $final_stock . '</li>';
                echo '</ul>';

                echo '<div class="compare-actions">';
                echo '<div class="compare-similar-wrap">';
                echo '<div class="compare-similar">';
                echo '<input id="similar' . $i . '" value="None" type="checkbox" name="checkbox_similar" class="checkbox_similar" />';
                echo '<label for="similar' . $i . '"></label>';
                echo '</div>';
                echo '<span class="title">Show similar details</span>';
                echo '</div>';

                echo '<div class="compare-remove" data-id="' . get_the_id() . '">';
                echo '<i class="icon"></i>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

                echo '<div class="compare-list">';
                $attributes = wc_get_attribute_taxonomies();
                $args = array('posts_per_page' => -1, 'post_type' => 'attribute_group', 'post_status' => 'publish', 'orderby' => 'menu_order', 'suppress_filters' => 0);
                $attribute_groups = get_posts($args);

                foreach ($attribute_groups as $attribute_group) {
                    $attributes_in_group = get_post_meta(esc_attr($attribute_group->ID), 'woocommerce_group_attributes_attributes');
                    if (!empty($attributes_in_group)) {
                        foreach ($attributes_in_group as $attribute_in_group) {
                            foreach ($attributes as $tax) {
                                if (in_array($tax->attribute_id, $attribute_in_group)) {

                                    $label = !empty($tax->attribute_label) ? $tax->attribute_label : $tax->attribute_name;

                                    $wc_product_attributes[$name] = $tax;

                                    register_taxonomy(
                                        $name,
                                        apply_filters('woocommerce_taxonomy_objects_' . $name, array('product')),
                                        apply_filters('woocommerce_taxonomy_args_' . $name, array(/* … sane defaults … */))
                                    );

                                    echo '<div class="title-box" data-id="' . esc_attr($tax->attribute_id) . '" data-group-id="' . esc_attr($attribute_group->ID) . '"></div>';

                                    $garanvalues = get_the_terms($product->get_id(), 'pa_' . esc_attr($tax->attribute_name));

                                    if ($garanvalues) {
                                        echo '<div class="value-box" data-parentid="parent_' . esc_attr($tax->attribute_id) . '">';
                                        $garanvalues_counter = 1;
                                        $garanvalues_count = count($garanvalues);
                                        foreach ($garanvalues as $garanvalue) {
                                            if ($garanvalues_counter < $garanvalues_count) {
                                                $comma = ',';
                                            } else {
                                                $comma = '';
                                            }

                                            if ($garanvalue->name == 'true') {
                                                $garanvalue_v = '<i class="icon-status check"></i><em class="d-none">' . esc_html($garanvalue->name) . '</em>';
                                            } else if ($garanvalue->name == 'false') {
                                                $garanvalue_v = '<i class="icon-status uncheck"></i><em class="d-none">' . esc_html($garanvalue->name) . '</em>';
                                            } else {
                                                $garanvalue_v = esc_html($garanvalue->name);
                                            }
                                            echo '<div class="value">' . $garanvalue_v . '<span class="comma">' . $comma . '</span></div>';
                                            $garanvalues_counter++;
                                        }
                                        echo '</div>';
                                    } else {
                                        echo '<div class="value-box-empty" data-parentid="parent_' . esc_attr($tax->attribute_id) . '" data-group-id="' . esc_attr($attribute_group->ID) . '"></div>';
                                    }
                                }
                            }
                        }
                    }
                }
                echo '</div>';
                echo '</div>';
            }
            $i++;
        }

        echo '<div class="compare-item">';
        echo '<div class="compare-summary">';
        echo '<div class="compare-image">';
        echo '<img src="' . esc_url($add_new_image) . '"  alt="add new" />';
        echo '</div>';

        echo '<h2 class="compare-title"><a>Add a new product</a></h2>';

        echo '<div class="compare-back-btn">';
        echo '<a href="' . get_permalink(wc_get_page_id('shop')) . '">';
        echo 'return to shop';
        echo '</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '</div>';
        echo '<div class="compare-back-top"><span class="icon-wrapper"><i class="icon"></i></span></div>';
        echo '</div>';



        echo '<div class="compare-list-titles">';
        $attributes = wc_get_attribute_taxonomies();
        $args = array('posts_per_page' => -1, 'post_type' => 'attribute_group', 'post_status' => 'publish', 'orderby' => 'menu_order', 'suppress_filters' => 0);
        $attribute_groups = get_posts($args);

        foreach ($attribute_groups as $attribute_group) {
            $attribute_group_name = $attribute_group->post_title;
            echo '<div class="title-box title-box-group" data-id="' . esc_attr($attribute_group->ID) . '">' . esc_html($attribute_group_name) . '</div>';

            $attributes_in_group = get_post_meta(esc_attr($attribute_group->ID), 'woocommerce_group_attributes_attributes');
            if (!empty($attributes_in_group)) {
                ksort($attributes_in_group);
                foreach ($attributes_in_group as $attribute_in_group) {
                    $i = 0;
                    foreach ($attributes as $tax) {

                        if (in_array($tax->attribute_id, $attribute_in_group)) {
                            $i++;
                            $label = !empty($tax->attribute_label) ? $tax->attribute_label : $tax->attribute_name;

                            $wc_product_attributes[$name] = $tax; // used as a global elsewhere

                            register_taxonomy(
                                $name,
                                apply_filters('woocommerce_taxonomy_objects_' . $name, array('product')),
                                apply_filters('woocommerce_taxonomy_args_' . $name, array())
                            );

                            echo '<div class="title-box" data-id="' . esc_attr($tax->attribute_id) . '" data-group-id="' . esc_attr($attribute_group->ID) . '">' . esc_html($tax->attribute_label) . '</div>';
                        }
                    }
                }
            }
        }
        echo '</div>';
    }
    wp_reset_postdata();
    echo '</div>';
}

echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
?>





<?php
get_footer();
