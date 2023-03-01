<?php
get_header();
$ids_products = get_field('related_products');
$ids_posts = get_field('related_posts');
$show_related_posts = get_field('show_related_posts');
$show_related_products = get_field('show_related_products');
$post_layout = get_field('post_layout');
if ($post_layout == '') $post_layout = 'one-col';
?>
    <main id="main" class="site-main">
        <div class="container single-wrap">
            <div class="row">
                <?php
                if (!wp_is_mobile()) {
                    get_template_part('template-parts/single/templates/summary-' . $post_layout);
                } else {
                    get_template_part('template-parts/single/templates/summary-one-col');
                }
                ?>
            </div>
        </div>
        <?php if ($show_related_posts):
                ?>
                <div class="section-related-posts" id="news-related">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="title-part inner-title title-site-center">
                                    <i class="icon-hover-menu"></i>
                                    <h4 class="title-heading">مقالات مرتبط</h4>
                                </div>
                                <div class="blog-carousel owl-carousel">
                                    <?php
                                    $args = array(
                                        'post_type' => 'post',
                                        'posts_per_page' => 6,
                                        'order' => 'DESC',
                                        'post_status' => 'publish',
                                    );
                                    if ($ids_posts != null) {
                                        $args['post__in'] = $ids_posts;
                                    } else {
                                        $args['post__not_in'] = array(get_the_ID());
                                        $args['category__in'] = wp_get_post_categories(get_the_ID());
                                    }
                                    $the_query = new WP_Query($args);
                                    if ($the_query->have_posts()) :
                                        while ($the_query->have_posts()) : $the_query->the_post();
                                            get_template_part('template-parts/cards/card', 'post');
                                        endwhile;
                                        wp_reset_postdata();
                                    endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
        endif; ?>
        <?php
        if ($show_related_products):
            if ($ids_products != null) {
                ?>
                <div class="section-related-products" id="pro-related">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="title-part inner-title title-site-center">
                                    <i class="icon-hover-menu"></i>
                                    <h4 class="title-heading">محصولات مرتبط</h4>
                                </div>
                                <?php echo websima_list_products('products', '4', $ids_products); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
        endif; ?>
        <?php if (comments_open()): ?>
            <div class="section-comment" id="comments-single">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-8 mx-auto">
                            <div class="title-part inner-title title-site-center">
                                <i class="icon-token-title"></i>
                                <h4 class="title-heading">نظرات کاربران</h4>
                            </div>
                            <div class="editor-content">
                                <div class="comment-wrapper">
                                    <?php comments_template(); ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
<?php get_footer(); ?>
<?php faq_schema($term = null); ?>