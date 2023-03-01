<?php
 if(get_field('show_faqs')){
    if(get_field('faqs')){
        echo '<div id="faqs" class="my-single">';
			echo '<div class="mx-auto">';
				 websima_faqs($term = null , $title = 'true' );
			echo '</div>';
        echo '</div>';
    }
 }	