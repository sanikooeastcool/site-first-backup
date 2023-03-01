<?php
get_header();
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );

$search_query = get_search_query();
?>
<main id="main" class="site-main archive-blog">
    <div class="container">
        <div class="title-part inner-title contact-title">

            <?php
            $page_title = " جستجو برای : ". get_search_query();
            ?>
            <h1 class="title-heading"><i class="icon-Rectangle"></i><?php echo $page_title;?></h1>
        </div>
        <div class="row">
            <?php
            $search_query = str_replace('ي','ی',$search_query);
            $search_query = str_replace('ك','ک',$search_query);
            $search_query = str_replace('آ','ا',$search_query);

            $args = array(
                'post_type' => 'post',
                'order' => 'DESC',
                'paged' => $paged,
                's'=> $search_query,
                'posts_per_page'=>'8',
                'post_status' => 'publish',
            );
            $posts = new WP_Query( $args );
            if( $posts->have_posts() ):
                while ( $posts->have_posts() ) :
                    $posts->the_post();?>
                    <div class="col-6 col-md-4 mb-4">
                        <?php get_template_part('template-parts/cards/card', 'post');?>
                    </div>
                <?php endwhile;
            else:
                echo "<div class='woocommerce-error'>متاسفانه نتیجه ای یافت نشد.</div>";
            endif;
            ?>
        </div>

</main>
<?php get_footer(); ?>
