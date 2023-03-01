<?php
if(get_sub_field('pos_image')=='l'){
    $classes1="order-lg-2";
    $classes2="order-lg-1";
}else {
    $classes1="order-lg-1";
    $classes2="order-lg-2";
}

if(get_sub_field('image')){
    $cls="col-12 col-lg-7";
}else{
    $cls="col-12";
}

?>
<section class="section-content section-base">
    <div class="container">
        <div class="row align-items-center">
            <div class="<?php echo $cls .' '.$classes2; ?> order-2 slideInRight wow" data-wow-delay="0.5s">
				<div class="about-content-wrap">
				    <div class="about-title">
                        <?php if(get_sub_field('en_title') !== '') { ?> <h3 class="en-title-heading"><?php echo get_sub_field('en_title'); ?></h3> <?php } ?>
                        <h2 class="title-heading"><?php echo get_sub_field('title'); ?></h2>
					</div>
					<div class="editor-content main-content"><?php echo get_sub_field('desc'); ?></div>
					<?php 
					$btns=get_sub_field('btns');
					if($btns !=null){
						foreach($btns as $btn):
                    if($btn['btn_url'] !=null){
						 $cls_btn = ($btn['btn_design2']) ? 'button1' : ' ';
						 echo'<a href="'.$btn['btn_url'].'" class="button '.$cls_btn.'" target="_blank">'.$btn['btn'].'</a>';
                    }
						endforeach;
					}
					?>
				</div>
            </div>
			<?php if(get_sub_field('image')){?>
            <div class="col-12 col-md-10 col-lg-5 mx-auto order-1 <?php echo $classes1; ?> slideInLeft wow" data-wow-delay="0.5s">
                <div class="image-about">
                    <?php echo wp_get_attachment_image(get_sub_field('image'),'full'); ?>
                </div>
            </div>
			<?php }?>
        </div>
    </div>
</section>