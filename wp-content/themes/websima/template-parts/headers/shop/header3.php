<?php
$logo = get_field('logo', 'option');
?>
<header id="header">
    <div class="container">
        <div class="row top-header justify-content-between">
            <div class="header-mm "><i class="icon-menu"></i></div>
            <div class="col-7 col-sm-6 col-md-6 col-lg-4 logo-wrap">
                <?php if ($logo): ?>
                    <a class="header-logo" href="<?php echo home_url(); ?>" title="<?php echo bloginfo('name'); ?>">
                        <?php echo wp_get_attachment_image($logo, 'full'); ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class=" col-lg-5 bottom-head-search">
                <div class="header-search"><span>search ...</span><i class="icon-search"></i> </div>
                <div class="nav-compare">
                    <?php websima_compare_link() ?>
                </div>

                <div class="parent_item_cart item_has_sub">
                    <a class="head_item_cart" href="<?php echo wc_get_cart_url(); ?>">
                        <i class="icon-basket"></i>
                        <em class="count"><?php echo WC()->cart->get_cart_contents_count();?></em>
                    </a>
                    <div class="sub_part">
                        <div class="widget_shopping_cart_content">
                            <?php woocommerce_mini_cart(); ?>
                        </div>
                    </div>
                </div>
                <div class="head_account item_has_sub">
                    <?php websima_auth_modal_btn(); ?>
                </div>
                <?php $en_link = get_field('en_link', 'option');
                if($en_link){
                    echo'<a href="'.$en_link.'" class="lang"><i class="icon-global"></i><span>EN</span></a>';
                }
                ?>

            </div>
        </div>

    </div>
    <nav id="nav">
        <div class="container">
            <div class="row nav-head ">
                <div id="head_menu" class="col-12 d-none d-lg-block">
                    <?php echo websima_custom_menu(); ?>
                </div>

            </div>
        </div>
    </nav>
</header>
