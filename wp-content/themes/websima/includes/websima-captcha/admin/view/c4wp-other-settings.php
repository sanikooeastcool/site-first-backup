<?php
/**
 * This Files Print Captcha Other Settings HTML of WP Captcha Plugin in admin Section.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */
?>
<div class="col-10 c4wp-other-settings-content mx-auto">
	<div class="row mb-2">
		<div class="form-group col-md-3">
			<label for="characters_on_image">عنوان تصویر کپچا</label>
		</div>
		<div class="form-group col-md-8">
			<input type="text" class="form-control" id="captcha_title" name="c4wp_options[other_settings][captcha_title]" value="<?php echo $c4wp_plugin_options['c4wp_options']['other_settings']['captcha_title']; ?>"/>
			
		</div>  
	</div>
	<div class="row mb-2">
		<div class="form-group col-md-3">
			<label for="characters_on_image">نمایش پیام زمان پرنکردن فیلد</label>				
		</div>
		<div class="form-group col-md-8">
			<input type="text" class="form-control" id="captcha_empty_messages" name="c4wp_options[other_settings][captcha_empty_messages]" value="<?php echo $c4wp_plugin_options['c4wp_options']['other_settings']['captcha_empty_messages']; ?>"/>
			
		</div>
	</div>
	<div class="row mb-2">
		<div class="form-group col-md-3">
			<label for="characters_on_image">نمایش پیام زمان خطا</label>				
		</div>
		<div class="form-group col-md-8">		
			<input type="text" class="form-control" id="captcha_error_messages" name="c4wp_options[other_settings][captcha_error_messages]" value="<?php echo $c4wp_plugin_options['c4wp_options']['other_settings']['captcha_error_messages']; ?>"/>
		</div>  
	</div>
	<div class="row mb-2">
		<div class="form-group col-md-3">
			<label for="characters_on_image">عدم نمایش برای کاربران لاگین شده</label>				
		</div>
		<div class="form-group col-md-8">		
			<input type="checkbox" class="form-control" id="hide_for_logged_users" name="c4wp_options[other_settings][hide_for_logged_users]" value="1" <?php if( isset( $c4wp_plugin_options['c4wp_options']['other_settings']['hide_for_logged_users'] ) ) { checked( $c4wp_plugin_options['c4wp_options']['other_settings']['hide_for_logged_users'], true ); } ?>/>
		</div>  
	</div>
</div>