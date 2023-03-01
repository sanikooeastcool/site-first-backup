<?php 
if($cta_title || $cta_desc || $cta_phone):
	echo'<div class="cta">';
		if($cta_title):
			echo'<h3>'.$cta_title.'</h3>';
		endif;
		if($cta_desc):
			echo'<p>'.$cta_desc.'</p>';
		endif;
		if($cta_phone):
			echo'<a href="'.$cta_phone.'">'.$cta_phone.'</a>';
		endif;
	echo'</div>';
endif;