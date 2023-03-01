<article class="post-item">
    <a href="<?php the_permalink(); ?>" class="link-hover">
    </a>
    <?php if (has_post_thumbnail()) {
        the_post_thumbnail('img_blog', array('loading' => 'lazy'));
    } else {
        if (class_exists('WooCommerce')) {
            echo wc_placeholder_img('img_blog');
        }
    } ?>
    <div class="blog-date">
        <?php $time = get_field('post_date'); ?>
        <span class="date"><?php echo $time; ?></span>
    </div>
    <div class="card-body">
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <a class="button button1 post-btn" href="<?php the_permalink(); ?>">Read More</a>
    </div>

</article>