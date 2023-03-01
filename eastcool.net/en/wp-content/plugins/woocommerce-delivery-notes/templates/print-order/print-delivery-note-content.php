<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$oid = $order->get_order_number();
$shipping_method = $order->get_items('shipping');
$shipping_method = reset($shipping_method);
$protocols = array('http://', 'http://www.', 'www.', 'https://', 'https://www.');

$title = get_field('invoice_title', 'option');
$admin_message = get_field('invoice_admin_message', 'option');

$company_logo = get_field('invoice_company_logo', 'option');
$company_name = get_field('invoice_company_name', 'option');
$company_address = get_field('invoice_company_address', 'option');
$company_phone = get_field('invoice_company_phone', 'option');
$company_fax = get_field('invoice_company_fax', 'option');
$company_postal_code = get_field('invoice_company_postal_code', 'option');
$company_nid = get_field('invoice_company_nid', 'option');
$company_rid = get_field('invoice_company_rid', 'option');
$company_eid = get_field('invoice_company_eid', 'option');


$shipping_first_name = get_post_meta(esc_attr($oid) ,'_shipping_first_name', true);
$shipping_last_name = get_post_meta(esc_attr($oid) ,'_shipping_last_name', true);
$shipping_phone = get_post_meta(esc_attr($oid) ,'_shipping_phone', true);
$shipping_postcode = get_post_meta(esc_attr($oid) ,'_shipping_postcode', true);
$shipping_address_1 = get_post_meta(esc_attr($oid) ,'_shipping_address_1', true);
$shipping_city = get_post_meta(esc_attr($oid) ,'_shipping_city', true);
$shipping_state = get_post_meta(esc_attr($oid) ,'_shipping_state', true);

echo '<div id="delivery-note" class="box delivery-note-box white-bg without-padding">';
	if($company_name or $company_address or $company_postal_code or $company_phone or $company_fax){
		echo '<div class="sender-info">';
			if($company_name or $company_address or $company_postal_code){
				echo '<ul>';
					if($company_name){
						echo '<li class="list-item">';
							echo '<span class="title">فرستنده:</span>';
							echo '<span class="value">'.esc_html($company_name).'</span>';
						echo '</li>';
					}
					if($company_address){
						echo '<li class="list-item">';
							echo '<span class="title">آدرس:</span>';
							echo '<span class="value">'.esc_html($company_address).'</span>';
						echo '</li>';
					}
					if($company_postal_code){
						echo '<li class="list-item">';
							echo '<span class="title">کد پستی:</span>';
							echo '<span class="value">'.esc_html($company_postal_code).'</span>';
						echo '</li>';
					}
				echo '</ul>';
			}
			if($company_phone or $company_fax){
				echo '<ul>';
					if($company_phone){
						echo '<li class="list-item">';
							echo '<span class="title">تلفن:</span>';
							echo '<span class="value">'.esc_html($company_phone).'</span>';
						echo '</li>';
					}
					if($company_fax){
						echo '<li class="list-item">';
							echo '<span class="title">فکس:</span>';
							echo '<span class="value">'.esc_html($company_fax).'</span>';
						echo '</li>';
					}
				echo '</ul>';
			}
		echo '</div>';
	}
	echo '<div class="receiver-client-info">';
		echo '<div class="row no-gutters">';
			echo '<div class="column column-8">';
				echo '<div class="receiver-info">';
					echo '<ul>';
						echo '<li class="list-item">';
							echo '<span class="title">گیرنده:</span>';
							echo '<span class="value">'.websima_invoice_iran_state($shipping_state).' '.esc_html($shipping_city).' '.esc_html($shipping_address_1).'</span>';
						echo '</li>';
						echo '<li class="list-item">';
							echo '<span class="title">کد پستی:</span>';
							echo '<span class="value">'.esc_html($shipping_postcode).'</span>';
						echo '</li>';
					echo '</ul>';
					echo '<ul>';
						echo '<li class="list-item">';
							echo '<span class="title">نام کامل:</span>';
							echo '<span class="value">'.esc_html($shipping_first_name).' '.esc_html($shipping_last_name).'</span>';
						echo '</li>';
						echo '<li class="list-item">';
							echo '<span class="title">تلفن:</span>';
							echo '<span class="value">'.esc_html($shipping_phone).'</span>';
						echo '</li>';
					echo '</ul>';
				echo '</div>';


				echo '<div class="order-meta-info">';
					echo '<ul>';
						$fields = apply_filters( 'wcdn_order_info_fields', wcdn_get_order_info( $order ), $order );
						unset($fields['billing_email']);
						unset($fields['billing_phone']);
						if($shipping_method){
						$fields['shipping_method']['label'] = 'روش حمل و نقل';
						$fields['shipping_method']['value'] = $shipping_method->get_name();
                        }
						foreach ( $fields as $key => $field ) :
							echo '<li class="list-item '.esc_attr($key).'">';
								echo '<span class="title">'.wp_kses_post( apply_filters( 'wcdn_order_info_name', $field['label'], $field ) ).':</span>';
								if($field['label'] == 'تاریخ سفارش'){
									$order_date = wp_kses_post( apply_filters( 'wcdn_order_info_content', $field['value'], $field ) );
									$order_date = websima_invoice_convert_number(implode('/',array_reverse(explode("/",$order_date))));
									echo '<span class="value">'.esc_html($order_date).'</span>';
								}else{
									echo '<span class="value">'.wp_kses_post( apply_filters( 'wcdn_order_info_content', $field['value'], $field ) ).'</span>';
								}
							echo '</li>';
						endforeach;
					echo '</ul>';
				echo '</div>';
			echo '</div>';
			echo '<div class="column column-4">';
				echo '<div class="company-branding">';
					echo '<img src="'.esc_url($company_logo).'" alt="'.get_bloginfo('name').'" class="company-logo"/>';
					echo '<span class="company-website">وبسایت: '.str_replace($protocols,'',get_bloginfo('url')).'</span>';
					echo '<span class="post-logo"></span>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</div>';
?>