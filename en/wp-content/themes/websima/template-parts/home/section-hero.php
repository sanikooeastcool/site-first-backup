<?php
$sliders = get_sub_field('items');
?>
<section class="section-slider">

    <div class=" slider-site-wrap">
        <div class="slider-site owl-carousel" id="sync1">
            <?php if ($sliders != null) {
                foreach ($sliders as $slider) {
                    $img = $slider['image'];
                    if (wp_is_mobile()){
                        if ($slider['image_mobile'] != ''){
                            $img = $slider['image_mobile'];
                        }
                    }
                    ?>
                    <div class="slides">
                        <a href="<?php echo $slider['url'] ?>">
                            <?php echo wp_get_attachment_image($img, 'full'); ?>
                        </a>

                        <?php if($slider['title'] || $slider['desc']){ ?>
                            <div class="banner-content">
                                <h3><?php echo $slider['title']; ?></h3>
                                <p><?php echo $slider['desc']; ?></p>
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                }
            } ?>
        </div>

    </div>
    <div class="container">
        <div class="row justify-content-end">
            <div class="col-lg-7 thumbnail-wrap">
                <div class="thumbnail-slider owl-carousel" id="sync2">
                    <?php if ($sliders != null) {
                        foreach ($sliders as $slider) {
                            $img = $slider['image'];
                            if (wp_is_mobile()){
                                if ($slider['image_mobile'] != ''){
                                    $img = $slider['image_mobile'];
                                }
                            }
                            ?>
                            <div class="item-slider">
                                <a href="<?php echo $slider['url'] ?>">
                                    <?php echo wp_get_attachment_image($img, 'img_slider'); ?>
                                </a>
                            </div>
                            <?php
                        }
                    } ?>
                </div>
            </div>
        </div>
    </div>
</section>