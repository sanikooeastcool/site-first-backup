<?php
/* template name: contact*/
acf_form_head(); 
get_header();
$page_id = websima_find_page_id('templates/template-contact.php');
$show_cform=get_field('show_cform',$page_id);
?>
<main id="main" class="site-main">
	<div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
		<div class="title-part title-site-center inner-title">
            <i class="icon-token-title"></i>
			<h1 class="title-heading"><?php echo get_the_title();?></h1>
			<?php
			if (have_posts()) : 
				while (have_posts()) : the_post(); 
					if(get_the_content()):?>
				   <div class="editor-content main-content"><?php the_content();?></div>
				<?php
					endif;
				endwhile;
			 endif;
			?>
		</div>
            </div>
        </div>
		<?php   
			$map_type = get_field('map_type');
			websima_custom_map($map_type);
		?>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
		<?php if($show_cform): ?>
		<div class="websima-contact-form">
		    <div class="title-part title-site-center inner-title">
                <i class="icon-token-title"></i>
				<?php if(get_field('cform_title', 'option') != '') echo'<h4 class="title-heading">'.get_field('cform_title', 'option').'</h4>';?>
				<?php if(get_field('cform_desc', 'option') != '') echo'<div class="editor-content main-content">'.get_field('cform_desc', 'option').'</div>';?>
			</div>
            <div class="editor-content main-content">
                <?php
                echo get_field('form');
                ?>
            </div>
			 
		</div>
		<?php endif;?>
                </div>
            </div>
	</div>	
</main>	
<?php get_footer(); ?>