(function( $ ) {
	'use strict';

	$(document).ready(function() {

		$(document).on('click', '.woocommerce-group-attributes-accordion-name', function(e) {
			e.preventDefault();
			var $this = $(this);

			if($this.hasClass('woocommerce-group-attributes-accordion-name-open')) {
				$this.removeClass('woocommerce-group-attributes-accordion-name-open');
				$this.find('.woocommerce-group-attributes-icon').removeClass('fa-minus').addClass('fa-plus');

				var values = $this.next('.woocommerce-group-attributes-accordion-values');
				if(values.length > 0) {
					values.first().removeClass('woocommerce-group-attributes-accordion-values-open');	
				} else {
					values = $this.parent().find('.woocommerce-group-attributes-accordion-values');
					values.first().removeClass('woocommerce-group-attributes-accordion-values-open');	
				}
				
			} else {
				$this.addClass('woocommerce-group-attributes-accordion-name-open');
				$this.find('.woocommerce-group-attributes-icon').addClass('fa-minus').removeClass('fa-plus');

				var values = $this.next('.woocommerce-group-attributes-accordion-values');
				if(values.length > 0) {
					values.first().addClass('woocommerce-group-attributes-accordion-values-open');	
				} else {
					values = $this.parent().find('.woocommerce-group-attributes-accordion-values');
					values.first().addClass('woocommerce-group-attributes-accordion-values-open');	
				}
			}

		});

	} );

})( jQuery );