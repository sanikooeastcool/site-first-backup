<section class="section-faq">
    <div class="container">
		<div id="faqs">
		    <?php if(get_sub_field('faqs')):?>
                <div class="title-part inner-title title-site-center">
                    <i class="icon-token-title"></i>
                    <h4 class="title-heading"><?php echo get_sub_field('title');?></h4>
			</div>
			<?php endif;?>
			<?php if(get_sub_field('faqs')){?>
			<div class="container">
				<div class="row">
					<div class="col-12"><?php websima_faqs(); ?></div>
				</div>
			</div>
			<?php } ?>
		</div>
    </div>
</section>
<?php faq_schema($term = null);?>