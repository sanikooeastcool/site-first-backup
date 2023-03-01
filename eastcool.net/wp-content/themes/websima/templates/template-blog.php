<?php
/* template name: Simple Blog */
get_header();
$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
?>
<main id="main" class="site-main">
	<div class="container">
        <div class="row">
            <div class="col-lg-11 mx-auto">
                <div class="title-part inner-title title-site-center">
                    <i class="icon-token-title"></i>
                    <h1 class="title-heading"><?php echo get_the_title();?></h1>
                    <div class="editor-content main-content"><?php the_content();?></div>
                </div>
                <?php
                $categories = get_categories();
                if (!empty($categories)):
                    echo '<div class="items-wrap">';
                    echo'<div class="items-sub-category sub-category-carousel owl-carousel">';
                    foreach ($categories as $cat) :
                        echo'<a class="item-sub-category" target="_blank" href="'.get_category_link($cat->term_id).'">';
                        echo $cat->name;
                        echo'</a>';
                    endforeach;
                    echo'</div>';
                    echo'</div>';
                endif;
                ?>
            </div>

			<div class="col-12"">
			    <div class="row">
					<?php 
						$args = array(
								'post_type' => 'post',
								'paged' 	=> $paged,
								'post_status ' => 'publish',
							);
						$posts = new WP_Query($args);
						if ($posts->have_posts()):
							while ($posts->have_posts()) : $posts->the_post();
								echo'<div class="col-sm-6 col-md-6 col-lg-4">';
								get_template_part('template-parts/cards/card', 'post');
								echo'</div>';
							endwhile;
						endif;
						wp_reset_postdata();
					?>		   
				</div>
				<?php  websima_pagination($posts); ?>
        </div>
        <div class="col-lg-10 mx-auto">
            <?php
            if(get_field('more_desc')):
                echo'<div class="editor-content main-content" id="more-tax-desc">';
                echo get_field('more_desc');
                echo'</div>';
            endif;
            ?>
            <?php websima_faqs($term=null , $title = 'true' );?>
        </div>


		</div>         
	</div>
</main>
<?php get_footer(); ?>
<?php faq_schema($term=null);?>
