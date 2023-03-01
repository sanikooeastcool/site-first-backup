jQuery(document).ready(function($) {
    $.validator.addMethod('custommobile', function (value, element) {
        return this.optional(element) || /^[0][9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]$/.test(value);
    });

    //https://github.com/muhammadessarind/MaxLength
    /*jQuery("input[type=number]").keypress(function(event) {
        if (jQuery(this).val().length == jQuery(this).attr("maxlength")) {
            return false;
        }
    });*/

    jQuery("input[type=number]").on('input', function() {
        if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);
    });

    var loading_text = 'در حال بررسی اطلاعات ...';
    var user_mobile_required = 'لطفا شماره موبایل خود را وارد نمایید';
    var user_mobile_validation = 'شماره موبایل وارد شده معتبر نمی باشد';
    var user_verification_code_required = 'لطفا کد تایید خود را وارد نمایید';
    var user_verification_code_digits = 'کد تایید باید به صورت عددی باشد';
    var user_verification_code_length = 'طول کد تایید باید 6 رقم باشد';
    var user_password_required = 'لطفا رمز عبور خود را وارد نمایید';
    var user_password_digits = 'رمز عبور باید به صورت عددی باشد';
    var user_password_length = 'طول رمز عبور باید 6 رقم باشد';


    jQuery("#account_detection_form").validate({
        rules: {
            mobile: {
                required: true,
                custommobile: true
            }
        },

        messages: {
            mobile: {
                required: user_mobile_required,
                custommobile: user_mobile_validation
            }
        },
        submitHandler: function(form) {
            dataString = $("#account_detection_form").serialize();
            var account_detection = jQuery('#account_detection_form_wrapper');
            var account_login = jQuery('#account_login_form_wrapper');
            var account_register = jQuery('#account_register_form_wrapper');

            jQuery.ajax({
                type : "post",
                dataType: 'json',
                data: dataString,
                url : auth_user_dyn_data.admin_ajax,
                beforeSend : function(){
                    jQuery("#websima-auth-modal .modal-dialog").append('<div class="alert bg-primary">'+loading_text+'</div>');
                    $alert = jQuery("#websima-auth-modal").find('.alert');
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

                        var action = response.action;
                        if(action == 'login'){
                            if(response.strategy == 'otp'){ auth_timer('.resend_code_wrapper .resend-code[data-type="otp-password"]'); }
                            setTimeout(function(){
                                account_detection.fadeOut(750, function(){
                                    account_login.fadeIn(750);
                                });
                            }, 2500);
                        }
                        if(action == 'register'){
                            auth_timer('.resend_code_wrapper .resend-code[data-type="verification-code"]');
                            setTimeout(function(){
                                account_detection.fadeOut(750, function(){
                                    account_register.fadeIn(750);
                                });
                            }, 2500);
                        }
                    }
                },
                error:function(){alert('no');}
            })
        }
    });

    jQuery("#account_login_form").validate({
        rules: {
            password: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6
            }
        },

        messages: {
            password: {
                required: user_password_required,
                digits: user_password_digits,
                minlength: user_password_length,
                maxlength: user_password_length
            }
        },
        submitHandler: function(form) {
            dataString = $("#account_login_form").serialize();

            jQuery.ajax({
                type : "post",
                dataType: 'json',
                data: dataString,
                url : auth_user_dyn_data.admin_ajax,
                beforeSend : function(){
                    jQuery("#websima-auth-modal .modal-dialog").append('<div class="alert bg-primary">'+loading_text+'</div>');
                    $alert = jQuery("#websima-auth-modal").find('.alert');
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
                        setTimeout(function(){ jQuery('#websima-auth-modal').modal('toggle'); }, 2500);
                        setTimeout(function(){ window.location.reload(); }, 3000);
                    }
                },
                error:function(){
					window.location.reload();
				}
            })
        }
    });

    jQuery('.resend_code_wrapper').on('click','.resend-code:not(.disable)',function(){
        var type = jQuery(this).attr("data-type");
        var element = jQuery(this);
        if(type == 'otp-password'){ var again_text = 'ارسال مجدد رمز عبور'; }
        if(type == 'reset-password'){ var again_text = 'ارسال مجدد رمز عبور'; }
        if(type == 'verification-code'){ var again_text = 'ارسال مجدد کد تایید'; }

        jQuery.ajax({
            type : "post",
            dataType: 'json',
            data       : {
                action: "websima_auth_account_resend_code",
                type : type,
                nonce: auth_user_dyn_data.nonce,
            },
            url : auth_user_dyn_data.admin_ajax,
            beforeSend : function(){
                element.text(loading_text);
                element.addClass('disable');
            },
            success:function(response){
                if(response.status == 0){
                    element.text(response.msg);
                    setTimeout(function(){
                        element.text(again_text);
                        element.removeClass('disable');
                    }, 2000);
                }
                if(response.status == 1){
                    element.text(response.msg);

					setTimeout(function(){
                        //element.after('<div class="resend-code-timer"></div>');
						var countdownvalue = 60;
						var now = 0;
						var x = setInterval(function() {
							now = now + 1;
							var distance = countdownvalue - now;
							if (distance < 0) {
								clearInterval(x);
								element.removeClass('disable');
								element.text(again_text);
								//jQuery('.resend-code-timer').remove();
							}else{
								element.text(distance + ' ثانیه');
							}
						}, 1000);
                    }, 2000);
                }
            },
            error:function(){alert('no');}
        })
    });

    jQuery("#account_register_form").validate({
        rules: {
            verification_code: {
                required: true,
                digits: true,
                minlength: 6,
                maxlength: 6
            }
        },

        messages: {
            verification_code: {
                required: user_verification_code_required,
                digits: user_verification_code_digits,
                minlength: user_verification_code_length,
                maxlength: user_verification_code_length
            }
        },
        submitHandler: function(form) {
            dataString = $("#account_register_form").serialize();
            var account_register = jQuery('#account_register_form_wrapper');
            var account_profile = jQuery('#account_profile_form_wrapper');

            jQuery.ajax({
                type : "post",
                dataType: 'json',
                data: dataString,
                url : auth_user_dyn_data.admin_ajax,
                beforeSend : function(){
                    jQuery("#websima-auth-modal .modal-dialog").append('<div class="alert bg-primary">'+loading_text+'</div>');
                    $alert = jQuery("#websima-auth-modal").find('.alert');
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

                        if(response.extra_step == false){
                            setTimeout(function(){ jQuery('#websima-auth-modal').modal('toggle'); }, 2500);
                            setTimeout(function(){ window.location.reload(); }, 3000);
                        }else{
                            setTimeout(function(){
                                account_register.fadeOut(750, function(){
                                    account_profile.fadeIn(750);
                                });
                            }, 2500);
                        }
                    }
                },
                error:function(){alert('no');}
            })
        }
    });

    if(jQuery('#account_profile_form_wrapper').length){
        var profile_rules = auth_user_dyn_data.user_profile_rules;
        var profile_messages = auth_user_dyn_data.user_profile_messages;

        jQuery("#account_profile_form").validate({
            rules: profile_rules,
            messages: profile_messages,

            submitHandler: function(form) {
                dataString = $("#account_profile_form").serialize();

                jQuery.ajax({
                    type : "post",
                    dataType: 'json',
                    data: dataString,
                    url : auth_user_dyn_data.admin_ajax,
                    beforeSend : function(){
                        jQuery("#websima-auth-modal .modal-dialog").append('<div class="alert bg-primary">'+loading_text+'</div>');
						$alert = jQuery("#websima-auth-modal").find('.alert');
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

                            setTimeout(function(){ jQuery('#websima-auth-modal').modal('toggle'); }, 2500);
                            setTimeout(function(){ jQuery('#websima-auth-modal').modal('toggle'); }, 2500);
                            setTimeout(function(){ window.location.reload(); }, 3000);
                        }
                    },
                    error:function(){alert('no');}
                })
            }
        });
    }

    if(jQuery("body").hasClass("woocommerce-checkout")){
        if(jQuery('#websima-auth-modal').length){
            setTimeout(function(){ jQuery('#websima-auth-modal').fadeIn('slow'); }, 500);
        }
    }

    var auth_timer = function(element){
        var type = jQuery(element).attr("data-type");
        if(type == 'otp-password'){ var again_text = 'ارسال مجدد رمز عبور'; }
        if(type == 'reset-password'){ var again_text = 'ارسال مجدد رمز عبور'; }
        if(type == 'verification-code'){ var again_text = 'ارسال مجدد کد تایید'; }
        jQuery(element).addClass('disable');

        setTimeout(function(){
            //element.after('<div class="resend-code-timer"></div>');
            var countdownvalue = 60;
            var now = 0;
            var x = setInterval(function() {
                now = now + 1;
                var distance = countdownvalue - now;
                if (distance < 0) {
                    clearInterval(x);
                    jQuery(element).removeClass('disable');
                    jQuery(element).text(again_text);
                    //jQuery('.resend-code-timer').remove();
                }else{
                    jQuery(element).text(distance + ' ثانیه');
                }
            }, 1000);
        }, 2000);
    };
});