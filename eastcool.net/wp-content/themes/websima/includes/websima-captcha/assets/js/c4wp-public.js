
jQuery(document).ready(function($){

	$(".refresh_captcha").on("click", function() {
		var src = $(this).data("imgsrc");
		$(this).parent().find("img.c4wp_image").attr("src", src);
	});
	
	$(".c4wp-svg-padding").on("click", function() {
		$(".c4wp-svg").removeClass("c4wp-captcha-selected");
		$(this).find(".c4wp-svg").addClass("c4wp-captcha-selected");
		var c4wp_icons = $(this).find(".c4wp-icons").val();
		$("#c4wp_user_input_captcha").val(c4wp_icons);
	});
	$("#c4wp_user_input_captcha").on('change keyup paste', function(evt) {
		var val=$(this).val();
		val=val.replace(/۰/g,'0');
		val=val.replace(/۱/g,'1');
		val=val.replace(/۲/g,'2');
		val=val.replace(/۳/g,'3');
		val=val.replace(/۴/g,'4');
		val=val.replace(/۵/g,'5');
		val=val.replace(/۶/g,'6');
		val=val.replace(/۷/g,'7');
		val=val.replace(/۸/g,'8');
		val=val.replace(/۹/g,'9');
		val=val.replace(/٠/g,'0');
		val=val.replace(/١/g,'1');
		val=val.replace(/٢/g,'2');
		val=val.replace(/٣/g,'3');
		val=val.replace(/٤/g,'4');
		val=val.replace(/٥/g,'5');
		val=val.replace(/٦/g,'6');
		val=val.replace(/٧/g,'7');
		val=val.replace(/۸/g,'8');
		val=val.replace(/٩/g,'9');
		$(this).val(val);
			 
		});
});	