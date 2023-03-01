<?php 

function websima_menu_scripts() {
    wp_enqueue_style( 'menu.css', get_template_directory_uri().'/includes/websima-menu/assets/menu.css');
    wp_enqueue_script( 'menu.js', get_template_directory_uri().'/includes/websima-menu/assets/menu.js', array(), '1.0.0',true);
}
add_action( 'wp_enqueue_scripts', 'websima_menu_scripts' );

// mega menu walker
class megaMenuWalker extends Walker_Nav_Menu {
	private $curItem;
	public $stack = array();
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $item_id = $item->ID;
        $this->curItem = $item;
		if ($depth == 0 && get_field('type_menu', $item_id)=='mega_tab') {
			$class_names = join(" megatab ", apply_filters("nav_menu_css_class", array_filter($classes), $item));
		}elseif($depth == 0 && get_field('type_menu', $item_id)=='mega_simple'){
			$class_names = join(" mega-menu ", apply_filters("nav_menu_css_class", array_filter($classes), $item));
		}else{
			$class_names = join(" ", apply_filters("nav_menu_css_class", array_filter($classes), $item));
		}
        $class_names = " class=\"" . esc_attr($class_names) . "\"";
		if(get_field('has_nolink', $item_id)){
			$output .= sprintf(
            "<li id=\"menu-item-%s\"%s><span>".$item->title."</span>",
            $item_id,
            $class_names,
            $item->url,
            $item->title
        );
		}else{
			$output .= sprintf(
            "<li id=\"menu-item-%s\"%s><a href=\"%s\">%s</a>",
            $item_id,
            $class_names,
            $item->url,
            $item->title
        );
		}
        

    }
    function start_lvl(&$output, $depth = 0, $args = array()) {
		$item = $this->curItem;
		if ($depth == 0 && get_field('type_menu', $item->ID)=='mega_tab') {			
		    array_push($this->stack, $item->ID);			
            $output .= "<ul class=\"level-".$depth." sub-menu\"><li class=\"menu-item\"><ul class=\"mega-tabmenu sub-menu\">";
        }elseif ($depth == 0 && get_field('type_menu', $item->ID)=='mega_simple' && get_field('image_mega', $item->ID)) {	
		$img = get_field('image_mega', $item->ID);
		$output .= "<ul class=\"sub-menu\">";
		$output .= "<img class='mega-image' src='".$img['url']."' width=".$img['width']." height=".$img['height'].">";
		}else{
            $output .= "<ul class=\"sub-menu\">";
		}
    }
    function end_lvl(&$output, $depth = 0, $args = array()) {	
	    $item = $this->curItem;		
		if ($depth == 0){		
			if (!empty($this->stack)) {
				foreach($this->stack as $value){
					if (get_field('type_menu', $value)=='mega_tab') {
						$key = array_search($value, $this->stack);
						unset($this->stack[$key]);
						$output .= "</ul></li></ul>";
					}else{
						$output .= "</ul>";
					}
				}
			}else{
				$output .= "</ul>";
			}
		}else{
			$output .= "</ul>";
		}
    }
    function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		$output .= "</li>";
    }
}

add_action('after_setup_theme', 'websima_megamenus');
function websima_megamenus(){
	register_nav_menus( array(
		'category-menu'  => __( 'Category Menu', 'websima' ),
	) );
}
function websima_custom_menu(){
	if (has_nav_menu('category-menu')){
		wp_nav_menu(array(
			'theme_location' => 'category-menu',
			'container'  => 'nav',
			'container_class'  => 'wrap-menu',
			'menu_class' => 'header-menu',
			'walker'  => new megaMenuWalker()
		));
	}
}
