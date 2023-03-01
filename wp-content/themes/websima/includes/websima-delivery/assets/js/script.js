jQuery(document).ready(function(){
    jQuery('#delivery_day').on('change', function() {
        var day = this.value;
        if(day != 0){
            jQuery.ajax({
                type : "post",
                dataType: 'json',
                data       : {
                    action: "websima_delivery_time_options",
                    day : day
                },
                url : woocommerce_params.ajax_url,
                beforeSend : function(){
                    $delivery_time_field = jQuery("#delivery_time");
                    $delivery_time_field.prop('disabled', true);
                },
                success:function(response){
                    if(response.status == 0){
                        $delivery_time_field.empty();
                    }
                    if(response.status == 1){
                        $delivery_time_field.empty();
                        $delivery_time_field.prop('disabled', false);
                        if(response.times){
                            jQuery.each(response.times, function(key, value) {
                                $delivery_time_field.append('<option value="'+key+'">'+value+'</option>');
                            });
                        }
                    }
                },
                error:function(){alert('no');}
            });
        }
    });
});