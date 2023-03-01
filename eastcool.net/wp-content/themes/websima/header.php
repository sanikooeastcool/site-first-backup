<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php
		if(is_page_template('templates/template-contact.php')){
			$attr='maximum-scale=1, user-scalable=0';
		}else{
			$attr='shrink-to-fit=no';
		}	
    ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, <?php echo $attr;?>">
	<?php wp_head(); ?>
	<?php
	$codes_head = get_field('codes_head','option');
	$codes_body_start = get_field('codes_body_start','option');
	if ($codes_head != '') echo PHP_EOL.$codes_head.PHP_EOL;
	?>
</head>
<body <?php body_class(); ?>>
<?php if ($codes_body_start != '') echo PHP_EOL.$codes_body_start.PHP_EOL; ?>
<?php wp_body_open(); ?>

<div id="wrapper">
<?php
	get_template_part('template-parts/headers/shop/header3');
	
 if (!is_front_page() && !is_404()) {
	if (function_exists('yoast_breadcrumb')) {
		yoast_breadcrumb('<div id="breadcrumbs"><div class="container"><div class="row"><div class="col-12">', '</div></div></div></div>');
	}
} 

