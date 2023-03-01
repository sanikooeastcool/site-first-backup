<?php

class C4WP_Admin_Wizard {
	
	public static function c4wp_wizard( $c4wp_plugin_options, $c4wp_messages ) {	?>

		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="pt-4 col-md-12">
					<form id="c4wp-wizard-form" class="c4wp-wizard-form" action="<?php echo admin_url( 'admin.php' ); ?>?page=captcha-settings" method="post">		
						<?php if( ! empty( $c4wp_messages ) ) : ?>
						<div id="message" class="c4wp-update"><?php echo $c4wp_messages; ?></div>	
						<?php endif; ?>
						<div class="c4wp-alert-success" style="display:none;"></div>
						<h3>تنظیمات تصویر کپچا</h3>
						<fieldset>
							<div class="form-card  container">
								<div class="row">
									<?php include_once('c4wp-image-settings.php'); ?>
								</div>
							</div>
						</fieldset>
						<!--<h3>تنظیمات جایگاه کپچا</h3> 
						<fieldset>
							<div class="form-card  container">
								<div class="row">
									<?php /* include_once('c4wp-captcha-forms.php'); */ ?>
								</div>	
							</div>
						</fieldset>-->
						<h3>سایر تنظیمات کپچا</h3>
						<fieldset>
							<div class="form-card  container">
								<div class="row">
									<?php include_once('c4wp-other-settings.php'); ?>
								</div>
							</div>
							<input type="submit" name="c4wp_submit" class="button c4wp-submit action-button" value="ذخیره" /> 
						</fieldset>
					</form>
				</div>
			</div>
		</div>
<?php } } ?>