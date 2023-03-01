<?php
/*
Plugin Name: افزونه درگاه پرداخت سداد بانک ملی ووکامرس
Version: 2.1
Description:  به کمک این افزونه می توانید فروشگاه ووکامرسی خود را به درگاه پرداخت سداد بانک ملی متصل کنید
Plugin URI: https://www.rtl-theme.com/sadad-payment-gateway-wordpress-plugin/
Author: حلما وب
Author URI: http://helmaweb.com/
*/

add_action( 'plugins_loaded', function () {

	if ( ! class_exists( 'Persian_Woocommerce_Gateways' ) ) {
		return add_action( 'admin_notices', function () { ?>
			<div class="notice notice-error">
				<p>برای استفاده از افزونه درگاه پرداخت سداد بانک ملی ووکامرس باید ووکامرس + ووکامرس فارسی 3.3.6 به بالا را نصب و فعال نمایید</p>
			</div>
			<?php
		} );
	}

	include_once('loader.php');
}, 999 );
