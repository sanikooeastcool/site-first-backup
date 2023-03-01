<?php
define('THEME_URL', get_template_directory_uri());
define('THEME_PATH', get_template_directory());

/**
 * Creating Menu
 **/
add_action('after_setup_theme', 'websima_menus');
function websima_menus()
{
	register_nav_menus(array(
		'top'  => __('Top Menu', 'websima'),
		'mobile'  => __('Mobile Menu', 'websima'),
		'category-menu'  => __('Category Menu', 'websima'),
		'footer1'  => __('Footer 1 Menu', 'websima'),
		'footer2'  => __('Footer 2 Menu', 'websima'),
	));
}

/**
 * Proper way to enqueue scripts and styles
 **/

function websima_scripts()
{
	wp_enqueue_script("jquery");
	wp_enqueue_script('lightgalleryjs', THEME_URL . '/assets/js/lightgallery.js', array('jquery'), '2.0', true);

	wp_enqueue_script('customjs', THEME_URL . '/assets/js/custom.js', array('jquery'), '', true);


	$data = array(
		'url' => admin_url('admin-ajax.php')
	);
	wp_localize_script('customjs', 'ajax_data', $data);
	wp_enqueue_style('lightgallery', THEME_URL . '/assets/css/lightgallery.css');
	wp_dequeue_style('wp-block-library');
	wp_enqueue_style('style', THEME_URL . '/style.css');
	wp_enqueue_style('home', THEME_URL . '/assets/css/home.css');
	wp_enqueue_style('editor', THEME_URL . '/assets/css/editor.css');

	wp_enqueue_style('productcss', THEME_URL . '/assets/css/single-product.css');
	wp_enqueue_style('accountcss', THEME_URL . '/assets/css/myaccount.css');
}
add_action('wp_enqueue_scripts', 'websima_scripts');


function websima_admin_enqueue_scripts()
{
	wp_deregister_script('woo-tracks');
}
add_action('admin_enqueue_scripts', 'websima_admin_enqueue_scripts');

/**
 * Remove Admin Bar
 **/
add_filter('show_admin_bar', '__return_false');

/**
 * Remove version from head
 **/
remove_action('wp_head', 'wp_generator');

/**
 * Remove Gravity Forms Css
 **/

add_filter('pre_option_rg_gforms_disable_css', '__return_true');

/**

 * After setup theme

 **/

add_action('after_setup_theme', 'websima_theme_setup');
function websima_theme_setup()
{
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('woocommerce');
	add_theme_support('html5', array('style', 'script'));
	add_theme_support('wc-product-gallery-zoom');
	add_theme_support('wc-product-gallery-lightbox');
	add_theme_support('wc-product-gallery-slider');
	add_filter('use_default_gallery_style', '__return_false');
	add_editor_style(THEME_URL . '/assets/css/editor-style-panel.css');
	add_image_size('img_blog', 398, 480, true);
	add_image_size('img_product', 278, 358, true);
	add_image_size('img_categories', 94, 138, true);
	add_image_size('img_slider', 208, 139, true);
	add_image_size('img_lightgallery', 162, 162, true);
	/*
	add_image_size( 'img_album', 260,345,true ); */
}
/**
 * Clean Script Tag
 **/
if (!is_admin()) {
	add_filter('script_loader_tag', 'clean_script_tag');
}
function clean_script_tag($input)
{
	$input = str_replace("type='text/javascript' ", '', $input);
	return str_replace("'", '"', $input);
}
/**
 * Remove WP Version From Styles & Scripts
 **/
add_filter('style_loader_src', 'sdt_remove_ver_css_js', 9999);
add_filter('script_loader_src', 'sdt_remove_ver_css_js', 9999);
function sdt_remove_ver_css_js($src)
{
	if (strpos($src, 'ver='))
		$src = remove_query_arg('ver', $src);
	return $src;
}
/**
 * Disables Pesky Emojis
 **/
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

/** 
 * Disables Embeds 
 **/

function cb_disable_peskies_disable_embeds_rewrites($rules)
{
	foreach ($rules as $rule => $rewrite) {
		if (false !== strpos($rewrite, 'embed=true')) {
			unset($rules[$rule]);
		}
	}
	return $rules;
}

function cb_disable_peskies_disable_embeds_tiny_mce_plugin($plugins)
{
	return array_diff($plugins, array('wpembed'));
}
function cb_disable_peskies_disable_embeds_remove_rewrite_rules()
{
	add_filter('rewrite_rules_array', 'cb_disable_peskies_disable_embeds_rewrites');
	flush_rewrite_rules();
}
function cb_disable_peskies_disable_embeds_flush_rewrite_rules()
{
	remove_filter('rewrite_rules_array', 'cb_disable_peskies_disable_embeds_rewrites');
	flush_rewrite_rules();
}
function cb_disable_peskies_disable_embeds()
{
	// Remove the REST API endpoint.
	remove_action('rest_api_init', 'wp_oembed_register_route');
	// Turn off oEmbed auto discovery.
	add_filter('embed_oembed_discover', '__return_false');
	// Don't filter oEmbed results.
	remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
	// Remove oEmbed discovery links.
	remove_action('wp_head', 'wp_oembed_add_discovery_links');
	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action('wp_head', 'wp_oembed_add_host_js');
	add_filter('tiny_mce_plugins', 'cb_disable_peskies_disable_embeds_tiny_mce_plugin');
	// Remove all embeds rewrite rules.
	add_filter('rewrite_rules_array', 'cb_disable_peskies_disable_embeds_rewrites');
}
add_action('init', 'cb_disable_peskies_disable_embeds', 99);

register_activation_hook(__FILE__, 'cb_disable_peskies_disable_embeds_remove_rewrite_rules');

register_deactivation_hook(__FILE__, 'cb_disable_peskies_disable_embeds_flush_rewrite_rules');

/**
 * Search Filter
 **/
function SearchFilter($query)
{
	if (!is_admin()) :
		if ($query->is_search) {
			if (isset($_GET['post_type'])) {
				$query->set('post_type', array('product'));
			} else {
				$query->set('post_type', array('post'));
			}
		}
		return $query;
	endif;
}
add_filter('pre_get_posts', 'SearchFilter');

/**
 * Custom Excerpt Content Function By Limit
 **/


function excerpt_content($limit)
{
	$excerpt = explode(' ', get_the_content(), $limit);
	if (count($excerpt) >= $limit) {
		array_pop($excerpt);
		$excerpt = implode(" ", $excerpt) . ' ... ';
	} else {
		$excerpt = implode(" ", $excerpt);
	}
	$excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
	return $excerpt;
}

/**
 * Move Textarea Comment to End of Form
 **/
function wpb_move_comment_field_to_bottom($fields)
{
	$comment_field = $fields['comment'];
	unset($fields['comment']);
	$fields['comment'] = $comment_field;
	return $fields;
}
add_filter('comment_form_fields', 'wpb_move_comment_field_to_bottom');
/**
 * Enqueue JS Ajax Form Comment
 **/
function mytheme_enqueue_comment_reply()
{
	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_comment_reply');

/**
 * Light Gallery In Editor Content
 **/
add_filter('post_gallery', 'ct_post_gallery', 10, 2);
function ct_post_gallery($output, $attr)
{
	global $post;
	if (isset($attr['orderby'])) {
		$attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
		if (!$attr['orderby'])
			unset($attr['orderby']);
	}
	extract(shortcode_atts(array(
		'order' => 'ASC',
		'orderby' => 'menu_order ID',
		'id' => $post->ID,
		'itemtag' => 'dl',
		'icontag' => 'dt',
		'captiontag' => 'dd',
		'columns' => 3,
		'size' => 'thumbnail',
		'include' => '',
		'exclude' => ''
	), $attr));
	$id = intval($id);
	if ('RAND' == $order) $orderby = 'none';
	if (!empty($include)) {
		$include = preg_replace('/[^0-9,]+/', '', $include);
		$_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
		$attachments = array();
		foreach ($_attachments as $key => $val) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	}
	if (empty($attachments)) return '';
	// Here's your actual output, you may customize it to your need
	$output .= "<div class=\"owl-carousel owl-gallery page_lightgallery\">\n";
	// Now you loop through each attachment
	foreach ($attachments as $id => $attachment) {
		// Fetch the thumbnail (or full image, it's up to you)
		$full = wp_get_attachment_image_src($id, 'full');
		// $img = wp_get_attachment_image_src($id, 'my-custom-image-size');
		$img = wp_get_attachment_image_src($id, 'pos');
		$image_alt = get_post_meta($id, '_wp_attachment_image_alt', true);
		$attachment_title = get_the_title($id);
		$output .= "<div class=\"gallery_item\" data-src=\"{$full[0]}\" >\n";
		$output .= "<a href=\"{$full[0]}\" data-sub-html='.caption'>";
		$output .= "<div class='caption' style='display:none'><h4>" . $attachment_title . "</h4><p>" . get_the_excerpt($id) . "</p></div>";
		$output .= "<img src=\"{$img[0]}\" width=\"{$img[1]}\" height=\"{$img[2]}\" alt=\"{$image_alt}\" loading='lazy' /></a>\n";
		$output .= "</div>\n";
	}
	$output .= "</div>\n";
	return $output;
}
/**
 * Convert Input Submit To Button Submit
 **/
function awesome_comment_form_submit_button($button)
{
	$button = "<button id='submit' class='button'>send</button>";
	return $button;
}
add_filter('comment_form_submit_button', 'awesome_comment_form_submit_button');

add_filter('gform_submit_button', 'form_submit_button', 10, 2);
function form_submit_button($button, $form)
{
	return "<button class='button gform_button' id='gform_submit_button_{$form['id']}'><span>" . $form['button']['text'] . "</span></button>";
}

/**
 * Blog Schema Function
 **/
if (!function_exists('blog_schema')) {
	function blog_schema($id)
	{
		$poster  = get_post($id);
		$title   = get_the_title($id);
		$logo    = get_field('logo', 'option');
		$content = $poster->post_content;
		// we need a expression to match things
		$regex = '/src="([^"]*)"/';
		// we want all matches
		preg_match_all($regex, $content, $matches);
		// reversing the matches array
		$images = array_reverse($matches);
		echo '<script type="application/ld+json">

		{

		  "@context": "http://schema.org",

		  "@type": "Article",

		  "mainEntityOfPage": {

			"@type": "WebPage",

			"@id": "' . get_permalink($id) . '"

		  },

		  "headline": "' . $title . '",

		  "image": ["' . get_the_post_thumbnail_url($id, 'large') . '"';

		foreach ($images[0] as $image) {

			echo ',"' . $image . '"';
		}

		echo '],';

		echo '"description": "' . get_the_excerpt($id) . '",

		  "datePublished": "' . str_replace(' ', 'T', $poster->post_date_gmt) . '+03:30",

		  "dateModified": "' . str_replace(' ', 'T', $poster->post_modified_gmt) . '+03:30",

		  "author": {

			"@type": "Person",

			"name": "' . get_bloginfo('name') . '"

		  },

		  "publisher": {

			"@type": "Organization",

			"name": "' . get_bloginfo('name') . '",

			"sameAs": "' . get_bloginfo('url') . '",

			"logo": {

			  "@type": "ImageObject",

			  "url": "' . wp_get_attachment_url($logo) . '"

			}

		  }';

		echo '}';

		echo '</script>';
	}
}

/**
 * FAQ with Category Schema Function
 **/
if (!function_exists('faqcat_schema')) {

	function faqcat_schema()
	{
		$faqs = get_field('faqs_cat');
		if ($faqs != null) {
			$allcount_faq = 0;
			foreach ($faqs as $faq) {
				$allcount_faq += count(array($faq['faqs']));
			}

			echo '<script type="application/ld+json">

			{

			  "@context": "https://schema.org",

			  "@type": "FAQPage",

			  "mainEntity": [';

			foreach ($faqs as $faq) :

				$faq_items = $faq['faqs'];

				if ($faq_items) :

					$i = 0;

					foreach ($faq_items as $faq_item) :

						$i++;

						$allcount_faq--;



						echo ' {

				"@type": "Question",

				"name": "' . $faq_item['question'] . '",

				"acceptedAnswer": {

				  "@type": "Answer",

				  "text": "' . str_replace('"', '\'', strip_tags(trim($faq_item['answer']), '<a><p><ul><li><b><ol>')) . '"



				}

			  }';

						if (0 != $allcount_faq) echo ',';

					endforeach;

				endif;

			endforeach;

			echo ']

			}

			</script>';
		}
	}
}

/**
 * FAQ without category Schema Function
 **/

if (!function_exists('faq_schema')) {

	function faq_schema($term = null)
	{
		$faqs = get_field('faqs');
		if ($term != null) {
			$faqs = get_field('faqs', $term);
		}
		if (get_sub_field('faqs')) {
			$faqs = get_sub_field('faqs');
		}

		if ($faqs != null) {

			$count_faq = count($faqs);

			echo '<script type="application/ld+json">

			{

			  "@context": "https://schema.org",

			  "@type": "FAQPage",

			  "mainEntity": [';

			$i = 0;

			foreach ($faqs as $key => $val) {

				$i++;

				echo '{

				"@type": "Question",

				"name": "' . $val['question'] . '",

				"acceptedAnswer": {

				  "@type": "Answer",

				  "text": "' . str_replace('"', '\'', strip_tags(trim($val['answer']), '<a><p><ul><li><b><ol>')) . '"



				}

			  } ';

				if ($i != $count_faq) echo ',';
			}



			echo ']

			}

            </script>';
		}
	}
}

/**
 * Remove Class RTL From Body
 **/
function ja_remove_body_classes($wp_classes)
{
	if (!is_admin()) :
		$blacklist = array('rtl');
		$wp_classes = array_diff($wp_classes, $blacklist);
		return $wp_classes;
	endif;
}
add_filter('body_class', 'ja_remove_body_classes', 10, 2);
/**
 * Remove Comment Cookies
 **/
remove_action('set_comment_cookies', 'wp_set_comment_cookies');
/**
 * Add Custom Class to Body In Inner Pages
 **/
add_filter('body_class', function ($classes) {
	if (!is_front_page()) {
		return array_merge($classes, array('inner-page'));
	} else {
		return array_merge($classes);
	}
});



/**
 * Add Sidebar for blog
 **/
register_sidebar(array(
	'name' => 'sidebar blog',
	'id' => 'sidebar_blog',
	'before_widget' => '<div id="%1$s" class="widget widget-side mb-4 %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<div class="widget-title"><h4>',
	'after_title' => '</h4></div>',
));

/**
 * Create Themeoption for general setting 
 **/
add_action('acf/init', 'websima_theme_op_init');
function websima_theme_op_init()
{
	if (function_exists('acf_add_options_page')) {
		$option_page = acf_add_options_page(array(
			'page_title'    => __('General Theme Settings'),
			'menu_title'    => __('Theme Settings'),
			'menu_slug'     => 'theme-general-settings',
			'capability'    => 'edit_posts',
			'redirect'      => false
		));
	}
	if (function_exists('acf_add_options_page')) {
		acf_add_options_page(array(
			'page_title' => 'Dedicated settings',
			'menu_title' => 'Dedicated settings',
			'menu_slug' => 'websima-package-general-settings',
			'capability' => 'edit_posts'
		));
	}
}

/**
 * disable colorpicker of acfform  in contact page(contact-form)
 **/
add_action('wp_print_scripts', 'pp_deregister_javascript', 99);
function pp_deregister_javascript()
{
	if (!is_admin()) {
		wp_dequeue_script('wp-color-picker');
		wp_deregister_script('wp-color-picker-js-extra');
		wp_deregister_script('wp-color-picker');
	}
}
/* Customize title of archive*/
add_filter('get_the_archive_title', function ($title) {
	if (is_category()) {
		$title = single_cat_title('', false);
	} elseif (is_tag()) {
		$title = single_tag_title('', false);
	} elseif (is_tax()) {
		$title = sprintf(__('%1$s'), single_term_title('', false));
	} elseif (is_post_type_archive()) {
		$title = post_type_archive_title('', false);
	}
	return $title;
});

function websima_after_setup_theme_2()
{
	remove_theme_support('widgets-block-editor');
}
add_action('after_setup_theme', 'websima_after_setup_theme_2');


// custom css and js
add_action('admin_enqueue_scripts', 'cstm_css_and_js');

function cstm_css_and_js()
{
	// your-slug => The slug name to refer to this menu used in &quot;add_submenu_page&quot;
	// tools_page => refers to Tools top menu, so it's a Tools' sub-menu page
	$screen = get_current_screen();
	if ($screen->id == "edit-shop_order") {
		wp_enqueue_style('productcss', THEME_URL . '/assets/css/persianDatepicker.css');
		wp_enqueue_script('persianDatePickerjs', THEME_URL . '/assets/js/persianDatepicker.js', array('jquery'), '0.1.0', true);
	}
}
