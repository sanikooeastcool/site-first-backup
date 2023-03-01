<div class="col-md-8 col-lg-9 order-1 order-md-2 mx-auto">
    <article <?php post_class('single-top'); ?> id="post-<?php the_ID(); ?>">
        <?php get_template_part('template-parts/single/thumbnail'); ?>
        <?php get_template_part('template-parts/single/meta'); ?>
        <?php get_template_part('template-parts/single/content'); ?>
		<?php get_template_part('template-parts/single/faq'); ?>
        <?php get_template_part('template-parts/single/tags'); ?>
    </article>
</div>
<div class="col-md-4 col-lg-3 order-2 order-md-1">
    <div class="sidebar sidebar-post">
        <?php get_template_part('template-parts/single/help'); ?>
        <?php get_template_part('template-parts/single/access'); ?>
    </div>
</div>
