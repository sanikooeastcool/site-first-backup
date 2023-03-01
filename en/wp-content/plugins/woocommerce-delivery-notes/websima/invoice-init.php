<?php
add_filter('woocommerce_settings_tabs_array', 'websima_invoice_woocommerce_settings_tabs_array', 1000, 1);
function websima_invoice_woocommerce_settings_tabs_array($tabs_array)
{
    unset($tabs_array['wcdn-settings']);

    return $tabs_array;
}

add_action('init', 'websima_invoice_init');
function websima_invoice_init()
{
    update_option('wcdn_template_type_invoice', 'yes');
    update_option('wcdn_template_type_delivery-note', 'yes');
    update_option('wcdn_print_button_on_my_account_page', 'yes');
    if (function_exists('acf_add_options_page')) {
        acf_add_options_sub_page(array(
            'page_title'     => 'invoice',
            'menu_title'    => 'invoice',
            'menu_slug'        => 'websima-package-invoice-settings',
            'parent_slug'    => 'websima-package-general-settings',
            'capability'    => 'edit_posts'
        ));
    }
}

function websima_invoice_convert_number($string)
{
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $arabic  = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١', '٠'];

    $num                  = range(0, 9);
    $convertedPersianNums = str_replace($persian, $num, $string);
    $englishNumbersOnly   = str_replace($arabic, $num, $convertedPersianNums);

    return $englishNumbersOnly;
}

function websima_invoice_iran_state($state_str)
{
    if ($state_str == 'KHZ') {
        return 'خوزستان';
    }
    if ($state_str == 'THR') {
        return 'تهران';
    }
    if ($state_str == 'ILM') {
        return 'ایلام';
    }
    if ($state_str == 'BHR') {
        return 'بوشهر';
    }
    if ($state_str == 'ADL') {
        return 'اردبیل';
    }
    if ($state_str == 'ESF') {
        return 'اصفهان';
    }
    if ($state_str == 'YZD') {
        return 'یزد';
    }
    if ($state_str == 'KRH') {
        return 'کرمانشاه';
    }
    if ($state_str == 'KRN') {
        return 'کرمان';
    }
    if ($state_str == 'HDN') {
        return 'همدان';
    }
    if ($state_str == 'GZN') {
        return 'قزوین';
    }
    if ($state_str == 'ZJN') {
        return 'زنجان';
    }
    if ($state_str == 'LRS') {
        return 'لرستان';
    }
    if ($state_str == 'ABZ') {
        return 'البرز';
    }
    if ($state_str == 'EAZ') {
        return 'آذربایجان شرقی';
    }
    if ($state_str == 'WAZ') {
        return 'آذربایجان غربی';
    }
    if ($state_str == 'CHB') {
        return 'چهارمحال و بختیاری';
    }
    if ($state_str == 'SKH') {
        return 'خراسان جنوبی';
    }
    if ($state_str == 'RKH') {
        return 'خراسان رضوی';
    }
    if ($state_str == 'NKH') {
        return 'خراسان شمالی';
    }
    if ($state_str == 'SMN') {
        return 'سمنان';
    }
    if ($state_str == 'FRS') {
        return 'فارس';
    }
    if ($state_str == 'QHM') {
        return 'قم';
    }
    if ($state_str == 'KRD') {
        return 'کردستان';
    }
    if ($state_str == 'KBD') {
        return 'کهگیلوییه و بویراحمد';
    }
    if ($state_str == 'GLS') {
        return 'گلستان';
    }
    if ($state_str == 'GIL') {
        return 'گیلان';
    }
    if ($state_str == 'MZN') {
        return 'مازندران';
    }
    if ($state_str == 'MKZ') {
        return 'مرکزی';
    }
    if ($state_str == 'HRZ') {
        return 'هرمزگان';
    }
    if ($state_str == 'SBN') {
        return 'سیستان و بلوچستان';
    }
}

function websima_invoice_national_code_validation($code)
{
    if (!preg_match('/^[0-9]{10}$/', $code)) {
        return false;
    }
    for ($i = 0; $i < 10; $i++) {
        if (preg_match('/^' . $i . '{10}$/', $code)) {
            return false;
        }
    }
    for ($i = 0, $sum = 0; $i < 9; $i++) {
        $sum += ((10 - $i) * intval(substr($code, $i, 1)));
    }
    $ret    = $sum % 11;
    $parity = intval(substr($code, 9, 1));
    if (($ret < 2 && $ret == $parity) || ($ret >= 2 && $ret == 11 - $parity)) {
        return true;
    }

    return false;
}

if (get_option('options_invoice_customer_type_status') == 1) {
    include_once 'customer-type.php';
}
