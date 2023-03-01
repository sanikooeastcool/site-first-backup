<?php 
    $location = get_sub_field('location');
	$location = explode(",",$location);
	$lat = $location[0];
	$lng = $location[1];
	$working_time = get_sub_field('working_time');
	$address = get_sub_field('address');
	$phones = get_sub_field('phones');
	$fax = get_sub_field('fax');
	$email = get_sub_field('email');
?>
<ul class="contact-info">
    <?php if($phones): ?>
        <li class="phone">
            <i class="icon-phone"></i>
            <?php foreach($phones as $phone):?>
            <div class="phone-item">
                <a href="tel:<?php echo esc_attr($phone['phone']);?>"><?php echo esc_html($phone['phone']);?></a>
                <?php endforeach;?>
            </div>
        </li>
    <?php endif; ?>
    <?php if($email): ?>
        <li class="email"><i class="icon-mail"></i><a href="mailto:<?php echo esc_attr($email);?>"><?php echo esc_html($email);?></a></li>
    <?php endif; ?>
    <?php if($address):?>
        <li class="address"><i class="icon-pinn"></i><a href="https://www.google.com/maps/dir/?api=1&amp;destination=<?php echo $lat;?>, <?php echo $lng;?>" target="_blank" rel="nofollow" ><?php echo esc_html($address); ?></a></li>
    <?php endif; ?>
    <?php if($fax): ?>
        <li class="fax"><i class="icon-fax"></i><?php echo esc_html($fax);?></li>
    <?php endif; ?>

    <?php if($working_time): ?>
        <li class="working-time"><i class="icon-time"></i><?php echo esc_html($working_time);?></li>
    <?php endif; ?>
</ul>