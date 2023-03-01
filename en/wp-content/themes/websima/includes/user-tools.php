<?php
/**
 * Order export.
 */
add_action('admin_footer','websima_order_export_button');
function websima_order_export_button(){
    $screen = get_current_screen();
    if($screen->id == "edit-shop_order"){
        ?>
        <script type="text/javascript">

            jQuery(document).ready( function($){
                jQuery('.tablenav.top .clear, .tablenav.bottom .clear').before('<form action="#" method="POST" id="order-export-form"><span class="form-group"><label class="form-label">from</label><input type="text" class="persiandate form-control" name="ldate-from" id="ldate-from" data-jdate="" data-gdate="" autocomplete="off"/><input type="hidden" name="date-from" id="date-from"/></div><span class="form-group"><label class="form-label">to</label><input type="text" class="persiandate form-control" name="ldate-to" id="ldate-to" data-jdate="" data-gdate="" autocomplete="off"/><input type="hidden" name="date-to" id="date-to"/></div><input type="hidden" name="order-export-csv" id="order-export-csv" value="accept"/><input type="submit" class="button button-primary" value="خروجی csv"/></form>');
            });
            jQuery(function(){

                if (jQuery("#ldate-from").length > 0) {
                    jQuery("#ldate-from").persianDatepicker({
                        onSelect: function () {
                            jQuery("#date-from").val(jQuery("#ldate-from").attr("data-gdate"));
                        }
                    });
                }

                if (jQuery("#ldate-to").length > 0) {
                    jQuery("#ldate-to").persianDatepicker({
                        onSelect: function () {
                            jQuery("#date-to").val(jQuery("#ldate-to").attr("data-gdate"));
                        }
                    });
                }
                    
            });

        </script>
        <?php
    }
}

add_action('admin_init','websima_order_export_operation');
function websima_order_export_operation(){
    if(is_admin()){
        if(is_user_logged_in()){
            if(current_user_can('manage_options')){
                if(!empty($_POST)){
                    if($_POST['order-export-csv'] == 'accept'){
                        $args = array();
						$args['post_type'] = 'shop_order';
						$args['post_status'] = array(
                            'wc-pending'    => _x( 'pending', 'Order status', 'woocommerce' ),
                            'wc-processing' => _x( 'processing', 'Order status', 'woocommerce' ),
                            'wc-on-hold'    => _x( 'on-hold', 'Order status', 'woocommerce' ),
                            'wc-completed'  => _x( 'completed', 'Order status', 'woocommerce' ),
                            'wc-cancelled'  => _x( 'cancelled', 'Order status', 'woocommerce' ),
                            'wc-refunded'   => _x( 'refunded', 'Order status', 'woocommerce' ),
                            'wc-failed'     => _x( 'failed', 'Order status', 'woocommerce' ),
                        );
						$args['posts_per_page'] = -1;
						$args['fields'] = 'ids';
						if($_POST['date-from'] and $_POST['date-to']){
							$start = explode('/',$_POST['date-from']);
							$end = explode('/',$_POST['date-to']);
							
							$args['date_query']['before']['year'] = esc_attr($end[0]);
							$args['date_query']['before']['month'] = esc_attr($end[1]);
							$args['date_query']['before']['day'] = esc_attr($end[2]);
							
							$args['date_query']['after']['year'] = esc_attr($start[0]);
							$args['date_query']['after']['month'] = esc_attr($start[1]);
							$args['date_query']['after']['day'] = esc_attr($start[2]);
							
							$args['date_query']['inclusive'] = true;
						}
						$orders = get_posts($args);
                        if(!empty($orders)){
                            $csv_fields = array();
                            $csv_fields[] = 'Order number';
                            $csv_fields[] = 'order date';
                            $csv_fields[] = 'order status';
                            $csv_fields[] = 'full name';
                            $csv_fields[] = 'code id';
                            $csv_fields[] = 'country code';
                            $csv_fields[] = 'province';
                            $csv_fields[] = 'city';
                            $csv_fields[] = 'address';
                            $csv_fields[] = 'phone';
                            $csv_fields[] = 'province';
                            $csv_fields[] = 'payment method';
                            $csv_fields[] = 'Shipping method';
                            $csv_fields[] = 'shipping price';
                            $csv_fields[] = 'Total order price';
                            $csv_fields[] = '  total price';
                            $csv_fields[] = 'product title';
                            $csv_fields[] = 'product count';
                            $csv_fields[] = ' product sku';
                            $csv_fields[] = 'product category';
                            $csv_fields[] = 'compnay';
                            $csv_fields[] = 'National ID';
                            $csv_fields[] = 'Economic code';
                            $csv_fields[] = 'registration number';
                            $output_filename = 'order.csv';
                            $output_handle = @fopen( 'php://output', 'w' );

                            header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
                            header( 'Content-Description: File Transfer' );
                            header( 'Content-type: text/csv' );
                            header('Content-Transfer-Encoding: binary');
                            header( 'Content-Disposition: attachment; filename=' . $output_filename );
                            header( 'Expires: 0' );
                            header( 'Pragma: public' );
                            echo "\xEF\xBB\xBF"; // UTF-8 BOM
                            fputcsv( $output_handle, $csv_fields );

                            foreach($orders as $order_id){
                                $order = wc_get_order( $order_id );
                             
                                $order_date = jdate( 'Y/m/d g:ia', strtotime( $order->get_date_modified() ) );

                                if($order->get_status() == 'completed') {
                                    $order_status = 'completed';
                                }elseif ($order->get_status() == 'pending'){
                                    $order_status = 'pending';
                                }elseif ($order->get_status() == 'processing'){
                                    $order_status = 'processing';
                                }elseif ($order->get_status() == 'on-hold'){
                                    $order_status = 'on-hold';
                                }elseif ($order->get_status() == 'cancelled'){
                                    $order_status = 'cancelled';
                                }elseif ($order->get_status() == 'failed'){
                                    $order_status = 'failed';
                                }elseif ($order->get_status() == 'refunded'){
                                    $order_status = 'refunded';
                                }else {
                                    $order_status = $order->get_status();
                                }
                                $customer_full_name = $order->get_formatted_billing_full_name();
                                $national_code = get_post_meta(esc_attr($order->id),'national-code',true);
                                $order_country = $order->get_billing_country();
                                $order_city =  $order->get_billing_city();
                                $order_state = $order->get_billing_state();
                                $order_address = $order->get_billing_address_1();
                                $customer_phone = $order->get_billing_phone();
                                $order_postal = $order->get_billing_postcode();
                                $order_payment_method = $order->get_payment_method_title();
                                $order_shipping_method = $order->get_shipping_method();
                                $order_shipping_total = $order->get_shipping_total();
                                $order_subtotal = $order->get_subtotal();
                                $order_total = $order->get_total();
                                $national_id = get_post_meta(esc_attr($order->id),'national-id',true);
                                $company = get_post_meta(esc_attr($order->id),'company',true);
                                $economic_code = get_post_meta(esc_attr($order->id),'economic-code',true);
                                $register_number = get_post_meta(esc_attr($order->id),'register-number',true);

                                // products meta
                                foreach ( $order->get_items() as $item_id => $item ) {
								   $product_id = $item->get_product_id();
								    $product = $item->get_product();
                                    $terms = get_the_terms( $product_id, 'product_cat' );
                                    foreach ($terms as $term) {
                                        $product_cat = $term->name;
                                    }
								    if($product->post_type == 'product_variation'){
										$variation_id = $item->get_variation_id();
										$product_name = websima_get_product_variation_title2($variation_id);
								    }else{
									    $product_name = $item->get_name();

								    }
                                    $product_sku =$item->get_product()->get_sku();
								   $quantity = $item->get_quantity();
								   $subtotal = $item->get_subtotal();
								   $total = $item->get_total();
								    $leadArray = array();
									$leadArray[] = esc_html($order_id);
									$leadArray[] = esc_html($order_date);
									$leadArray[] = esc_html($order_status);
									$leadArray[] = esc_html($customer_full_name);
									$leadArray[] = esc_html($national_code);
									$leadArray[] = esc_html($order_country);
									$leadArray[] = esc_html($order_city);
									$leadArray[] = esc_html($order_state);
									$leadArray[] = esc_html($order_address);
									$leadArray[] = esc_html($customer_phone);
									$leadArray[] = esc_html($order_postal);
									$leadArray[] = esc_html($order_payment_method);
									$leadArray[] = esc_html($order_shipping_method);
									$leadArray[] = esc_html($order_shipping_total);
									$leadArray[] = esc_html($order_subtotal);
									$leadArray[] = esc_html($order_total);
									$leadArray[] = esc_html($product_name);
									$leadArray[] = esc_html($quantity);
									$leadArray[] = esc_html($product_sku);
									$leadArray[] = esc_html($product_cat);
									$leadArray[] = esc_html($company);
									$leadArray[] = esc_html($national_id);
									$leadArray[] = esc_html($economic_code);
									$leadArray[] = esc_html($register_number);
									fputcsv( $output_handle, $leadArray );
								}

                            }

                            fclose( $output_handle );
                            die();
                        }
                    }
                }
            }
        }
    }
}
function websima_get_product_variation_title2($variation_id)
{
    $variation = wc_get_product(esc_attr($variation_id));
    $title = $variation->get_title() . ' ';
    $counter = 1;
    $count = count($variation->get_variation_attributes());
    foreach ($variation->get_variation_attributes() as $var) {
        $title .= urldecode($var);
        if ($counter < $count) {
            $title .= ' / ';
        }
        $counter++;
    }
    return $title;
}
