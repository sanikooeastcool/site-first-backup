<section class="section-blog section-base">
    <div class="container">
        <span class="token"></span>
        <div class="title-site title-site-center fadeInDown wow" data-wow-delay="0.15s">
            <i class="icon-hover-menu"></i>
            <?php if(get_sub_field('en_title')){?> <h4 class="en-title-heading"><?php echo get_sub_field('en_title'); ?></h4> <?php } ?>
            <h4 class="title-heading"><?php echo get_sub_field('title'); ?></h4>
        </div>
        <div class="blog-carousel owl-carousel fadeInDown wow" data-wow-delay="0.3s">
            <?php
            $args      = array(
                'post_type'      => 'post',
                'posts_per_page' => get_sub_field('count'),
                'order'          => 'DESC',
                'post_status'    => 'publish',
            );
            if (get_sub_field('type') == 'custom'){
                $args['post__in'] = get_sub_field('ids');
            }
			if (get_sub_field('type') == 'cat'){
                $args['cat'] = get_sub_field('cat_id');
            }
            $the_query = new WP_Query( $args ); ?>
            <?php if ( $the_query->have_posts() ) : ?>
                <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                    <?php get_template_part( 'template-parts/cards/card', 'post' ); ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>
        </div>
        <div class="btn-wrap fadeInDown wow" data-wow-delay="0.45s"> <?php if (get_sub_field('btn_url') != '') echo "<a class='button button1' href=".get_sub_field('btn_url')." target='_blank'>".get_sub_field('btn')."</a>"; ?></div>

    </div>
</section>
