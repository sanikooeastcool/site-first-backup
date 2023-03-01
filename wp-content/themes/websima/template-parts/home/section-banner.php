<?php
$items = get_sub_field('items');
$layout = get_sub_field('layout');
$remove = THEME_URL . '/includes/acf-image-select/img/';
$layout = str_replace(array($remove, '.png'), '', $layout);
include( locate_template( 'template-parts/banners-main.php', false, false ) );
?>
