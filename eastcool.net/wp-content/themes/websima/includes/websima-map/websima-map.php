<?php
defined( 'ABSPATH' ) || exit;
if(!class_exists('websima_map')):

    class websima_map_construct {
        function __construct() {
            include_once('inc/acf-import.php');
            include_once('inc/custom-functions.php');
        }

        function initialize() {

        }
    }


    function websima_map(){
        global $websima_map_var;

        if( !isset($websima_map_var)){
            $websima_map_var = new websima_map_construct();
            $websima_map_var->initialize();
        }

        return $websima_map_var;
    }


    // initialize
    websima_map();
endif;
?>