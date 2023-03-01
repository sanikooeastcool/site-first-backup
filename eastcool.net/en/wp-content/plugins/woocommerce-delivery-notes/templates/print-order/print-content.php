<?php
/**
 * Print order content. Copy this file to your themes
 * directory /woocommerce/print-order to customize it.
 *
 * @package WooCommerce Print Invoice & Delivery Note/Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


$oid = $order->get_order_number();

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
?>

	<div class="order-branding">
		<?php
		if ($company_logo != ''){
			echo '<div class="company-logo">';			
					echo '<img src="'.esc_url($company_logo).'" alt="'.get_bloginfo('name').'"/>';
			echo '</div>';
		}

		if($title){
		    echo '<div class="company-name"><h1>'.esc_html($title).'</h1></div>';
        }
		do_action( 'wcdn_after_branding', $order ); 
		?>
	</div><!-- .order-branding -->

	<div class="order-info box">
		<ul class="info-list">
			<?php
			$fields = apply_filters( 'wcdn_order_info_fields', wcdn_get_order_info( $order ), $order );
			unset($fields['billing_email']);
			unset($fields['billing_phone']);
			?>
			<?php foreach ( $fields as $field ) : ?>
				<li>
					<strong><?php echo wp_kses_post( apply_filters( 'wcdn_order_info_name', $field['label'], $field ) ); ?>:</strong>					
					<?php 
					if($field['label'] == 'تاریخ سفارش'){
					$order_date = wp_kses_post( apply_filters( 'wcdn_order_info_content', $field['value'], $field ) ); 
					$order_date = websima_invoice_convert_number(implode('/',array_reverse(explode("/",$order_date))));
					?>
					<span><?php echo esc_html($order_date); ?></span>
					<?php }else{ ?>
					<span><?php echo wp_kses_post( apply_filters( 'wcdn_order_info_content', $field['value'], $field ) ); ?></span>
					<?php } ?>
				</li>
			<?php endforeach; ?>
		</ul>

        <?php
        $delivery_day = get_post_meta(esc_attr($oid), 'delivery_day', true);
        $delivery_time = get_post_meta(esc_attr($oid), 'delivery_time', true);

        if($delivery_day or $delivery_time){
            echo '<ul class="info-list">';
            if($delivery_day){
                echo '<li><strong>تاریخ تحویل سفارش: </strong><span class="d-inline-block">'.esc_html($delivery_day).'</span></li>';
            }
            if($delivery_time){
                $delivery_time_array = explode("-",$delivery_time);
                $start = $delivery_time_array[0];
                $end = $delivery_time_array[1];
                $final_time = 'از '.esc_html($start).' تا '.esc_html($end);
                echo '<li><strong>ساعت تحویل سفارش: </strong><span>'.esc_html($final_time).'</span></li>';
            }
            echo '</ul>';
        }
        ?>

		<?php //do_action( 'wcdn_after_info', $order ); ?>
	</div><!-- .order-info -->
	
	<?php
	$payment_datetime = '';
	$transaction_id = get_post_meta(esc_attr($oid) ,'_transaction_id', true);
	$sale_order_id = get_post_meta(esc_attr($oid) ,'_SaleOrderId', true);
	$payment_timestamp = get_post_meta(esc_attr($oid) ,'_date_paid', true);
	/*if($payment_timestamp){
		$payment_date = parsidate("Y/m/d", esc_attr($payment_timestamp),'en');
	
		$timezone = get_option('timezone_string');
		$dt = new DateTime("now", new DateTimeZone($timezone));
		$payment_time = $dt->setTimestamp($payment_timestamp)->format('H:i');
		
		$payment_datetime = $payment_date.' '.$payment_time;
	}*/
    if($payment_timestamp){
        $payment_datetime = jdate('Y/m/d H:i',esc_attr($payment_timestamp),'','Asia/Tehran','en');
    }
	
	if($transaction_id or $sale_order_id or $payment_datetime){
		echo '<div class="order-info box">';
			echo '<ul class="info-list">';			
				if($transaction_id){
					echo '<li>';
						echo '<strong>کد رهگیری: </strong>';
						echo '<span>'.esc_html($transaction_id).'</span>';
					echo '</li>';
				}
				
				if($sale_order_id){
					echo '<li>';
						echo '<strong>شماره درخواست تراکنش: </strong>';
						echo '<span>'.esc_html($sale_order_id).'</span>';
					echo '</li>';	
				}
				
				if($payment_datetime){
					echo '<li>';
						echo '<strong>تاریخ پرداخت: </strong>';
						echo '<span class="ltr">'.esc_html($payment_datetime).'</span>';
					echo '</li>';	
				}
			echo '</ul>';
		echo '</div>';
	} 
	?>
	
	<div class="order-addresses 
	<?php
	$order_id = $order->get_order_number();
	if ( ! wcdn_has_shipping_address( $order ) ) :
		?>
		no-shipping-address<?php endif; ?>">
		<div class="billing-address address-box">
			<h3><span>خریدار<span></h3>
			<address>
				<?php
				if ( ! $order->get_formatted_billing_address() ) {
					esc_attr_e( 'N/A', 'woocommerce-delivery-notes' );
				} else {					
					$billing_first_name = get_post_meta(esc_attr($order_id) ,'_billing_first_name', true);
					$billing_last_name = get_post_meta(esc_attr($order_id) ,'_billing_last_name', true);
					$billing_phone = get_post_meta(esc_attr($order_id) ,'_billing_phone', true);
					$billing_postcode = get_post_meta(esc_attr($order_id) ,'_billing_postcode', true);
					$billing_address_1 = get_post_meta(esc_attr($order_id) ,'_billing_address_1', true);
					$billing_city = get_post_meta(esc_attr($order_id) ,'_billing_city', true);
					$billing_state = get_post_meta(esc_attr($order_id) ,'_billing_state', true);
					$customer_type = get_post_meta(esc_attr($order_id) ,'customer-type', true);
					$national_code = get_post_meta(esc_attr($order_id) ,'national-code', true);
					$national_id = get_post_meta(esc_attr($order_id) ,'national-id', true);
					$economic_code = get_post_meta(esc_attr($order_id) ,'economic-code', true);
					$register_number = get_post_meta(esc_attr($order_id) ,'register-number', true);
					$company = get_post_meta(esc_attr($order_id) ,'company', true);
					
					echo '<div class="row">';
						echo '<div class="column column-8 list-item">';
							echo '<span class="title">نام:</span>';
							echo '<span class="value">'.esc_html($billing_first_name).' '.esc_html($billing_last_name).'</span>';
						echo '</div>';
						
						echo '<div class="column column-4 list-item">';
							echo '<span class="title">تلفن:</span>';
							echo '<span class="value">'.esc_html($billing_phone).'</span>';
						echo '</div>';
					echo '</div>';
					
					echo '<div class="row">';
						echo '<div class="column column-8 list-item">';
							echo '<span class="title">آدرس:</span>';
							echo '<span class="value">'.websima_invoice_iran_state($billing_state).' '.esc_html($billing_city).' '.esc_html($billing_address_1).'</span>';
						echo '</div>';
						
						echo '<div class="column column-4 list-item">';
							echo '<span class="title">کد پستی:</span>';
							echo '<span class="value">'.esc_html($billing_postcode).'</span>';
						echo '</div>';
					echo '</div>';

					if($customer_type == 'haghighi'){
						echo '<div class="row">';									
							echo '<div class="column column-12 list-item list-item">';
								echo '<span class="title">کد ملی:</span>';
								echo '<span class="value">'.esc_html($national_code).'</span>';
							echo '</div>';
						echo '</div>';
					}elseif($customer_type == 'hoghooghi'){
						echo '<div class="row">';							
							echo '<div class="column column-8 list-item">';
								echo '<span class="title">نام شرکت:</span>';
								echo '<span class="value">'.esc_html($company).'</span>';
							echo '</div>';
																			
							echo '<div class="column column-4 list-item">';
								echo '<span class="title">شماره ثبت:</span>';
								echo '<span class="value">'.esc_html($register_number).'</span>';
							echo '</div>';
							
							echo '<div class="column column-8 list-item">';
								echo '<span class="title">کد اقتصادی:</span>';
								echo '<span class="value">'.esc_html($economic_code).'</span>';
							echo '</div>';
							
							echo '<div class="column column-4 list-item">';
								echo '<span class="title">شناسه ملی:</span>';
								echo '<span class="value">'.esc_html($national_id).'</span>';
							echo '</div>';
						echo '</div>';
					}
				}
				?>
			</address>
		</div>

		<div class="shipping-address address-box">						
			<h3><span>گیرنده</span></h3>
			<address>

				<?php
				if ( ! $order->get_formatted_shipping_address() ) {
					esc_attr_e( 'N/A', 'woocommerce-delivery-notes' );
				} else {
					$shipping_first_name = get_post_meta(esc_attr($order_id) ,'_shipping_first_name', true);
					$shipping_last_name = get_post_meta(esc_attr($order_id) ,'_shipping_last_name', true);
					$shipping_phone = get_post_meta(esc_attr($order_id) ,'_shipping_phone', true);
					$shipping_postcode = get_post_meta(esc_attr($order_id) ,'_shipping_postcode', true);
					$shipping_address_1 = get_post_meta(esc_attr($order_id) ,'_shipping_address_1', true);
					$shipping_city = get_post_meta(esc_attr($order_id) ,'_shipping_city', true);
					$shipping_state = get_post_meta(esc_attr($order_id) ,'_shipping_state', true);
					echo '<div class="wrapper">';
					echo '<div class="row">';
						echo '<div class="column column-8 list-item">';
							echo '<span class="title">نام:</span>';
							echo '<span class="value">'.esc_html($shipping_first_name).' '.esc_html($shipping_last_name).'</span>';
						echo '</div>';
						
						echo '<div class="column column-4 list-item">';
							echo '<span class="title">تلفن:</span>';
							echo '<span class="value">'.esc_html($shipping_phone).'</span>';
						echo '</div>';
					echo '</div>';
					
					echo '<div class="row">';
						echo '<div class="column column-8 list-item">';
							echo '<span class="title">آدرس:</span>';
							echo '<span class="value">'.websima_invoice_iran_state($shipping_state).' '.esc_html($shipping_city).' '.esc_html($shipping_address_1).'</span>';
						echo '</div>';
						
						echo '<div class="column column-4 list-item">';
							echo '<span class="title">کد پستی:</span>';
							echo '<span class="value">'.esc_html($shipping_postcode).'</span>';
						echo '</div>';
					echo '</div>';
					echo '</div>';
				}
				?>

			</address>
		</div>

		<?php do_action( 'wcdn_after_addresses', $order ); ?>
	</div><!-- .order-addresses -->

	<div class="order-items">
		<table>
			<thead>
				<tr>
					<th class="head-name"><span>کالا</span></th>
					<th class="head-item-price">قیمت</span></th>
					<th class="head-quantity"><span>تعداد</span></th>
					<th class="head-price"><span>مجموع</span></th>
				</tr>
			</thead>

			<tbody>
				<?php

				if ( count( $order->get_items() ) > 0 ) :
					?>
					<?php foreach ( $order->get_items() as $item ) : ?>

						<?php

						$product = apply_filters( 'wcdn_order_item_product', $item->get_product(), $item );
						if ( ! $product ) {
							continue;
						}
						if ( version_compare( get_option( 'woocommerce_version' ), '3.0.0', '>=' ) ) {
							$item_meta = new WC_Order_Item_Product( $item['item_meta'], $product );
						} else {
							$item_meta = new WC_Order_Item_Meta( $item['item_meta'], $product );
						}
						?>
						<tr>
							<td class="product-name">
								<?php do_action( 'wcdn_order_item_before', $product, $order, $item ); ?>
								<span class="name">
								<?php

								$addon_name  = $item->get_meta( '_wc_pao_addon_name', true );
								$addon_value = $item->get_meta( '_wc_pao_addon_value', true );
								$is_addon    = ! empty( $addon_value );

								if ( $is_addon ) { // Displaying options of product addon.
									$addon_html = '<div class="wc-pao-order-item-name">' . esc_html( $addon_name ) . '</div><div class="wc-pao-order-item-value">' . esc_html( $addon_value ) . '</div></div>';

									echo wp_kses_post( $addon_html );
								} else {

									$product_id   = $item['product_id'];
									$prod_name    = get_post( $product_id );
									$product_name = $prod_name->post_title;

									echo wp_kses_post( apply_filters( 'wcdn_order_item_name', $product_name, $item ) );
									?>
									</span>

									<?php

									if ( version_compare( get_option( 'woocommerce_version' ), '3.0.0', '>=' ) ) {
										if ( isset( $item['variation_id'] ) && 0 !== $item['variation_id'] ) {
											$variation = wc_get_product( $item['product_id'] );
											foreach ( $item['item_meta'] as $key => $value ) {
												if ( ! ( 0 === strpos( $key, '_' ) ) ) {
													if ( is_array( $value ) ) {
														continue;
													}
													$term_wp        = get_term_by( 'slug', $value, $key );
													$attribute_name = wc_attribute_label( $key, $variation );
													if ( isset( $term_wp->name ) ) {
														echo ' - ' . wp_kses_post( $attribute_name . ':' . $term_wp->name );
													} else {
														echo ' - ' . wp_kses_post(urldecode($value));
													}
												}
											}
										} else {
											foreach ( $item['item_meta'] as $key => $value ) {
												if ( ! ( 0 === strpos( $key, '_' ) ) ) {
													if ( is_array( $value ) ) {
														continue;
													}
													echo '<br>' . wp_kses_post( $key . ':' . $value );
												}
											}
										}
									} else {
										$item_meta_new = new WC_Order_Item_Meta( $item['item_meta'], $product );
										$item_meta_new->display();

									}
									?>
									<br>
									<dl class="extras">
										<?php if ( $product && $product->exists() && $product->is_downloadable() && $order->is_download_permitted() ) : ?>

											<dt><?php esc_attr_e( 'Download:', 'woocommerce-delivery-notes' ); ?></dt>
											<dd>
											<?php
											// translators: files count.
											printf( esc_attr_e( '%s Files', 'woocommerce-delivery-notes' ), count( $item->get_item_downloads() ) );
											?>
											</dd>

										<?php endif; ?>

										<?php

											$fields = apply_filters( 'wcdn_order_item_fields', array(), $product, $order, $item );

										foreach ( $fields as $field ) :
											?>

											<dt><?php echo esc_html( $field['label'] ); ?></dt>
											<dd><?php echo esc_html( $field['value'] ); ?></dd>

										<?php endforeach; ?>
									</dl>
								<?php } ?>
								<?php do_action( 'wcdn_order_item_after', $product, $order, $item ); ?>
							</td>
							<td class="product-item-price">								
								<span><?php echo wp_kses_post( wcdn_get_formatted_item_price( $order, $item ) ); ?></span>
							</td>
							<td class="product-quantity">
								<span><?php echo esc_attr( apply_filters( 'wcdn_order_item_quantity', $item['qty'], $item ) ); ?></span>
							</td>
							<td class="product-price">
								<span><?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?></span>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>

			<tfoot>
				<?php
				$totals_arr = $order->get_order_item_totals();
				if ( $totals_arr ) :

					foreach ( $totals_arr as $total ) :
						?>
						<tr>
							<td class="total-name"><span><?php echo wp_kses_post( $total['label'] ); ?></span></td>
							<td class="total-item-price"></td>
							<?php if ( 'Total' === $total['label'] ) { ?>
							<td class="total-quantity"><?php echo wp_kses_post( $order->get_item_count() ); ?></td>
							<?php } else {  ?>
							<td class="total-quantity"></td>
							<?php } ?>
							<td class="total-price"><span><?php echo wp_kses_post( $total['value'] ); ?></span></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tfoot>
		</table>

		<?php do_action( 'wcdn_after_items', $order ); ?>
	</div><!-- .order-items -->

	<?php 
	if ( wcdn_has_customer_notes( $order ) ){
		echo '<div class="order-notes box">';
            echo '<div class="list-item">';
                echo '<span class="title">یادداشت مشتری:</span>';
                echo '<span class="value">';
                    wcdn_customer_notes( $order );
                echo '</span>';
                //do_action( 'wcdn_after_notes', $order );
            echo '</div>';
		echo '</div>';
	}

	if($admin_message){
        echo '<div class="order-thanks box">';
            echo '<div class="list-item">';
                echo '<span class="title">پیام مدیر:</span>';
                echo '<span class="value"><p>'.esc_html($admin_message).'</p></span>';
            echo '</div>';
        echo '</div>';
	}
    ?>

	<div class="order-colophon">
		<?php
        if($company_name or $company_phone or $company_fax or $company_nid or $company_rid or $company_eid or $company_address or $company_postal_code){
        echo '<div class="company-info box">';
            if($company_name or $company_phone or $company_fax){
                echo '<div class="row">';
                    if($company_name){ echo '<div class="column column-4 list-item"><span class="title">نام فروشنده/شرکت:</span><span class="value">'.esc_html($company_name).'</span></div>'; }
                    if($company_phone){ echo '<div class="column column-4 list-item"><span class="title">شماره تماس:</span><span class="value">'.esc_html($company_phone).'</span></div>'; }
                    if($company_fax){ echo '<div class="column column-4 list-item"><span class="title">فکس:</span><span class="value">'.esc_html($company_fax).'</span></div>'; }
                echo '</div>';
            }
            if($company_nid or $company_rid or $company_eid){
                echo '<div class="row">';
                    if($company_nid){ echo '<div class="column column-4 list-item"><span class="title">شناسه ملی:</span><span class="value">'.esc_html($company_nid).'</span></div>'; }
                    if($company_rid){ echo '<div class="column column-4 list-item"><span class="title">شماره ثبت:</span><span class="value">'.esc_html($company_rid).'</span></div>'; }
                    if($company_eid){ echo '<div class="column column-4 list-item"><span class="title">شماره اقتصادی:</span><span class="value">'.esc_html($company_eid).'</span></div>'; }
                echo '</div>';
            }
            if($company_address or $company_postal_code){
                echo '<div class="row">';
                    if($company_address){ echo '<div class="column column-8 list-item"><span class="title">آدرس:</span><span class="value">'.esc_html($company_address).'</span></div>'; }
                    if($company_postal_code){ echo '<div class="column column-4 list-item"><span class="title">کد پستی:</span><span class="value">'.esc_html($company_postal_code).'</span></div>'; }
                echo '</div>';
            }
        echo '</div>';
        }
		?>		
		
		<div class="colophon-policies">
			<?php wcdn_policies_conditions(); ?>
		</div>

		<div class="colophon-imprint">
			<?php wcdn_imprint(); ?>
		</div>	

		<?php do_action( 'wcdn_after_colophon', $order ); ?>
	</div><!-- .order-colophon -->

