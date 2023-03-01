<?php
$bg = get_sub_field('image');
?>


<section class="section-categories section-base" <?php if($bg){?>style="background-image:url(<?php echo wp_get_attachment_image_url($bg,'full');?>)"<?php } ?>>
    <div class="container">
	    <div class="title-site title-site-center fadeInDown wow" data-wow-delay="0.25s">
            <i class="icon-hover-menu"></i>
            <?php if(get_sub_field('en_title')){?> <h4 class="en-title-heading"><?php echo get_sub_field('en_title'); ?></h4> <?php } ?>
            <?php if(get_sub_field('title')){?> <h4 class="title-heading"><?php echo get_sub_field('title'); ?></h4> <?php } ?>

		</div>
        <div class="categories-wrap row">
            <?php
            if (get_sub_field('terms') != null){
                $j=0.5;
                foreach (get_sub_field('terms') as $item) { ?>
                        <div class="col-6 col-md-6 col-lg-4 col-xl-3">
                            <div class="item-categories fadeInDown wow" data-wow-delay="<?php echo $j?>s">
							<?php if ($item['url'] != '') {?>
                                    <a class="link-hover" href="<?php echo $item['url']; ?>" target="_blank"></a>
                            <?php }?>
                                <div class="cat-details">
                                    <span class="fa-title"><?php echo $item['title']; ?></span>
                                    <span class="en-title"><?php echo $item['title_en']; ?></span>
                                </div>
                                <div class="cat-img-wrap"><?php echo wp_get_attachment_image($item['image'],'img_categories') ?></div>
                            </div>
                        </div>
                    <?php
                    $j=$j+0.25;
                }
            } ?>
        </div>

    </div>
</section>