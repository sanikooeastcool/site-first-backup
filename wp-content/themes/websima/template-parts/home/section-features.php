
<section class="section-feature">
    <div class="container">
		<?php
		if (get_sub_field('terms') != null){
			echo'<div class="row">';
			echo'<div class="feature-carousel owl-carousel">';
            $j=0.3;
						foreach (get_sub_field('terms') as $item) { ?>
                            <div class="item-feature-wrap fadeInUp wow" data-wow-delay="<?php echo $j?>s">
                                    <?php echo ($item['link'] !== '') ? '<a class="item-feature" href="'.$item['link'].'">' : '<div class="item-feature">' ?>
                                        <div class="img-wrap">
                                            <div class="octagonWrap">
                                                <div class='octagon'></div>
                                            </div>
                                            <?php echo wp_get_attachment_image($item['image'],'full') ?>
                                        </div>
                                        <?php if ($item['title'] != '') echo "<h3 class='title'>".$item['title']."</h3>"; ?>
                                        <?php if ($item['desc'] != '') echo "<p class='desc'>".$item['desc']."</p>"; ?>
                                    <?php echo ($item['link'] !== '') ? '</a>' : '</div>' ?>
                            </div>
							<?php
                            $j=$j+0.15;
						}
			echo'</div>';
			echo'</div>';
		} ?>
    </div>
</section>