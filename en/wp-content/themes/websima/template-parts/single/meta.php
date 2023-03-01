<div class="title-site title-post">
    <h1 class="title-heading"><?php echo get_the_title();?></h1>
</div>

    <?php if(get_the_category()){?>
        <div class="category_post">
            <?php the_category(' ', ' '); ?>
        </div>
    <?php } ?>
<div class="social-wrap">
    <?php if(get_field('post_date')){?>
        <div class="post-date">
            <span class="date"><?php echo get_field('post_date');?></span>
        </div>
    <?php } ?>
    <div class="social-links">
        <i class="icon-share"></i>
        <?php echo websima_shares(); ?>
    </div>
</div>

<?php if(get_field('short_desc')){?>
    <div class="editor-content post-content">
        <?php
        echo get_field('short_desc');
        ?>
    </div>
<?php } else {?>
    <div class="editor-content post-content">
        <?php
        echo get_the_excerpt();
        ?>
    </div>
<?php } ?>