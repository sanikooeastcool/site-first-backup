<?php
$codes_body_end = get_field('codes_body_end','option');
$footer_desc = get_field('desc_ft','option');
$phone_ft = get_field('phone_ft','option');
$phone2_ft = get_field('phone2_ft','option');
$email_ft = get_field('email_ft','option');
$address_ft = get_field('address_ft','option');
$address_ft_url = get_field('address_ft_url','option');
$footer_menu1 = get_field('menu_one_ft','option');
$footer_menu2 = get_field('menu_two_ft','option');
$contact_ft = get_field('contact_ft','option');
$copyright = get_field('copyright','option');
$logo_ft = get_field('logo_ft','option');
$newsletter_title = get_field('newsletter_title','option');
$newsletter_desc = get_field('newsletter_desc','option');
$title = get_field('address_title','option');
$address = get_field('address','option');
$address_link = get_field('address_link','option');
$postal = get_field('postal','option');
$phone = get_field('phone_1','option');
$phone2 = get_field('phone_2','option');
$request_phone = get_field('request_phone','option');
$request_phone_link = get_field('request_phone_link','option');
$main_title_request = get_field('title_request','option');
$short_desc_request = get_field('desc_request','option');
$logo = get_field('logo', 'option');

include( locate_template( 'template-parts/footers/footer2.php', false, false ) );
?>
</div>
<?php if(!is_product()) {?>
<div class="request-btn active">
    <span>درخواست مشاوره</span>
    <i class="icon-requestclose"></i>
</div>
<?php } ?>
<div class="request-box">
    <i class="icon-close"></i>
    <?php echo wp_get_attachment_image($logo, 'full'); ?>
    <?php
    if($request_phone){
        echo '<a class="tel-request" href="tel:'.$request_phone_link.'">'.$request_phone.'</a>';
    }
    ?>

    <?php
    if($main_title_request){
        echo '<h4 class="title">'.$main_title_request.'</h4>';
    }
    if($short_desc_request){
        echo '<p class="desc">'.$short_desc_request.'</p>';
    }
    ?>

    <?php websima_request_form() ;?>

</div>


<?php
get_template_part('template-parts/search','popup');

get_template_part('template-parts/mobile','menu');

websima_auth_modal();

wp_footer();

if (is_singular('post')) {
	blog_schema(get_the_id());
}

if ($codes_body_end != '') echo PHP_EOL.$codes_body_end.PHP_EOL; 
?>
</body>
</html>