<?php
$ids_products = get_field('related_products');
$ids_posts = get_field('related_posts');
$show_related_posts = get_field('show_related_posts');
$show_related_products = get_field('show_related_products');
$faqs = get_field('faqs');
$show_faqs = get_field('show_faqs');
?>
<div class="access-post">
    <?php 
	if($show_faqs):
		if($faqs != null): ?>
			<a href="#faqs">Common Faqs</a>
	<?php endif;
	endif;
     ?>
    <?php if($show_related_posts){ ?>
        <a href="#news-related">Related Article</a>
    <?php } ?>
    <?php if($show_related_products){
        if($ids_products != null){ ?>
        <a href="#pro-related">Related Product</a>
    <?php }
    } ?>
	<?php if ( comments_open() ) {?>
    <a href="#comments-single">Users Reviews</a>
	<?php }?>
</div>
