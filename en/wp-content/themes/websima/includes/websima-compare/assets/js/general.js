jQuery(document).ready(function(){
    var compare_page_link = wbcomp_general_data.compare_page_url;
    var tooltip_in = wbcomp_general_data.tooltip_in;
    var tooltip_out = wbcomp_general_data.tooltip_out;
    var unique_id = wbcomp_general_data.unique_id;
    var compare_list = unique_id+'_compare_list';
    var compare_list_temp = [];

    if(localStorage.getItem(compare_list)) {
        compare_list_temp = localStorage.getItem(compare_list).split(',');
    }


    if(compare_list_temp.length > 0 ) {
        jQuery('.compare-link').attr('href', compare_page_link+'?compare_list='+compare_list_temp);
        if(jQuery('.compare-link .count').length == 0){
            jQuery('.compare-link').append("<em class='count'></em>");
        }
        jQuery('.compare-link .count').text(compare_list_temp.length);

        jQuery.each( compare_list_temp, function( i, value ) {
            jQuery('.compare-btn[data-id="'+value+'"]').addClass('added');
            jQuery('.compare-btn[data-id="'+value+'"]').attr('title',tooltip_in);
        });
    }


    jQuery('.compare-btn').click(function() {
        var compare_list_temp = [];
        if(localStorage.getItem(compare_list)) {
            compare_list_temp = localStorage.getItem(compare_list).split(',');
        }
        var compare_id = jQuery(this).attr('data-id');
        if(jQuery.inArray( compare_id , compare_list_temp ) != -1 ) {
            var removeItem = compare_id;
            compare_list_temp = jQuery.grep(compare_list_temp, function(value) {
                return value != removeItem;
            });
            jQuery(this).removeClass('added');
            jQuery(this).attr('title',tooltip_out);
        } else {
            compare_list_temp.push(compare_id);
            jQuery(this).addClass('added');
            jQuery(this).attr('title',tooltip_in);
        }
        localStorage.setItem(compare_list, compare_list_temp);

        if(compare_list_temp.length > 0 ) {
            jQuery('.compare-link').attr('href', compare_page_link+'?compare_list='+compare_list_temp);
            if(jQuery('.compare-link .count').length == 0){
                jQuery('.compare-link').append("<em class='count'></em>");
            }
            jQuery('.compare-link .count').text(compare_list_temp.length);
        }else{
            jQuery('.compare-link').attr('href', compare_page_link);
            jQuery('.compare-link .count').remove();
        }
    });
});


