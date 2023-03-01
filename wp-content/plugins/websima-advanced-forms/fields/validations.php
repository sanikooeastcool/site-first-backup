<?php
add_filter('acf/validate_value/type=phone', 'wfb_acf_phone_validation', 10, 4);
function wfb_acf_phone_validation($valid,$value,$field,$input){
    if($value){
        if(strlen($value) == '11'){
            if(is_numeric($value)){
                if(preg_match( '/^0[1-9]{1}[0-9]{9}$/', $value )){
                    return $valid;
                }else{
                    return esc_html($field['label']).' وارد شده معتبر نمی باشد';
                }
            }else{
                return esc_html($field['label']).' باید به صورت عددی وارد شود';
            }
        }else{
            return 'طول '.esc_html($field['label']).' باید 11 کاراکتر باشد';
        }
    }
}

add_filter('acf/validate_value/type=mobile', 'wfb_acf_mobile_validation', 10, 4);
function wfb_acf_mobile_validation($valid,$value,$field,$input){
    if($value){
        if(strlen($value) == '11'){
            if(is_numeric($value)){
                if(preg_match( '/^09[0-9]{9}$/', $value )){
                    return $valid;
                }else{
                    return esc_html($field['label']).' وارد شده معتبر نمی باشد';
                }
            }else{
                return esc_html($field['label']).' باید به صورت عددی وارد شود';
            }
        }else{
            return 'طول '.esc_html($field['label']).' باید 11 کاراکتر باشد';
        }
    }
}

function wfb_national_code_validation( $code ) {
    if ( ! preg_match( '/^[0-9]{10}$/', $code ) ) {
        return false;
    }
    for ( $i = 0; $i < 10; $i ++ ) {
        if ( preg_match( '/^' . $i . '{10}$/', $code ) ) {
            return false;
        }
    }
    for ( $i = 0, $sum = 0; $i < 9; $i ++ ) {
        $sum += ( ( 10 - $i ) * intval( substr( $code, $i, 1 ) ) );
    }
    $ret    = $sum % 11;
    $parity = intval( substr( $code, 9, 1 ) );
    if ( ( $ret < 2 && $ret == $parity ) || ( $ret >= 2 && $ret == 11 - $parity ) ) {
        return true;
    }

    return false;
}

add_filter('acf/validate_value/type=national_code', 'wfb_acf_national_code_validation', 10, 4);
function wfb_acf_national_code_validation($valid,$value,$field,$input){
    if($value){
        if(wfb_national_code_validation($value)){
            return $valid;
        }else{
            return esc_html($field['label']).' وارد شده معتبر نمی باشد';
        }
    }
}