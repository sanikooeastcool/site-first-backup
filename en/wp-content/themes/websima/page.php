<?php
get_header();
?>
<main id="main" class="site-main ">
	<div class="container">
	    <article>
			<div class="title-part inner-title title-site-center">
                <i class="icon-token-title"></i>
                <h1 class="title-heading"><?php echo get_the_title(); ?></h1>
			</div>
			<?php if (has_post_thumbnail()) { ?>		
			<div class="thumbnail-img">		
				<?php the_post_thumbnail(); ?>
			</div>
			<?php } ?> 
			<div class="editor-content"><?php the_content();?></div>
		</article>
	</div>
</main>
<?php get_footer(); ?>
