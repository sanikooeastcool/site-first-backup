jQuery( document ).ready(function() {
	var $height_ar = [];
    jQuery(".header-menu li.megatab .mega-tabmenu > li").each(function () {
		jQuery(this).find("> ul.sub-menu > li").each(function () {
			$height =jQuery(this).outerHeight();
			$height_ar.push($height);
		});
       getMaxValue = Math.max.apply(Math, $height_ar );
	});
	jQuery(".header-menu li.megatab").each(function () {
		getMaxValue=parseInt(getMaxValue)+20;
		jQuery(this).find('.mega-tabmenu').css({'min-height':''+ getMaxValue +'px'});
	});
});

jQuery(".header-menu > li.megatab").each(function () {
	$i=0;
	jQuery(this).mouseover(function() {
        jQuery(this).addClass("open-menu");
		$i++;
		if($i==1){
	    jQuery(this).parent().find(".mega-tabmenu li").first().addClass("active");
		}
    });
	jQuery(this).mouseleave(function() {
		$i=0;
        jQuery(this).removeClass("open-menu");		
		jQuery(this).parent().find(".mega-tabmenu li").first().removeClass("active");
    });
});
jQuery(".header-menu .megatab .mega-tabmenu > li").each(function () {
    jQuery(this).mouseover(function() {
		jQuery(this).parent().find('li.active').removeClass("active");
        jQuery(this).addClass("active");
    });
	jQuery(this).mouseleave(function() {
        jQuery(this).removeClass("active");
    });
});