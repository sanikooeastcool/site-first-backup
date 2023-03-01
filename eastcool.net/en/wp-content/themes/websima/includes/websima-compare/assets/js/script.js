jQuery('.compare-list .value-box').hover(function() {
    jQuery(this).addClass('hovered');
    var parentid = jQuery(this).data('parentid');
    var datatext = jQuery(this).find(".value").text();
    jQuery("[data-parentid='" + parentid + "']").each(function(){
        var comptext = jQuery(this).find(".value").text();
        if (datatext === comptext) {
            jQuery(this).addClass('hovered');
        }
    });
}, function() {
    jQuery(".compare-list .value-box").removeClass('hovered');
});
var owl_item_count = jQuery('.owl-carousel-compare').attr('data-count');
jQuery(".value-box-empty").each(function(){
    var parents = jQuery(this).data('parentid');
    var group_id = jQuery(this).data('group-id');
    var i = 0;
    jQuery('[data-parentid="'+parents+'"][data-group-id="'+group_id+'"]').each(function(){
        if(jQuery(this).hasClass('value-box-empty')) {
            i = i + 1;
        }
    });
    if (i == owl_item_count) {
        jQuery('[data-parentid="'+parents+'"][data-group-id="'+group_id+'"]').each(function(){
            jQuery(this).remove();
        });
        res = parents.replace("parent_", "");
        jQuery('[data-id="'+res+'"][data-group-id="'+group_id+'"]').remove();
    }
    jQuery('.value-box-empty').html('<div class="value">-</div>');
});
var box_title_var_height = jQuery('.compare-list .value-box').outerHeight();
var box_title_var_height = box_title_var_height + 60;
//jQuery('.value-box-empty').css('height', box_title_var_height);
jQuery( window ).on('load', function() {
    jQuery(".compare-box .owl-item:first .compare-list .title-box").each(function() {
        var data = jQuery(this).data('id');
        var group_id = jQuery(this).data('group-id');
        var parentid = 'parent_'+data;
        var sheight = 0;
        jQuery("[data-parentid='" + parentid + "'][data-group-id='" + group_id + "']").each(function() {
            height = jQuery(this).height();
            if (height > sheight) {sheight = height;}
        });
        jQuery("[data-parentid='" + parentid + "'][data-group-id='" + group_id + "']").each(function() {
            jQuery(this).height(sheight);
        });
    });

    jQuery(".compare-box .owl-item:first .compare-list .title-box").each(function() {
        var data_title_attr = jQuery(this).data('id');
        var data_title_group_attr = jQuery(this).data('group-id');

        var position = jQuery(this).position();
        var positiontop = position.top;
        //alert("Top position: " + position.top);
        jQuery(".compare-list-titles .title-box[data-id='" + data_title_attr + "'][data-group-id='" + data_title_group_attr + "']").each(function() {
            jQuery(this).css('top', positiontop + 50 );
        });
    });

    jQuery(".compare-list-titles .title-box").each(function(){
        if(jQuery(this).hasClass('title-box-group')){
            var group_id = jQuery(this).data('id');
            var group_title = jQuery(this).text();
            jQuery('.compare-list-titles [data-group-id="'+group_id+'"]').first().prepend('<span>'+group_title+'</span>');
        }
    });

    jQuery(".compare-box .compare-item").each(function() {

        var compare_id = jQuery(this).attr('data-id');
        jQuery(".compare-box .compare-item[data-id='" + compare_id + "'] .checkbox_similar").each(function() {
            jQuery(this).on('click',function () {


                if (jQuery(this).is(':checked')) {
                    jQuery(".compare-box .compare-item .value-box").each(function() {
                        jQuery(this).removeClass('equivalent');
                    });
                    jQuery(".compare-box .compare-item .checkbox_similar").each(function() {
                        if (jQuery(this).is(':checked')) {
                            jQuery(this).prop('checked',false);
                        }
                    });
                    jQuery(this).prop('checked',true);

                    jQuery(".compare-box .compare-item[data-id='" + compare_id + "'] .value-box").each(function() {
                        jQuery(this).addClass('equivalent');
                        var parentid = jQuery(this).data('parentid');
                        var datatext = jQuery(this).find(".value").text();
                        jQuery("[data-parentid='" + parentid + "']").each(function(){
                            var comptext = jQuery(this).find(".value").text();
                            if (datatext === comptext) {
                                jQuery(this).addClass('equivalent');
                            }
                        });
                    });
                }else {

                    jQuery(".compare-box .compare-item .value-box").each(function() {
                        jQuery(this).removeClass('equivalent');
                    });

                }

            });
        });

    });

});


jQuery('.compare-remove').click(function() {
    var compare_page_link = wbcomp_script_data.compare_page_url;
    var unique_id = wbcomp_script_data.unique_id;
    var compare_list = unique_id+'_compare_list';
    var compare_list_temp = [];

    if(localStorage.getItem(compare_list)) {
        compare_list_temp = localStorage.getItem(compare_list).split(',');
    }

    var remove_compare_id = jQuery(this).attr('data-id');
    if(jQuery.inArray( remove_compare_id , compare_list_temp ) != -1 ) {
        var removeItem = remove_compare_id;
        compare_list_temp = jQuery.grep(compare_list_temp, function(value) {
            return value != removeItem;
        });
    }
    localStorage.setItem(compare_list, compare_list_temp);

    if(compare_list_temp.length > 0 ) {
        jQuery('.compare-link').attr('href', compare_page_link+'?compare_list='+compare_list_temp);
        if(jQuery('.compare-link .count').length == 0){
            jQuery('.compare-link').append("<em class='count'></em>");
        }
        jQuery('.compare-link .count').text(compare_list_temp.length);
        window.location.href = compare_page_link+'?compare_list='+compare_list_temp;
    }else{
        jQuery('.compare-link').attr('href', compare_page_link);
        jQuery('.compare-link .count').remove();
        window.location.href = compare_page_link;
    }
});



jQuery('.owl-carousel-compare').owlCarousel({
    rtl:true,
    items:3,
    loop:false,
    nav:false,
    autoplay: false,
    margin:0,
    responsiveClass:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:3
        }
    }
});

jQuery('.compare-back-top .icon-wrapper').bind("click", function () {
    jQuery('html, body').animate({ scrollTop: 0 }, 1200);
    return false;
});