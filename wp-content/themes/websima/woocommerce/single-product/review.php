<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$additional_comment_classes = '';

if ( user_can( $comment->user_id, 'administrator' ) ) {
    $additional_comment_classes = "class='byadmin'";
}
?>

<li <?php echo $additional_comment_classes; ?> id="li-comment-<?php comment_ID(); ?>">

    <div id="comment-<?php comment_ID(); ?>" class="comment_container">
        <div class="row">
            <div class="col-12">
                <div class="head-comment">
                    <span class="time_comment"><i class="icon-calendar"></i><?php echo get_comment_date('Y.m.j'); ?></span>
                    <span class="name_comment"><?php comment_author(); ?></span>
                    <?php comment_reply_link(array_merge( $args, array('reply_text' => "پاسخ دهید", 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                </div>
                <div class="editor-content">
                    <?php echo comment_text(); ?>
                    <?php if ( $comment->comment_approved == '0' ) : ?>
                        <em class="comment-awaiting-moderation waiting_pm"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
