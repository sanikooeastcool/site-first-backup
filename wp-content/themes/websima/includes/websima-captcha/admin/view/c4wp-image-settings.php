<?php
/**
 * This Files Print Image Captcha Settings HTML of WP Captcha Plugin in admin Section.
 * @package  WP Captcha
 * @version  1.0.0
 * @author   Devnath verma <devnathverma@gmail.com>
 */	
?>

<div class="col-12 c4wp-image-captcha-settings-content">
	<div class="row mb-2">
		<div class="form-group col-md-2">
			<label for="image_captcha_widht">عرض تصویر بر حسب پیکسل</label>
		</div>
		<div class="form-group col-md-4">
			<input type="text" class="form-control" id="image_captcha_widht" name="c4wp_options[image_captcha_setting][widht]" value="<?php echo $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['widht']; ?>"/>
		</div>
		<div class="form-group col-md-2">
			<label for="image_captcha_height">ارتفاع تصویر برحسب پیکسل</label>
		</div>
		<div class="form-group col-md-4">
			<input type="text" class="form-control" id="image_captcha_height" name="c4wp_options[image_captcha_setting][height]" value="<?php echo $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['height']; ?>"/>
		</div>
	</div>
	<div class="row mb-2">
		<div class="form-group col-md-2">
			<label for="random_lines">خطوط تصادفی</label>
		</div>
		<div class="form-group col-md-4">
			<input type="text" class="form-control" id="random_lines" name="c4wp_options[image_captcha_setting][random_lines]" value="<?php echo $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['random_lines']; ?>"/>
			<span class="description">در این مکان می توانید تعداد نمایش خطوط تصادفی در تصویر را تعیین کنید.</span>
		</div>
		<div class="form-group col-md-2">
			<label for="random_dots">نقاط تصادفی</label>
		</div>
		<div class="form-group col-md-4">
			<input type="text" class="form-control" id="random_dots" name="c4wp_options[image_captcha_setting][random_dots]" value="<?php echo $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['random_dots']; ?>"/>
			<span class="description">در این مکان می توانید تعداد نمایش نقاط تصادفی در تصویر را تعیین کنید.</span>
		</div>
	</div>
	<div class="row mb-2">
		<div class="form-group col-md-2">
			<label for="text_color">رنگ متن</label>
		</div>
		<div class="form-group col-md-4">
			<input type="text" class="c4wp-color-field" id="text_color" name="c4wp_options[image_captcha_setting][text_color]" value="<?php echo $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['text_color']; ?>"/><br />
			<span class="description">در این مکان می توانید رنگ متن در تصویر را تعیین کنید.</span>
		</div>
		<div class="form-group col-md-2">
			<label for="noise_color">رنگ نویز</label>
		</div>
		<div class="form-group col-md-4">
			<input type="text" class="c4wp-color-field" id="noise_color" name="c4wp_options[image_captcha_setting][noise_color]" value="<?php echo $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['noise_color']; ?>"/><br />
			<span class="description">در این مکان می توانید رنگ نویز در تصویر را تعیین کنید.</span>
		</div>
	</div>
	<div class="row mb-2">
		<div class="form-group col-md-2">
			<label for="character_types">انواع کاراکتر در تصویر</label>
		</div>
		<div class="form-group col-md-4">
			<select id="character_types" class="form-control" name="c4wp_options[image_captcha_setting][character_types]">
				<option value="only_numbers" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['character_types'], "only_numbers" ); ?>>فقط عدد</option>
				<option value="only_alphabets" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['character_types'], "only_alphabets" ); ?>>فقط حروف</option>
				<option value="alphabets_and_numbers" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['character_types'], "alphabets_and_numbers" ); ?>>ترکیب عدد و حروف</option>
			</select>
		</div>
		<div class="form-group col-md-2">
			<label for="text_case">نوع کاراکتر حروف در تصویر</label>
		</div>
		<div class="form-group col-md-4">
			<select id="text_case" class="form-control" name="c4wp_options[image_captcha_setting][text_case]">
				<option value="lower_case" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['text_case'], "lower_case" ); ?>>حروف کوچک</option>
				<option value="upper_case" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['text_case'], "upper_case" ); ?>>حروف بزرگ</option>
				<option value="mixed" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['text_case'], "mixed" ); ?>>ترکیب حروف بزرگ و کوچک</option>
			</select>
		</div>
	</div>
	<div class="row mb-2">
		<div class="form-group col-md-2">
			<label for="characters_on_image">تعداد کاراکتر در تصویر</label>
		</div>
		<div class="form-group col-md-4">
			<select id="characters_on_image" class="form-control" name="c4wp_options[image_captcha_setting][characters_on_image]">
				<option value="1" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "1" ); ?>><?php _e( '1', 'wp-captcha' ); ?></option>
				<option value="2" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "2" ); ?>><?php _e( '2', 'wp-captcha' ); ?></option>
				<option value="3" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "3" ); ?>><?php _e( '3', 'wp-captcha' ); ?></option>
				<option value="4" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "4" ); ?>><?php _e( '4', 'wp-captcha' ); ?></option>
				<option value="5" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "5" ); ?>><?php _e( '5', 'wp-captcha' ); ?></option>
				<option value="6" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "6" ); ?>><?php _e( '6', 'wp-captcha' ); ?></option>
				<option value="7" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "7" ); ?>><?php _e( '7', 'wp-captcha' ); ?></option>
				<option value="8" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "8" ); ?>><?php _e( '8', 'wp-captcha' ); ?></option>
				<option value="9" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "9" ); ?>><?php _e( '9', 'wp-captcha' ); ?></option>
				<option value="10" <?php selected( $c4wp_plugin_options['c4wp_options']['image_captcha_setting']['characters_on_image'], "10" ); ?>><?php _e( '10', 'wp-captcha' ); ?></option>
			</select>
		</div>
	</div>
</div>