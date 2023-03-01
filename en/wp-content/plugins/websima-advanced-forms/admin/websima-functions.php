<?php

/**
 * ACF configurations.
 */
add_action('admin_head', 'wfb_admin_head');
function wfb_admin_head()
{
    if (is_admin()) {
        $current_screen = get_current_screen();
        if ($current_screen->post_type == 'acf-field-group') {
            //if('devadmin' != wp_get_current_user()->user_login){
            if ((isset($_GET['from']) and $_GET['from'] == 'form_builder' and isset($_GET['form_id']) and get_post_type($_GET['form_id']) == 'af_form' and !isset($_GET['action'])) or (isset($_GET['from']) and $_GET['from'] == 'form_builder' and isset($_GET['form_id']) and get_post_type($_GET['form_id']) == 'af_form' and isset($_GET['action']) and $_GET['action'] == 'edit' and isset($_GET['post']) and get_post_type($_GET['post']) == 'acf-field-group')) {
                remove_meta_box('acf-field-group-locations', 'acf-field-group', 'normal');
                remove_meta_box('acf-field-group-options', 'acf-field-group', 'normal');
                add_filter('acf/admin/toolbar', 'wfb_remove_acf_tabs');
                add_filter('acf/get_field_types', 'wfb_acf_get_field_types');
            } else {
                if ('devadmin' != wp_get_current_user()->user_login) {
                    wp_redirect(admin_url('/edit.php?post_type=af_form'));
                    exit;
                }
            }
            //}
        }
    }
}

/**
 * Remove ACF tabs.
 */
function wfb_remove_acf_tabs()
{
    return false;
}

/**
 * ACF configurations.
 */
add_action('acf/update_field_group', 'wfb_update_field_group');
function wfb_update_field_group($field_group)
{
    if (is_admin()) {
        $field_group_id = $field_group['ID'];

        $http_referer_parse_url = parse_url($_SERVER['HTTP_REFERER']);
        parse_str($http_referer_parse_url['query'], $query_str);

        $current_screen = get_current_screen();
        if ($current_screen->post_type == 'acf-field-group') {
            //if('devadmin' != wp_get_current_user()->user_login){
            if ((isset($query_str['from']) and $query_str['from'] == 'form_builder' and isset($query_str['form_id']) and get_post_type($query_str['form_id']) == 'af_form' and !isset($query_str['action'])) or (isset($query_str['from']) and $query_str['from'] == 'form_builder' and isset($query_str['form_id']) and get_post_type($query_str['form_id']) == 'af_form' and isset($query_str['action']) and $query_str['action'] == 'edit' and isset($query_str['post']) and get_post_type($query_str['post']) == 'acf-field-group')) {
                $form_key = get_post_meta($query_str['form_id'], 'form_key', true);
                if ($form_key) {
                    $field_group_record = get_post($field_group_id);
                    $field_group_content = unserialize($field_group_record->post_content);
                    unset($field_group_content['location']);
                    $field_group_content['location'][0][0]['param'] = 'af_form';
                    $field_group_content['location'][0][0]['operator'] = '==';
                    $field_group_content['location'][0][0]['value'] = esc_attr($form_key);

                    $field_group_array = array(
                        'ID' => esc_attr($field_group_id),
                        'post_content' => serialize($field_group_content)
                    );
                    wp_update_post($field_group_array);

                    wp_redirect(admin_url('/post.php?post=' . esc_attr($query_str['form_id']) . '&action=edit'));
                    exit;
                } else {
                    wp_redirect(admin_url('/edit.php?post_type=af_form'));
                    exit;
                }
            } else {
                if ('devadmin' != wp_get_current_user()->user_login) {
                    wp_redirect(admin_url('/edit.php?post_type=af_form'));
                    exit;
                }
            }
            //}
        }
    }
}

/**
 * Send SMS.
 */
function wfb_send_sms($mobile, $template, $token1, $token2 = null, $token3 = null, $token10 = null, $token20 = null)
{
    $api = get_field('wfb_kavenegar_api', 'option');
    $url = 'https://api.kavenegar.com/v1/' . $api . '/verify/lookup.json?receptor=' . $mobile . '&template=' . $template . '&token=' . urlencode($token1) . '&token2=' . urlencode($token2) . '&token3=' . urlencode($token3) . '&token10=' . urlencode($token10) . '&token20=' . urlencode($token20);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
}

/**
 * Email and SMS notifications.
 */
add_action('wfb_save_submission', 'wfb_send_notification');
function wfb_send_notification($submission)
{
    if (!is_admin()) {
        if (!empty($submission)) {
            if ($submission['form']['post_id']) {
                $form_id = $submission['form']['post_id'];
                if (get_post_type(esc_attr($form_id)) == 'af_form') {
                    /* Manager notification*/
                    if (have_rows('manager_notify_email_repeater', esc_attr($form_id))) :
                        while (have_rows('manager_notify_email_repeater', esc_attr($form_id))) : the_row();
                            $to = get_sub_field('email');

                            $site_name = get_bloginfo('name');
                            $subject = 'form - ' . get_the_title($form_id);
                            $body = 'Hi, a new form has been filled in the site, please check the inbox of the site';
                            $headers = 'From: ' . esc_attr($site_name)  . "\r\n";

                            wp_mail($to, $subject, $body, $headers);
                        endwhile;
                    endif;

                    $submission_manager_notify_template = get_field('wfb_submission_manager_notify_template', 'option');
                    if (have_rows('manager_notify_sms_repeater', esc_attr($form_id))) :
                        while (have_rows('manager_notify_sms_repeater', esc_attr($form_id))) : the_row();
                            $fullname = get_sub_field('fullname');
                            $mobile = get_sub_field('mobile');
                            if (wfb_mobile_number_validation($mobile)) {
                                wfb_send_sms(esc_attr($mobile), esc_attr($submission_manager_notify_template), 'نمایید', '', '', esc_attr($fullname));
                            }
                        endwhile;
                    endif;



                    /* User notification*/
                    $user_email = '';
                    $user_mobile = '';
                    foreach ($submission['fields'] as $submission_fields) {
                        if ($submission_fields['type'] == 'email' and $submission_fields['name'] == 'user_email') {
                            $user_email = sanitize_email($submission_fields['value']);
                        }

                        if ($submission_fields['type'] == 'text' and $submission_fields['name'] == 'user_mobile') {
                            $user_mobile = sanitize_text_field($submission_fields['value']);
                        }
                    }

                    $submission_user_notify_template = get_field('wfb_submission_user_notify_template', 'option');
                    if ($user_mobile) {
                        if (wfb_mobile_number_validation($user_mobile)) {
                            wfb_send_sms(esc_attr($user_mobile), esc_attr($submission_user_notify_template), 'شد', '', '', '', '');
                        }
                    }

                    if ($user_email) {
                        $site_name = get_bloginfo('name');
                        $subject = get_the_title($form_id);
                        $body = 'Hello, your message has been received.';
                        $headers = 'From: ' . esc_attr($site_name)  . "\r\n";

                        wp_mail($user_email, $subject, $body, $headers);
                    }
                }
            }
        }
    }
}

/**
 * Page option.
 */
add_action('init', 'wfb_admin_menu');
function wfb_admin_menu()
{
    if (function_exists('acf_add_options_page')) {
        acf_add_options_sub_page(array(
            'page_title'     => 'Form builder settings',
            'menu_title'    => 'Form builder settings',
            'menu_slug'        => 'websima-package-form-builder-settings',
            'parent_slug'    => 'edit.php?post_type=af_form',
            'capability'    => 'edit_posts'
        ));
    }
}

/**
 * Plugin settings - Entry settings.
 */
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group(array(
        'key' => 'group_60a618b58f2e1',
        'title' => 'Form builder settings',
        'fields' => array(
            array(
                'key' => 'field_wfb_kavenegar_api',
                'label' => 'Kavenegar API',
                'name' => 'wfb_kavenegar_api',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'hide_admin' => 0,
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_wfb_manager_notify_template',
                'label' => 'SMS notification template to complete the form for the administrator',
                'name' => 'wfb_submission_manager_notify_template',
                'type' => 'text',
                'instructions' => 'Hello dear% token10 A new form has been filled in the site, please check the site inbox% token. WebSima Store',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'hide_admin' => 0,
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
            array(
                'key' => 'field_wfb_user_notify_message',
                'label' => 'Notifications to the user',
                'name' => '',
                'type' => 'message',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'hide_admin' => 0,
                'message' => 'To receive a text message after completing the form, you must create a text field named user_mobile in the form. If the user has completed this field, a text message will be sent to the user.
                To receive an email after completing the form, you must create an email field in the form called user_email. An email will be sent to the user if the user completes this field.
                ',
                'new_lines' => 'wpautop',
                'esc_html' => 0,
            ),
            array(
                'key' => 'field_wfb_user_notify_template',
                'label' => 'SMS notification template for completing the form for the user',
                'name' => 'wfb_submission_user_notify_template',
                'type' => 'text',
                'instructions' => 'Hello, your message received% token. WebSima Store',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'hide_admin' => 0,
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'websima-package-form-builder-settings',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    acf_add_local_field_group(array(
        'key' => 'group_60adf4409fe1d',
        'title' => 'Message settings',
        'fields' => array(
            array(
                'key' => 'field_60adf46086431',
                'label' => 'status',
                'name' => 'status',
                'type' => 'select',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'hide_admin' => 0,
                'choices' => array(
                    'pending' => 'pending',
                    'need_to_follow' => 'need to follow',
                    'checked' => 'checked',
                ),
                'default_value' => false,
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'af_entry',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
endif;

/**
 * Save form create entries by default.
 */
add_action('save_post', 'wfb_af_form_save_post');
function wfb_af_form_save_post($post_id)
{
    if (is_admin()) {
        if (get_post_type(esc_attr($post_id)) == 'af_form') {
            update_post_meta(esc_attr($post_id), 'form_create_entries', 1);
        }
    }
}

/**
 * Remove ACF extra field types.
 */
function wfb_acf_get_field_types($groups)
{
    foreach ($groups as $group_key => $fields) {
        foreach ($fields as $field_type => $field_name) {
            if (in_array($field_type, array('map_location', 'wysiwyg', 'oembed', 'gallery', 'link', 'post_object', 'page_link', 'relationship', 'taxonomy', 'user', 'google_map', 'date_time_picker', 'color_picker', 'accordion', 'tab', 'group', 'flexible_content', 'clone'))) {
                unset($groups[esc_attr($group_key)][esc_attr($field_type)]);
            }
        }
    }

    unset($groups['Relational']);
    unset($groups['Relational']);
    unset($groups['Forms']);

    return $groups;
}

/**
 * Entry status translator.
 */
function wfb_entry_status_translator($status)
{
    $status_str = '';
    if ($status == 'pending') {
        $status_str = 'need_to_follow';
    } elseif ($status == 'need to follow') {
        $status_str = 'need to follow';
    } elseif ($status == 'checked') {
        $status_str = 'checked';
    } else {
        $status_str = ' - ';
    }

    return $status_str;
}

/**
 * Entry status filter.
 */
add_action('restrict_manage_posts', 'wfb_entry_filter_by_status');
function wfb_entry_filter_by_status()
{
    global $typenow;
    global $wp_query;
    if ($typenow == 'af_entry') {
        $current_status = '';
        if (isset($_GET['status'])) {
            $current_status = $_GET['status'];
        }
?>
        <select name="status" id="status">
            <option value="all">همه</option>
            <option value="pending" <?php if ($current_status == 'pending') {
                                        echo 'selected';
                                    } ?>> pending </option>
            <option value="need_to_follow" <?php if ($current_status == 'need_to_follow') {
                                                echo 'selected';
                                            } ?>>need to follow</option>
            <option value="checked" <?php if ($current_status == 'checked') {
                                        echo 'selected';
                                    } ?>>checked</option>
        </select>
<?php
    }
}

add_filter('parse_query', 'wfb_entry_filter_by_status_query');
function wfb_entry_filter_by_status_query($query)
{
    global $pagenow;
    $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
    if (is_admin() && $pagenow == 'edit.php' && $post_type == 'af_entry' && isset($_GET['status']) && $_GET['status'] != 'all') {
        $query->query_vars['meta_key'] = 'status';
        $query->query_vars['meta_value'] = $_GET['status'];
        $query->query_vars['meta_compare'] = '=';
    }
}

/**
 * Page template in sub-folder.
 */
add_filter('theme_page_templates', 'wfb_add_page_templates', 10, 4);
function wfb_add_page_templates($post_templates, $wp_theme, $post, $post_type)
{
    $post_templates['includes/websima-form-builder-extension/wfb-print-entry.php'] = 'Print incoming message';
    $post_templates['includes/websima-form-builder-extension/wfb-export-entries.php'] = 'import form';

    return $post_templates;
}

/**
 * Find page id.
 */
function wfb_find_page_id($redirect_slug)
{
    $template_page_property_comparison_array = get_pages(
        array(
            'meta_key' => '_wp_page_template',
            'meta_value' => $redirect_slug
        )
    );
    if ($template_page_property_comparison_array) {
        return get_the_permalink($template_page_property_comparison_array[0]->ID);
    } else {
        return site_url();
    }
}

/**
 * Find entry value.
 */
function wfb_final_field_value($selector, $entry_id)
{
    $field_object = get_field_object(esc_attr($selector), esc_attr($entry_id));
    $field_type = $field_object['type'];
    $field_value = $field_object['value'];
    $field_return_format = '';
    $field_value_final = '';

    if ($field_type == 'message') {
        $field_value_final = '';
    } elseif ($field_type == 'image' or $field_type == 'file') {
        $field_return_format = $field_object['return_format'];
        if ($field_return_format == 'id') {
            $field_value_final = wp_get_attachment_url($field_value);
        } elseif ($field_return_format == 'array') {
            $field_value_final = esc_url($field_value['url']);
        } elseif ($field_return_format == 'url') {
            $field_value_final = esc_url($field_value);
        }
    } elseif ($field_type == 'radio') {
        $field_return_format = $field_object['return_format'];
        if ($field_return_format == 'label') {
            $field_value_final = esc_html($field_value);
        } elseif ($field_return_format == 'value') {
            $field_value_final = esc_html($field_value);
        } elseif ($field_return_format == 'array') {
            $field_value_final = esc_html($field_value['label']);
        }
    } elseif ($field_type == 'checkbox') {
        $field_return_format = $field_object['return_format'];
        if ($field_return_format == 'label') {
            $field_value_final = implode(', ', $field_value);
        } elseif ($field_return_format == 'value') {
            $field_value_final = implode(', ', $field_value);
        } elseif ($field_return_format == 'array') {
            $field_value_final = implode(',', array_map('array_pop', $field_value));
        }
    } elseif ($field_type == 'true_false') {
        if ($field_value == 1) {
            $field_value_final = 'بله';
        } else {
            $field_value_final = 'خیر';
        }
    } elseif ($field_type == 'select') {
        $field_return_format = $field_object['return_format'];
        if (!$field_object['multiple']) {
            if ($field_return_format == 'label') {
                $field_value_final = esc_html($field_value);
            } elseif ($field_return_format == 'value') {
                $field_value_final = esc_html($field_value);
            } elseif ($field_return_format == 'array') {
                $field_value_final = esc_html($field_value['label']);
            }
        } else {
            if ($field_return_format == 'label') {
                $field_value_final = implode(', ', $field_value);
            } elseif ($field_return_format == 'value') {
                $field_value_final = implode(', ', $field_value);
            } elseif ($field_return_format == 'array') {
                $field_value_final = implode(',', array_map('array_pop', $field_value));
            }
        }
    } elseif ($field_type == 'repeater') {
        $repeater_columns = array();
        $repeater_counter = 1;
        foreach ($field_object['sub_fields'] as $field_object_sub_field) {
            $repeater_columns[esc_attr($field_object_sub_field['name'])] = esc_html($field_object_sub_field['label']);
        }

        if (!empty($field_value)) {
            foreach ($field_value as $field_val) {
                $repeater_inner_counter = 1;
                foreach ($repeater_columns as $repeater_column_key => $repeater_column_value) {
                    $field_value_final .= esc_html($repeater_column_value);
                    $field_value_final .= ': ';
                    $field_value_final .= esc_html($field_val[$repeater_column_key]);
                    if ($repeater_inner_counter < count($repeater_columns)) {
                        $field_value_final .= ' - ';
                    }
                    $repeater_inner_counter++;
                }
                if ($repeater_counter < count($field_value)) {
                    $field_value_final .= ' / ';
                }
                $repeater_counter++;
            }
        }
    } else {
        if ($field_value) {
            $field_value_final = esc_html($field_value);
        }
    }

    if (!$field_value_final) {
        $field_value_final = ' - ';
    }
    return $field_value_final;
}

/**
 * Mobile number validation.
 */
function wfb_mobile_number_validation($mobile)
{
    if (strlen($mobile) == '11') {
        if (is_numeric($mobile)) {
            if (preg_match('/^09[0-9]{9}$/', $mobile)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}
