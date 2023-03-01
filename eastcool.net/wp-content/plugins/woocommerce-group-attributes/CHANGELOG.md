# Changelog
======
1.7.2
======
- FIX:	More text not shown when ONLY dimensions or weight enabled

======
1.7.1
======
- NEW: 	Option to Enable Frontend (disable that to only use backend functionality)
- FIX:	Accordion switched icons

======
1.7.0
======
- NEW:	Accordion functionality for all 4 layouts:
		https://imgur.com/a/FzLOTJa

======
1.6.1
======
- NEW:	Filter for attribute group name:
		woocommerce_group_attributes_group_title
		apply_filters( 'woocommerce_group_attributes_group_title', $attribute_group->post_title, $attribute_group, $product);
- FIX:	PHP Notice

======
1.6.0
======
- NEW:	Attribute groups can now be assigned to product categories
		Logic:
		- if product categories in attribute group categories -> show
		- if attribute group has no product categories -> show (default fallback for existing attribute groups)

======
1.5.4
======
- NEW:	Attribute group categories now display in submenu under products wp-admin menu

======
1.5.3
======
- NEW:	Added woocommerce_display_product_attributes filter support
- NEW:	Option to hide / show weight or dimensions
- NEW:	Option to set a custom text for "more" attribute group
- FIX:	Weight & Dimensions now grouped in more
- FIX:	Weight & Dimensions missing from layout 4

======
1.5.2
======
- FIX:	Added ob output buffer to shortcode

======
1.5.1
======
- NEW:	Shortcode implemented
		See: https://welaunch.io/plugins/woocommerce-group-attributes/faq/shortcode/
- FIX:	Outdated template issue
- FIX:	PHP notices

======
1.5.0
======
- NEW:	Group Style 4 - basically this is splitting up groups into columns (columns can be set by in settings)
		https://welaunch.io/plugins/woocommerce-group-attributes/product/grouped-attributes-style-4/
- FIX:	Styling issues

======
1.4.2
======
- FIX:	Fixed 3 PHP notices

======
1.4.1
======
- FIX:	Added ASYNC false to AJAX requests to avoid wrong attribute load order

======
1.4.0
======
- NEW:	Attribute group categories
		Attribute group categories can contain multiple attribute groups. 
		These categories can be loaded in the backend when you edit a product. 
		To create categories, simply edit a attribute group and you will see categories in the right sidebar.

======
1.3.7
======
- FIX:	CSS styling issue

======
1.3.6
======
- FIX:	Additonal Woo 3.6 support

======
1.3.5
======
- FIX:	WooCommerce 3.6 support

======
1.3.4
======
- FIX:	Removed TGM Plugin
- FIX:	Multiple Attributes in Groups not showing in Layout 1 only
- FIX:	Removed woocommerce-general CSS dependency

======
1.3.3
======
- FIX:	Layout 3 ":" missing when attribute images plugin not active

======
1.3.2
======
- FIX:	Added support for attribute images plugin latest version

======
1.3.1
======
- FIX:	Attribute order was not correct in layout 3

======
1.3.0
======
- NEW:  Added custom Class to attribute rows so you can hide them via custom CSS
- FIX:  Weight & Dimensions did not displayed correctly
- FIX:  Moved Weight & Dimensions to Bottom

======
1.2.9
======
- NEW:  Attribute image exchange
- FIX:	Fixed Attribute Value Divider

======
1.2.8
======
- NEW:  Added support for Attribute Images Plugin
- FIX:  Mobile / Responsive Issues

======
1.2.7
======
- NEW:  Attribute Archive link introduced
- FIX:  When attribute group in backend is loaded it will respect the sort order
- FIX:  CSS issues with right text align
- FIX:  Updated Attribute Layout to latest WooCommerce Standards

======
1.2.6
======
- FIX:  only load select2 while adding / editing a attribute group

======
1.2.5
======
- NEW:  Load attributes from attribute groups inside product editing
		Demo Video: https://youtu.be/w8ChQ_FfYEE

======
1.2.4
======
- NEW: 	Sort attributes in an attribute Group
		Demo Video: https://youtu.be/KxQK76Ldx8g

======
1.2.3
======
- NEW: custom attributes are stored in "More"-Group
- FIX: custom attributes did not show up
- FIX: ksort issue

======
1.2.2
======
- FIX: PHP Notice

======
1.2.1
======
- FIX: Removed the check for the meta box class exists
- FIX: Adjusted TGM Init

======
1.2.0
======
- NEW:  Removed Meta Box Plugin and replaced by native Code
		You can now remove the meta box plugin!
- FIX: WPML Support

======
1.1.3
======
- NEW: Improved WPML Support

======
1.1.2
======
- FIX: undefined variable: img

======
1.1.1
======
- NEW: New Documentation & Examples
- NEW: Improved Layout and HTML structure
- FIX: Various structure

======
1.1.0
======
- NEW: Set an order for your Attribute Groups
- FIX: Multiple Attributes in Groups
- FIX: Attributes not in Group will now display before Groups

======
1.0.13
======
- FIX: get_dimensions was called incorrectly

======
1.0.12
======
- FIX: PHP Notice:  WC_Product::enable_dimensions_display is deprecated
- FIX: "PHP Notice: id was called incorrectly"

=====
1.0.11
======
- FIX: Performance issue

=====
1.0.10
======
- FIX: Activation error
- FIX: Language file

=====
1.0.9
======
- FIX: Meta Boxes heavy ajax performance issue

=====
1.0.8
======
- FIX: Meta Boxes 4.8 compatible

=====
1.0.7
======
- NEW: Better plugin activation
- FIX: Better advanced settings page (ACE Editor for CSS and JS )
- FIX: array key exists

=====
1.0.6
======
- FIX: Redux Error

=====
1.0.5
======
- NEW: Removed the embedded Redux Framework AND Meta Boxes for update consistency
//* PLEASE MAKE SURE YOU INSTALL THE REDUX FRAMEWORK & Meta Box PLUGIN *//

=====
1.0.4
======
- FIX: visisble attribute bug
- FIX: show real Attribute Names in backend

=====
1.0.3
======
- FIX: end of file bug

======
1.0.2
======
- FIX: for all PHP versions below 5.4

======
1.0.1
======
- FIX: attributes will now be stores with their attribute ID (means: you can change the slug, attribute name etc and do not have to touch the attribute group)
- NEW: you can now choose if you want to have one attribute in more than one attribute group.

======
1.0.0
======
- Inital release