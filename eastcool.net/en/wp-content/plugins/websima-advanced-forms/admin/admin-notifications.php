<?php

class AF_Admin_Notifications
{

    function __construct()
    {

        add_action('acf/render_field/type=text', array($this, 'add_notifications_field_inserter'), 20, 1);

        add_filter('acf/load_field/name=recipient_field', array($this, 'populate_notifications_field_choices'), 10, 1);
        add_filter('af/form/settings_fields', array($this, 'notifications_acf_fields'), 10, 1);
    }


    /**
     * Add an "Insert field" button to recipient, subject, and from fields
     *
     * @since 1.0.1
     *
     */
    function add_notifications_field_inserter($field)
    {

        global $post;

        if (!$post) {
            return;
        }


        $form = af_form_from_post($post);

        if (!$form) {
            return;
        }

        $fields_to_add = array(
            'field_form_email_recipient_custom',
            'field_form_email_subject',
            'field_form_email_from',
        );


        if (in_array($field['key'], $fields_to_add)) {

            _af_field_inserter_button($form, 'regular', true);
        }
    }


    /**
     * Populates the email recipient field select with the current form's fields
     *
     * @since 1.0.0
     *
     */
    function populate_notifications_field_choices($field)
    {

        global $post;

        if ($post && 'af_form' == $post->post_type) {

            $form_key = get_post_meta($post->ID, 'form_key', true);

            $field['choices'] = _af_form_field_choices($form_key, 'regular');
        }

        return $field;
    }


    /**
     * Add fields for setting up emails to the form settings
     *
     * @since 1.0.0
     *
     */
    function notifications_acf_fields($field_group)
    {

        $field_group['fields'][] = array(
            'key' => 'field_form_notifications_tab',
            'label' => '<span class="dashicons dashicons-email-alt"></span>' . __('Notifications', 'advanced-forms'),
            'name' => '',
            'type' => 'tab',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'placement' => 'left',
            'endpoint' => 0,
        );

        $field_group['fields'][] = array(
            'key' => 'field_60a36fcc682c9',
            'label' => __('Email', 'advanced-forms'),
            'name' => 'manager_notify_email_repeater',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'hide_admin' => 0,
            'collapsed' => '',
            'min' => 0,
            'max' => 0,
            'layout' => 'table',
            'button_label' => 'add',
            'sub_fields' => array(
                array(
                    'key' => 'field_60a36fe7682ca',
                    'label' => __('Email', 'advanced-forms'),
                    'name' => 'email',
                    'type' => 'email',
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
                ),
            ),
        );

        $field_group['fields'][] = array(
            'key' => 'field_60a3702af7583',
            'label' => __('SMS', 'advanced-forms'),
            'name' => 'manager_notify_sms_repeater',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'hide_admin' => 0,
            'collapsed' => '',
            'min' => 0,
            'max' => 0,
            'layout' => 'table',
            'button_label' => 'add',
            'sub_fields' => array(
                array(
                    'key' => 'field_60a3702af7584',
                    'label' => __('Full name', 'advanced-forms'),
                    'name' => 'fullname',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
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
                    'key' => 'field_60a37052f7585',
                    'label' => __('Mobile number', 'advanced-forms'),
                    'name' => 'mobile',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
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
        );

        $field_group = apply_filters('af/form/notification_settings_fields', $field_group);
        return $field_group;
    }
}

return new AF_Admin_Notifications();
