<?php
/*
 * Template name: wishlist
 */
get_header();
?>
    <main id="main" class="site-main">
        <div class="container">
            <div class="title-part inner-title title-site-center">
                <i class="icon-token-title"></i>
                <h1 class="title-heading"><?php echo the_title(); ?></h1>
            </div>
            <?php websima_wc_wishlist(); ?>
        </div>

    </main>
<?php get_footer();