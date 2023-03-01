<div class="col-lg-10 mx-auto">
    <article <?php post_class('row single-top'); ?> id="post-<?php the_ID(); ?>">
        <div class="col-lg-6 pl-lg-4">
            <?php get_template_part('template-parts/single/meta'); ?>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <?php get_template_part('template-parts/single/thumbnail'); ?>
        </div>
    </article>
    <?php get_template_part('template-parts/single/help'); ?>
    <?php get_template_part('template-parts/single/content'); ?>
    <?php get_template_part('template-parts/single/tags'); ?>
	<?php get_template_part('template-parts/single/faq'); ?>
</div>
