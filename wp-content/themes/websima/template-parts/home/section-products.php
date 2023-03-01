<?php
$bg = get_sub_field('image');
 ?>
<section class="section-products-home section-base fadeInDown wow" data-wow-delay="0.15s" <?php if($bg){?>style="background-image:url(<?php echo wp_get_attachment_image_url($bg,'full');?>)"<?php } ?>>
    <div class="container">
        <div class="title-site title-site-center fadeInDown wow" data-wow-delay="0.3s">
            <i class="icon-hover-menu"></i>
            <?php if(get_sub_field('en_title')){?> <h4 class="en-title-heading"><?php echo get_sub_field('en_title'); ?></h4> <?php } ?>
            <h4 class="title-heading"><?php echo get_sub_field('title'); ?></h4>
            <?php if (get_sub_field('subtitle') != '') echo "<p>".get_sub_field('subtitle')."</p>"; ?>
        </div>
        <?php if (get_sub_field('what') == 'recent_products'){
            echo websima_list_products('recent_products',get_sub_field('count'));
        }elseif (get_sub_field('what') == 'best_selling_products'){
            echo websima_list_products('best_selling_products',get_sub_field('count'));
        }elseif (get_sub_field('what') == 'cat_products'){
            echo websima_list_products('products',get_sub_field('count'),null,get_sub_field('id_cat'));
        }elseif (get_sub_field('what') == 'custom_products'){
            echo websima_list_products('products',get_sub_field('count'),get_sub_field('ids'));
        } ?>
    </div>

    <?php if (get_sub_field('btn_url') != '') echo "<div class='btn-wrap fadeInDown wow' data-wow-delay='0.45s'><a class='button button1' href=".get_sub_field('btn_url')." target='_blank'>".get_sub_field('btn')."</a></div>"; ?>
</section>

