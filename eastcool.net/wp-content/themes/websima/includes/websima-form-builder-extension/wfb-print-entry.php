<?php
/*
 * Template name: چاپ پیام ورودی
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="<?php echo get_template_directory_uri().'/includes/websima-form-builder-extension/assets/css/style.css'; ?>">
    </head>
    <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

        <div id="wrapper">
            <div id="wfb-main-content">
                <?php
                if(is_user_logged_in()){
                    if(current_user_can('manage_options')){
                        if($_GET['entry_id']){
                            $entry_id = $_GET['entry_id'];
                            if(get_post_type(esc_attr($entry_id)) == 'af_entry'){
                                $entry_form = get_post_meta(esc_attr($entry_id),'entry_form',true);
                                if($entry_form){
                                    $forms = af_get_forms();
                                    foreach($forms as $form){
                                        if($form['key'] == $entry_form){
                                            $pid = esc_attr($form['post_id']);
                                        }
                                    }

                                    if($pid){
                                        if(get_post_type(esc_attr($pid)) == 'af_form'){
                                            $form = af_get_form(esc_attr($pid));
                                            $field_groups = af_get_form_field_groups($form['key']);
                                            if(!empty($field_groups)){
                                                if($form['title']){ echo '<h1 class="form-title">'.esc_html($form['title']).'</h1>'; }
                                                foreach($field_groups as $field_group){
                                                    echo '<div class="form-items-wrapper">';
                                                        if($field_group['title']){ echo '<h2 class="form-subtitle">'.esc_html($field_group['title']).'</h2>'; }
                                                        $fields = acf_get_fields($field_group);
                                                        echo '<div class="form-items">';
                                                            echo '<div class="row">';
                                                                foreach($fields as $field){
                                                                    echo '<div class="column column-6">';
                                                                        echo '<span class="title">'.esc_html($field['label']).':</span>';
                                                                        echo '<span class="value">';
                                                                            echo wfb_final_field_value(esc_attr($field['name']),esc_attr($entry_id));
                                                                        echo '</span>';
                                                                    echo '</div>';
                                                                }
                                                            echo '</div>';
                                                        echo '</div>';
                                                    echo '</div>';
                                                }
                                            }else{
                                                echo '<div class="alert-error">خطا رخ داده است</div>';
                                            }
                                        }else{
                                            echo '<div class="alert-error">خطا رخ داده است</div>';
                                        }
                                    }else{
                                        echo '<div class="alert-error">خطا رخ داده است</div>';
                                    }
                                }else{
                                    echo '<div class="alert-error">خطا رخ داده است</div>';
                                }
                            }else{
                                echo '<div class="alert-error">خطا رخ داده است</div>';
                            }
                        }else{
                            echo '<div class="alert-error">خطا رخ داده است</div>';
                        }
                    }else{
                        echo '<div class="alert-error">خطا رخ داده است</div>';
                    }
                }else{
                    echo '<div class="alert-error">خطا رخ داده است</div>';
                }
                ?>
            </div>
        </div>
    </body>
</html>