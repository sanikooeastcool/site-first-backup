<?php
get_header();
$error_image = get_field('error_image', 'option');
$error_text = get_field('error_text', 'option'); 

/* $query = new WC_Order_Query( array(
    'limit' =>  -1,
    'orderby' => 'date',
    'status' =>array('wc-cancelled','wc-panding'),
    'return' => 'ids',
	'payment_method' => 'melli_pay',
) );
$orders = $query->get_orders();
foreach($orders as $order):
echo $order;
echo '<br>';
endforeach; */
?>
<main id="main" class="site-main">
    <div class="container mx-auto">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4 mt-5 my-md-5 error-wrap">

                    <span>404</span>
                    <?php if($error_text):?>
                        <p><?php  echo $error_text; ?></p>
                    <?php endif;?>
                    <p>لطفا به صفحه اصلی مراجعه فرمایید.</p>
                    <a href="<?php echo home_url('/'); ?>" class="error-404-btn button">صفحه اصلی</a>

                </div>
            <div class="col-md-6 col-lg-3 my-4 error-page">
                <?php if($error_image):?>
                    <?php  echo wp_get_attachment_image($error_image,'full'); ?>
                <?php endif;?>
            </div>

        </div>
    </div>
</main>
<?php get_footer(); ?>