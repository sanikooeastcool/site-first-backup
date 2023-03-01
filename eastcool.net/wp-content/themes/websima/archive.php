<?php
get_header();
$term = get_queried_object();


?>
<main id="main" class="site-main">
    <div class="container">
        <div class="row">
        <div class="col-lg-10 mx-auto">
        <div class="title-part inner-title title-site-center">
            <i class="icon-token-title"></i>
		<?php
    		the_archive_title( '<h1 class="title-heading">', '</h1>' );
            the_archive_description( '<div class="editor-content main-content">', '</div>' );
		?>
        </div>
		<?php
		
		if (is_category($term->term_id)):
			$categories = get_categories(
				array( 'parent' => $term->term_id )
			);
			if (!empty($categories)):		
				echo'<div class="items-sub-category">';
					foreach ($categories as $cat) :
						echo'<a class="item-sub-category" target="_blank" href="'.get_category_link($cat->term_id).'">';
							echo $cat->name; 
						echo'</a>';
					endforeach;
				echo'</div>';
			endif; 
		endif;
		?>
        </div>
			<div class="col-12">
			    <div class="row">
					<?php 
						if (have_posts()) :  
							while (have_posts()) : the_post();
								echo '<div class="col-sm-6 col-lg-4">';
										  get_template_part('template-parts/cards/card', 'post');
								echo '</div>';
							endwhile;
						else:
							echo '<div class="col-12"><div class="alert-error">متاسفانه مطلبی در این صفحه وجود ندارد!</div></div>'; 
						endif;
					?>
				</div>
				<?php websima_pagination(); ?>
            </div>
            <div class="col-lg-10 mx-auto">
				<?php 
					if(get_field('more_desc','category_'.$term->term_id)):
						echo'<div class="editor-content main-content" id="more-tax-desc">';
							echo get_field('more_desc',$term->taxonomy.'_'.$term->term_id);
						echo'</div>';
					endif;   
				?>  
				
				<?php 
				if(get_field('show_faqs',$term->taxonomy.'_'.$term->term_id)){		
				   websima_faqs($term , $title = 'true' );
				}
				?>
			</div>  
		</div>
    </div>
</main>
<?php get_footer(); ?>

<?php faq_schema($term);?>