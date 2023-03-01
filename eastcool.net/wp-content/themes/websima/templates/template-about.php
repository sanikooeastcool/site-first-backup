<?php
/* template name: About */
get_header();
?>
<main id="main" class="site-main">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="title-part inner-title title-site-center">
                    <i class="icon-token-title"></i>
                    <h1 class="title-heading"><?php echo get_the_title(); ?></h1>
                    <div class="editor-content main-content"><?php echo get_field('short_desc') ?></div>
                </div>
            </div>
        </div>
    </div>
    <?php if (has_post_thumbnail()) { ?>
        <div class="about-thumbnail">
            <?php the_post_thumbnail(); ?>
        </div>
    <?php } ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <?php if (get_post()->post_content !== '') { ?>
                    <div class="editor-content main-content"><?php the_content(); ?></div>
                <?php } ?>
                <div class="section-awards">
                    <div class="title-part inner-title title-site-center">
                        <i class="icon-token-title"></i>
                        <h1 class="title-heading"><?php echo get_field('title'); ?></h1>
                        <div class="editor-content main-content"><?php echo get_field('desc') ?></div>
                    </div>
                    <?php
                    $images = get_field('gallery');
                    if( $images ): ?>
                        <div class="owl-carousel owl-gallery  page_lightgallery">
                            <?php foreach( $images as $image_id ): ?>
                                <div class="gallery_item"  data-src="<?php echo wp_get_attachment_image_src($image_id ,'full' )[0]; ?>">
                                    <a href="<?php echo wp_get_attachment_image_src($image_id ,'full' )[0]; ?>"><?php echo wp_get_attachment_image( $image_id, 'img_lightgallery' ); ?> </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>


