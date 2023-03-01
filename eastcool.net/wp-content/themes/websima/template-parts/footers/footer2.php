<?php
$bg = get_field('image' , 'option');
?>
<footer id="footer" <?php if($bg){?>style="background-image:url(<?php echo wp_get_attachment_image_url($bg,'full');?>)"<?php } ?>>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="feature-footer">
                    <span class="token"></span>
                    <?php if (have_rows('extra_features', 'option')): ?>
                        <div class="features-wrap owl-carousel">
                            <?php $j=0; ?>
                            <?php while (have_rows('extra_features', 'option')) : the_row();

                                $sub_icon = get_sub_field('feature_icon');
                                $sub_label = get_sub_field('feature_label');
                                ?>
                                <div class="item fadeInDown wow" data-wow-delay="<?php echo $j?>s">
                                    <?php echo wp_get_attachment_image($sub_icon, 'thumbnail'); ?>
                                    <span> <?php echo $sub_label ?></span>
                                    <?php
                                    $j=$j+0.3;
                                    ?>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
        <section class="footer-main">
            <div class="container">
                <div class="row footer-wrapper">
                    <?php websima_newsletter_form(); ?>
                    <div class="col-lg-4 footer-about ">
                        <?php if ($logo_ft != '') { ?>
                            <a class="footer-logo" href="<?php echo home_url(); ?>" title="<?php echo bloginfo('name'); ?>">
                                <?php echo wp_get_attachment_image($logo_ft, 'full'); ?>
                            </a>
                        <?php } ?>

                        <?php if ($footer_desc != '') { ?>
                            <p><?php echo $footer_desc; ?></p>
                        <?php } ?>


                    </div>
                    <div class="col-sm-6 col-lg-5 footer-access">

                        <div class="wrap-ft-menu">
                            <?php
                            if ($footer_menu1 != '') echo "<h4 class='footer-title'>" . $footer_menu1 . "</h4>";
                            if (has_nav_menu('footer1')) {
                                wp_nav_menu(array(
                                    'theme_location' => 'footer1',
                                    'menu_class' => 'footer-sub-menu',

                                ));
                            }
                            ?>
                        </div>
                        <div class="wrap-ft-menu">
                            <?php
                            if ($footer_menu2 != '') echo "<h4 class='footer-title'>" . $footer_menu2 . "</h4>";
                            if (has_nav_menu('footer2')) {
                                wp_nav_menu(array(
                                    'theme_location' => 'footer2',
                                    'menu_class' => 'footer-sub-menu',
                                ));
                            }
                            ?>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-3 footer-contact-wrap  ">
                        <?php if ($contact_ft != '') { ?>
                            <h4 class="footer-title"> <?php echo $contact_ft; ?> </h4>
                        <?php } ?>
                        <ul class="footer-contact">
                            <?php if ($address != '') { ?>
                                <li>
                                    <?php if ($address_link){ ?>
                                    <a href="<?php echo $address_link; ?>" rel="nofollow">
                                        <?php }else{ ?>
                                        <span>
								<?php } ?>
                                <?php echo $address; ?>
                                <?php if ($address_link){ ?>
                                    </a>
                                <?php } else { ?>
                                    </span>
                                <?php } ?>
                                </li>
                            <?php } ?>
                            <?php if ($postal != '') { ?>
                                <li>
                                    <span>
                                            <?php echo $postal; ?>
                                        </span>
                                </li>
                            <?php } ?>
                            <?php if ($phone != '' || $phone2 != '') { ?>
                                <li class="footer_call">
                                    <?php if ($phone != '') { ?>
                                        <span class="footer-phone">شماره تماس:</span>
                                        <a href="tel:<?php echo $phone; ?>" rel="nofollow"><?php echo $phone; ?></a>
                                    <?php } ?>
                                    <?php if ($phone2 != '') { ?>
                                        <a href="tel:<?php echo $phone2; ?>" rel="nofollow"><?php echo $phone2; ?></a>
                                    <?php } ?>

                                </li>
                            <?php } ?>

                        </ul>

                    </div>
                </div>
            </div>
        </section>


        <div class="row footer-end mt-4 mb-2">
            <div class="mx-auto col-sm-8 col-md-4 ">
                <div class="socials-wrap"><?php echo websima_socials(); ?></div>
            </div>
            <div class=" col-md-4">
                <?php if (have_rows('footer_images', 'option')): ?>
                    <div class="logos-wrap">
                        <?php while (have_rows('footer_images', 'option')) : the_row();
                            $sub_img = get_sub_field('img');
                            ?>
                            <div>
                                <?php echo wp_get_attachment_image($sub_img, 'thumbnail'); ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>

            </div>

            <div class="mx-auto col-sm-8 col-md-3 enamad-wrap">
                <div class="enamad">
                    <?php  echo get_field('codes_enamad','option');?>
                </div>
                <div class="enamad">
                    <?php  echo get_field('codes_enamad2','option');?>
                </div>

            </div>
        </div>
    </div>

        <div class=" footer-bottom">
            <?php if($copyright){?>
                <div class="copy-right">
                    <p><?php echo $copyright; ?></p>
                </div>
            <?php }?>
        </div>

</footer>

