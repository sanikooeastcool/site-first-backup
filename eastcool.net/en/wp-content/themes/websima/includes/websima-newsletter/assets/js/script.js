jQuery(document).ready(function($) {
    if(jQuery('#newsletter-form').length){
        var loading_text = 'Sending information ...';
        var newsletter_email_required = 'Please enter your email';
        var newsletter_email_validation = 'The entered email is not valid';


        jQuery("#newsletter-form").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },

            messages: {
                email: {
                    required: newsletter_email_required,
                    email: newsletter_email_validation
                }
            },
            submitHandler: function(form) {
                dataString = $("#newsletter-form").serialize();

                jQuery.ajax({
                    type : "post",
                    dataType: 'json',
                    data: dataString,
                    url : newsletter_script_dyn_data.admin_ajax,
                    beforeSend : function(){
                        jQuery("#newsletter-form").append('<div class="alert bg-primary">'+loading_text+'</div>');
                        $alert = jQuery("#newsletter-form").find('.alert');
                    },
                    success:function(response){
                        if(response.status == 0){
                            $alert.removeClass('bg-danger bg-success bg-primary');
                            $alert.addClass('bg-danger');
                            $alert.text(response.msg);
                            setTimeout(function(){ $alert.fadeOut('slow', function(){ $alert.remove(); }); }, 2000);
                        }
                        if(response.status == 1){
                            $alert.removeClass('bg-danger bg-success bg-primary');
                            $alert.addClass('bg-success');
                            $alert.text(response.msg);
                            setTimeout(function(){ $alert.fadeOut('slow', function(){ $alert.remove(); }); }, 2000);
                        }
                    },
                    error:function(){
                        $alert.removeClass('bg-danger bg-success bg-primary');
                        $alert.addClass('bg-danger');
                        $alert.text('An error has occurred');
                        setTimeout(function(){ $alert.fadeOut('slow', function(){ $alert.remove(); }); }, 2000);
                    }
                })
            }
        });
    }
});