<?php
/* template name: FAQ */
get_header();
?>
<main id="main" class="site-main">
    <div class="container">  
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="title-part inner-title title-site-center">
                    <i class="icon-token-title"></i>
                    <h1 class="title-heading"><?php echo the_title(); ?></h1>
					<?php 
					if (have_posts()) : 
			        	while (have_posts()) : the_post(); 
						    if(get_the_content()):?>
                           <div class="editor-content"><?php the_content();?></div>
						<?php
						    endif;
						endwhile;
					 endif;
					?>
                </div>  
				<?php 
					/* if your FAQ has category use this */
					websima_faqs_cat();
		         
				?>
                <?php if(get_field('show_faqs')){?>
                <div class="title-part inner-title title-site-center frequent-title">
                    <i class="icon-token-title"></i>
                    <h1 class="title-heading">Common Faqs</h1>
                </div>
                <?php
                /* if your FAQ without category use this */
                websima_faqs();
                ?>
                <?php } ?>
			</div>	 
        </div>
    </div>
</main>
<?php get_footer(); ?>
<?php faqcat_schema();?>
<?php faq_schema();?>

