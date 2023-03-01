<?php
/* template name: Advance Blog */
get_header();
$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
$cls = "col-12";
$show_sidebar=false;
if(get_field('show_sidebar_blog','option')){
	if(is_active_sidebar( 'sidebar_blog' )){
		$show_sidebar=true;
		$cls="col-12 col-md-8 col-lg-9";
	}	
}
?>
<main id="main" class="site-main">
	<div class="container">	
	    <div class="title-part inner-title">
		    <h1 class="title-heading mb-3"><?php echo get_the_title();?></h1>
			<?php /* page-description */?>
	        <div class="editor-content main-content"><?php the_content();?></div>
		</div>
        <div class="row">
		    <?php if($show_sidebar){?>
			    <span class="sidebar-btn">فیلتر</span>
				<div class="col-10 col-md-4 col-lg-3" id="sidebar">
				    <div class="sidebar-close"><i class="icon-close"></i></div>
					<?php get_sidebar(); ?>
				</div>
			<?php } ?>
			
			<div class="<?php echo $cls;?>">
			    <?php echo websima_layouts_page_builder(); ?>
			</div>  
		</div>         
	</div>
</main>
<?php get_footer(); ?>
<?php faq_schema($term=null);?>
