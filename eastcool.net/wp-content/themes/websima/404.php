<?php
get_header();
$error_image = get_field('error_image', 'option');
$error_text = get_field('error_text', 'option'); 
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

<?php
// function websima_rename_order_statuses($order_id){
//    $status = wc_get_order_statuses($order_id);
//    if( isset( $status['wc-completed'] ) )
//        $status['wc-completed'] = str_replace( 'تکمیل شده', __( 'Order Received', 'woocommerce'), $status['wc-completed'] );
//    if( isset( $status['wc-processing'] ) )
//        $status['wc-processing'] = str_replace( 'در حال انجام', __( 'In Process', 'woocommerce'), $status['wc-processing'] );
//    if( isset( $status['wc-on-hold'] ) )
//        $status['wc-on-hold'] = str_replace( 'در انتظار بررسی', __( 'Order on Hold', 'woocommerce'), $status['wc-on-hold'] );
//    if( isset( $status['wc-pending'] ) )
//        $status['wc-pending'] = str_replace( 'در انتظار پرداخت', __( 'Payment Pending', 'woocommerce'), $status['wc-pending'] );
//    return $status;
//}

?>
