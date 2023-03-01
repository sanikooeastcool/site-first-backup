<?php
/* template name: Advance Shop */
get_header();

$cls = "col-12";
$show_sidebar = false;
if (get_field('show_sidebar_advance_shop', get_the_ID())) {
	if (is_active_sidebar('sidebar_shop')) {
		$show_sidebar = true;
		$cls = "col-12 col-md-8 col-lg-9";
	}
}
?>
<main id="main" class="site-main">
	<?php if ($show_sidebar) { ?>
		<div class="container">
			<div class="row">
				<span class="sidebar-btn">Filter</span>
				<div class="col-10 col-md-4 col-lg-3" id="sidebar">
					<div class="sidebar-close"><i class="icon-close"></i></div>
					<?php do_action('woocommerce_sidebar'); ?>
				</div>

				<div class="<?php echo $cls; ?>">
					<?php echo websima_layouts_page_builder(); ?>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<?php echo websima_layouts_page_builder(); ?>
	<?php } ?>
</main>
<?php get_footer(); ?>
<?php faq_schema($term = null); ?>