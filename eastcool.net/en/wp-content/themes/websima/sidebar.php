<?php  
if ( (is_active_sidebar( 'sidebar_blog' )) != '' ) : ?>
<div class="sidebar sidebar-blog" >
	<?php dynamic_sidebar( 'sidebar_blog' ); ?>
</div>
<?php endif;?>
