<section class="section-products-offer section-base">
    <div class="container">
        <div class="title-site title-site-center fadeInDown wow">
            <i class="icon-hover-menu"></i>
            <?php if(get_sub_field('en_title')){?> <h4 class="en-title-heading"><?php echo get_sub_field('en_title'); ?></h4> <?php } ?>
            <h4 class="title-heading"><?php echo get_sub_field('title'); ?></h4>
        </div>
        <div class="product-carousel owl-carousel fadeInDown wow" data-wow-delay="0.3s">
            <?php
            $timer = true;
            if (get_sub_field('ids') != null) {
                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'posts_per_page' => get_sub_field('count'),
                    'post__in' => get_sub_field('ids'),
                );
                $my_posts = new WP_Query($args);
                if ($my_posts->have_posts()) :
                    while ($my_posts->have_posts()) : $my_posts->the_post();
                        ?>
                        <?php include(locate_template('woocommerce/content-sale-product.php', false, false)); ?>
                    <?php
                    endwhile;
                    wp_reset_query();
                endif;
            }
            ?>
        </div>
        <?php if (get_sub_field('btn_url') != '') echo "<div class='btn-wrap fadeInDown wow' data-wow-delay='0.75s'><a class='button button1' href=".get_sub_field('btn_url')." target='_blank'>".get_sub_field('btn')."</a></div>"; ?>
    </div>
</section>