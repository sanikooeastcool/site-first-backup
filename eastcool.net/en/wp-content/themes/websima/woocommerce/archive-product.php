<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

get_header('shop');
$term = get_queried_object();
$shop_page_id = wc_get_page_id('shop');

$show_sidebar = false;
if (!is_shop()){
	if(is_product_category()){
		if(get_field('show_sidebar_product_cat','option')){
			if(is_active_sidebar( 'sidebar_shop' )){
				$show_sidebar=true;
				$cls="col-12 col-md-8 col-lg-9";
			}	
		}
	}elseif(is_product_tag()){
		if(get_field('show_sidebar_product_tag','option')){
			if(is_active_sidebar( 'sidebar_shop' )){
				$show_sidebar=true;
				$cls="col-md-8";
			}	
		}
	}
}else{
	if(get_field('show_sidebar_shop','option')){
		if(is_active_sidebar( 'sidebar_shop' )){
			$show_sidebar=true;
			$cls="col-md-8";
		}	
	}
}
/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');

?>
    <main id="main" class="site-main">
        <div class="container">            
			<div class="title-part inner-title title-site-center">
                <i class="icon-token-title"></i>
			    <h1 class="title-heading"><?php woocommerce_page_title(); ?></h1>
            
			<?php
			if (!is_shop()){
				the_archive_description( '<div class="editor-content main-content">', '</div>' );

			}else{
				if(get_the_content(null,false,$shop_page_id)){ 
					echo '<div class="editor-content main-content">'.get_the_content(null,false,$shop_page_id).'</div>'; 
				}
			}
			?>
            </div>
            <div class="row">
				<?php if($show_sidebar){ ?>
				<span class="sidebar-btn">filter</span>
                <div class="col-10 col-md-4 col-lg-3" id="sidebar">
					<div class="sidebar-close"><i class="icon-close"></i></div> 
					<div class="sidebar sidebar-shop">
						<?php
						/**
						 * Hook: woocommerce_sidebar.
						 *
						 * @hooked woocommerce_get_sidebar - 10
						 */
						do_action('woocommerce_sidebar');
						?>
					</div>
                </div>
				<?php } ?>
                <div class="<?php echo $cls; ?>">                    
                    <?php
                    if (woocommerce_product_loop()) {

                        /**
                         * Hook: woocommerce_before_shop_loop.
                         *
                         * @hooked woocommerce_output_all_notices - 10
                         * @hooked woocommerce_result_count - 20
                         * @hooked woocommerce_catalog_ordering - 30
                         */
                        do_action('woocommerce_before_shop_loop');

                        woocommerce_product_loop_start();

                        if (wc_get_loop_prop('total')) {
                            while (have_posts()) {
                                the_post();

                                /**
                                 * Hook: woocommerce_shop_loop.
                                 */
                                do_action('woocommerce_shop_loop');

                                wc_get_template_part('content', 'product');
                            }
                        }

                        woocommerce_product_loop_end();

                        /**
                         * Hook: woocommerce_after_shop_loop.
                         *
                         * @hooked woocommerce_pagination - 10
                         */
                        do_action('woocommerce_after_shop_loop');
                    } else {
                        /**
                         * Hook: woocommerce_no_products_found.
                         *
                         * @hooked wc_no_products_found - 10
                         */
                        do_action('woocommerce_no_products_found');
                    }

                    /**
                     * Hook: woocommerce_after_main_content.
                     *
                     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                     */
                    do_action('woocommerce_after_main_content');
                    ?>
                    
					<?php 
					if (!is_shop()){
						if(get_field('more_desc',$term->taxonomy.'_'.$term->term_id)):
							echo'<div class="editor-content main-content" id="more-tax-desc">';
								echo get_field('more_desc',$term->taxonomy.'_'.$term->term_id);
							echo'</div>';
						endif;   
						
						if(get_field('show_faqs',$term->taxonomy.'_'.$term->term_id)){		
						   websima_faqs($term , $title = 'true' );
						}
					}else{
						if(get_field('more_desc',$shop_page_id)):
							echo'<div class="editor-content main-content" id="more-tax-desc">';
								echo get_field('more_desc',$shop_page_id);
							echo'</div>';
						endif;   
						
						if(get_field('show_faqs',$shop_page_id)){		
						   websima_faqs($shop_page_id , $title = 'true' );
						}
					}
					?>
                </div>
            </div>
        </div>
    </main>
<?php
get_footer( 'shop' );

if(!is_shop()){ faq_schema($term);  }else{ faq_schema($shop_page_id); }
?>
