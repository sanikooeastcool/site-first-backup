<?php 

$desc = get_sub_field('desc');
?>
<div class="section-cta fadeInDown wow" data-wow-delay="0.3s">
    <div class="container ">
	    <div class="row">
		    <div class="col-12">
			    <div class="cta-wrap">
					<div class="title-site title-mb-3">
						<span class="en-title"><?php echo get_sub_field('en_title'); ?></span>
						<h4 class="title-heading"><?php echo get_sub_field('title'); ?></h4>
					</div>
				    <div class="cta-desc-wrap"><?php if($desc)echo '<p>'.$desc.'<p>';?></div>
				    <div class="cta-btn-wrap">
						<?php 
						$btns=get_sub_field('access');
						if($btns !=null){
							foreach($btns as $btn):

							 echo'<a href="'.$btn['link'].'" class="cta-button" target="_blank">'.wp_get_attachment_image($btn['icon'] , 'full').'<span> '.$btn['title'].'</span></a>';
							endforeach;
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>