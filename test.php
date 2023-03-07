<?php
    /*
        Template Name: CustomName
    */
?>



<?php get_header(); ?>
	
	<?php get_sidebar(); ?>
	
	<section class="content">
		<?php if (have_posts()) : 
			while (have_posts()) : the_post(); ?>
			
			<a href="<?php the_permalink() ?>"><h1><?php the_title();?></h1></a>
			
			<?php the_content(); ?>
			
			
			<?php endwhile;
		endif; ?>
		
	</section>

<?php get_footer();?>