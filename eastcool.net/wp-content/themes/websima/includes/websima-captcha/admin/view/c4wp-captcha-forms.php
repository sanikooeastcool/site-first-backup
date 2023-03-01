<?php
/**
 * This Files Print Captcha Forms HTML of WP Captcha Plugin in admin Section.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */

if ( is_plugin_active('woocommerce/woocommerce.php') ) {
	$woocommerce_disable = '';
	$woocommerce_opacity = '';
} else {
	$woocommerce_disable = 'disabled="disabled"';
	$woocommerce_opacity = 'style="opacity:0.5;"';
}
?>
<div class="col-10 c4wp-captcha-forms-content mx-auto">
	<div class="row">
		<div class="form-group col-md-3">
			<input id="wp_login_form" type="checkbox" name="c4wp_options[enable_form_settings][wp_login_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_login_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_login_form'], true ); } ?> /><label for="wp_login_form"><?php _e( 'Wordpress Login', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-3">
			<input id="wp_reset_password_form" type="checkbox" name="c4wp_options[enable_form_settings][wp_reset_password_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_reset_password_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_reset_password_form'], true ); } ?> /><label for="wp_reset_password_form"><?php _e( 'Wordpress Reset Password', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-3">
			<input id="wp_comment_form" type="checkbox" name="c4wp_options[enable_form_settings][wp_comment_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_comment_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wp_comment_form'], true ); } ?>/><label for="wp_comment_form"><?php _e( 'Wordpress Comments', 'wp-captcha' ); ?></label>
		</div>
		<div class="form-group col-md-3">
			<input id="wc_checkout_form" type="checkbox" name="c4wp_options[enable_form_settings][wc_checkout_form]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_checkout_form'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['enable_form_settings']['wc_checkout_form'], true ); } ?> <?php echo $woocommerce_disable; ?>/><label for="wc_checkout_form" <?php echo $woocommerce_opacity; ?>><?php _e( 'WooCommerce Checkout', 'wp-captcha' ); ?></label>
		</div>
	</div>				
</div>