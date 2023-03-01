<?php
$count = 3;
$blog_switch = true;
$product_switch = true;
$image = true;
$product_cat = false;
$blog_cat = false;
$enable_sku = true;

if (!function_exists('websima_search_form')) {
    function websima_search_form(){	
		global $blog_switch,$product_switch;	
		if($blog_switch and $product_switch){
			$placeholder = 'محصول یا مقاله مورد نظر خود را جستجو کنید';
		}elseif($blog_switch){
			$placeholder = 'مقاله مورد نظر خود را جستجو کنید';
		}elseif($product_switch){
			$placeholder = 'محصول مورد نظر خود را جستجو کنید';
		}else{
			$placeholder = 'عبارت مورد نظر خود را جستجو کنید';
		}
		echo '<form action="'.home_url().'" class="search-form" method="get">';
            echo '<input id="search-text" type="search" name="s" placeholder="'.esc_attr($placeholder).'" autocomplete="off">';
            echo '<input type="hidden" name="post_type" value="product">';
			echo '<div class="icons-wrapper">';
				echo '<div class="wrap">';
					echo '<i class="icon-close search-remove"></i>';
					echo '<i class="icon-cart search-loading"></i>';
				echo '</div>';
			echo '</div>';
        echo '</form>';
        echo '<div class="search-results-box"></div>';		
	}
}

if (!function_exists('get_taxonomy_by_search')) {
    function get_taxonomy_by_search($keyword, $tax){
        global $wpdb;
        $result = $wpdb->get_results(
            $wpdb->prepare("
            SELECT $wpdb->terms.term_id,$wpdb->terms.name,$wpdb->term_taxonomy.taxonomy
            FROM $wpdb->terms 
            LEFT JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
            WHERE name LIKE %s AND $wpdb->term_taxonomy.taxonomy = '$tax'
            ", "%$keyword%")
        );
        return $result;
    }
}

if (!function_exists('html_result_item_search')) {
    function html_result_item_search(){
        ob_start();
        global $post,$image,$enable_sku;
        ?>
        <div class="search-box">
            <div class="search-detail">
                <?php if ($image) { ?>
                    <a href="<?php echo get_permalink(); ?>" class="search-image">
                        <?php the_post_thumbnail('thumbnail'); ?>
                    </a>
                <?php } ?>
                <h3>
                    <a href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title(); ?>">
                        <?php echo get_the_title(); ?>
                        <?php
                        if ($enable_sku) {
                            $sku = get_post_meta($post->ID, '_sku', true);
                            if ($sku != '') echo "<span> شناسه : $sku</span>";
                        }
                        ?>
                    </a>
                </h3>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        echo $html;
    }
}

add_action('init', function () {
    add_action("wp_ajax_results_search", "results_search");
    add_action("wp_ajax_nopriv_results_search", "results_search");
    function results_search(){
        global $count,$product_switch,$blog_switch,$product_cat,$blog_cat,$enable_sku;
        if (!empty($_POST["subject"])) {
            echo '<div class="row">';
            $search_query = sanitize_text_field($_POST['keyword']);
            $search_query = str_replace('ي', 'ی', $search_query);
            $search_query = str_replace('ك', 'ک', $search_query);
            //$search_query = str_replace('آ','ا',$search_query);
            if ($product_switch) {
                $the_query = new WP_Query(array('posts_per_page' => $count, 's' => esc_attr($search_query), 'post_type' => 'product'));
                echo '<div class="col-12 col-md"><div class="search-blogbox"><span class="search-title">محصولات</span>';
                if ($the_query->have_posts()) :
                    while ($the_query->have_posts()): $the_query->the_post();
                        html_result_item_search();
                    endwhile;
                    wp_reset_postdata(); ?>
                    <div class="text-center mt-4">
                        <a class="button"
                           href="<?php echo get_bloginfo('url'); ?>/?s=<?php echo esc_attr($search_query); ?>&post_type=product">نمایش
                            نتایج بیشتر <i class="icon-left"></i></a>
                    </div>
                <?php else :
                    if ($enable_sku) {
                        $the_query = new WP_Query(array(
                                'posts_per_page' => -1,
                                'post_type' => 'product',
                                'meta_query' => array(
                                    array(
                                        'key' => '_sku',
                                        'value' => esc_attr($search_query),
                                        'compare' => '=',
                                    )
                                )
                            )
                        );
                        if ($the_query->have_posts()) :
                            while ($the_query->have_posts()): $the_query->the_post();
                                html_result_item_search();
                            endwhile;
                            wp_reset_postdata();
                        else:
                            echo '<span class="noresults">متاسفانه نتیجه ای با این عبارت یافت نشد.</span>';
                        endif;
                    } else {
                        echo '<span class="noresults">متاسفانه نتیجه ای با این عبارت یافت نشد.</span>';
                    }
                endif;
                echo '</div>';
                if ($product_cat) {
                    $term_result = get_taxonomy_by_search($search_query, 'product_cat');
                    if ($term_result != null) { ?>
                        <span class="search-title">دسته بندی محصولات</span>
                        <div class="search-terms">
                            <?php foreach ($term_result as $value) {
                                $term_data = get_term($value->term_id);
                                echo "<a href='" . get_term_link($term_data->term_id) . "'>" . $value->name . "</a>";
                            } ?>
                        </div>
                        <?php
                    }
                }
                echo "</div>";
            }
            if ($blog_switch) {
                $the_query = new WP_Query(array('posts_per_page' => $count, 's' => esc_attr($search_query), 'post_type' => 'post'));
                echo '<div class="col-12 col-md"><div class="search-blogbox"><span class="search-title">مقالات</span>';
                if ($the_query->have_posts()) :
                    while ($the_query->have_posts()): $the_query->the_post();
                        html_result_item_search();
                    endwhile;
                    wp_reset_postdata(); ?>
                    <div class="text-center mt-4">
                        <a class="button" href="<?php echo get_bloginfo('url'); ?>/?s=<?php echo esc_attr($search_query); ?>">
                            نمایش نتایج بیشتر
                            <i class="icon-left"></i></a>
                    </div>
                <?php
                else :
                    echo '<span class="noresults">متاسفانه نتیجه ای با این عبارت یافت نشد.</span>';

                endif;
                echo '</div>';
                if ($blog_cat) {
                    $term_result = get_taxonomy_by_search($search_query, 'category');
                    if ($term_result != null) { ?>
                        <span class="search-title">دسته بندی مقالات</span>
                        <div class="search-terms">
                            <?php foreach ($term_result as $value) {
                                $term_data = get_term($value->term_id);
                                echo "<a href='" . get_term_link($term_data->term_id) . "'>" . $value->name . "</a>";
                            } ?>
                        </div>
                        <?php
                    }
                }
                echo '</div>';
            }
            echo '</div>';
            die();
        }
    }
}, 99);