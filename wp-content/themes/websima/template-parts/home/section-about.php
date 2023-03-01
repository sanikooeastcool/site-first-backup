<?php

if(get_sub_field('image')){
    $cls="col-12 col-lg-7";
}else{
    $cls="col-12";
}
?>
<section class="section-about">
    <div class="container">
        <div class="row align-items-center">
            <div class="<?php echo $cls; ?> order-lg-1 order-2 slideInRight wow" data-wow-delay="0.3s">
                <div class="about-wrap">

                     <h2 class="title-heading"><i class="icon-token-title"></i><?php echo get_sub_field('title'); ?></h2>
                    <div class="editor-content main-content"><?php echo get_sub_field('desc'); ?></div>
                    <?php
                    $btns=get_sub_field('btns');
                    if($btns !=null){
                        foreach($btns as $btn):
                           if($btn['btn_url'] !=null){
                            echo'<a href="'.$btn['btn_url'].'" class="button " target="_blank">'.$btn['btn'].'</a>';
                            }
                        endforeach;
                    }
                    ?>
                </div>
            </div>
            <?php if(get_sub_field('image')){?>
                <div class="col-12 col-md-10 col-lg-5 mx-auto order-1 order-lg-2 slideInLeft wow" data-wow-delay="0.3s">
                    <div class="image-about">
                        <?php echo wp_get_attachment_image(get_sub_field('image'),'full'); ?>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
</section>