<?php
$logo = get_field('logo', 'option');	
$phone_mbmenu = get_field('phone_mbmenu', 'option');
?>
<div id="mask"></div>
<div id="menumobile">
	<div class="title-mm">
        <a href="<?php echo home_url(); ?>" title="<?php echo bloginfo('name'); ?>">
            <?php echo wp_get_attachment_image($logo, 'full'); ?>
        </a>
		<span id="nomenumobile"><i class="icon-close"></i></span>
	</div>
	<?php if($phone_mbmenu):?>
    <div class="tel-mm">
	    <a href="<?php echo $phone_mbmenu; ?>"><?php echo $phone_mbmenu; ?></a>
    </div>
	<?php endif;?>
	<?php 
	    if ( has_nav_menu( 'mobile' ) ): 
			wp_nav_menu(array(
			'container'  => 'div',
			'container_class'  => 'main-mm', 
			'theme_location' => 'mobile'
			));
	   endif;
	?>
</div>