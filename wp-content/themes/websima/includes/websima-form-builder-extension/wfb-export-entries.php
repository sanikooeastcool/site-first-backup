<?php
/*
 * Template name: برون ریزی فرم
 */
if(is_user_logged_in()){
    if(current_user_can('manage_options')){
        if($_GET['form_id']){
            $form_id = $_GET['form_id'];
            if(get_post_type(esc_attr($form_id)) == 'af_form'){
                $form_key = get_post_meta(esc_attr($form_id),'form_key',true);
                if($form_key){
                    $entries = af_get_entries(esc_attr($form_key));
                    if(!empty($entries)){
                        $csv_fields = array();
                        $form_exist_fields = array();
                        $field_groups = af_get_form_field_groups(esc_attr($form_key));
                        if(!empty($field_groups)){
                            foreach($field_groups as $field_group){
                                $fields = acf_get_fields($field_group);
                                foreach($fields as $field){
                                    $csv_fields[] = esc_html($field['label']);
                                    $form_exist_fields[] = esc_attr($field['name']);
                                }
                            }
                            $output_filename = 'Form_export_' . esc_attr($form_id)  . '.csv';
                            $output_handle = @fopen( 'php://output', 'w' );
                            header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
                            header( 'Content-Description: File Transfer' );
                            header( 'Content-type: text/csv' );
                            header('Content-Transfer-Encoding: binary');
                            header( 'Content-Disposition: attachment; filename=' . $output_filename );
                            header( 'Expires: 0' );
                            header( 'Pragma: public' );
                            fputcsv( $output_handle, $csv_fields );

                            foreach($entries as $entry){
                                $leadArray = array();
                                foreach($form_exist_fields as $selector){
                                    $leadArray[] = wfb_final_field_value(esc_attr($selector),esc_attr($entry->ID));
                                }
                                fputcsv($output_handle,$leadArray);
                            }

                            fclose($output_handle);
                            die();
                        }
                    }
                }
            }
        }
    }
}
?>
