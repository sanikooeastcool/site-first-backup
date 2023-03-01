<?php
defined('ABSPATH') || exit ("no access");
if( empty($this->f026e2007e25d0a9437665b1f952ebaf) ): ?>
    <div class="notice notice-error">
        <?php if (version_compare(PHP_VERSION, '7.0.0') >= 0):?>
            <p>
                <?php printf(esc_html__( 'To activating your %s please insert you license key', 'zhaket-guard' ), $this->a0464f20d3ee8883acb85a8907c7c); ?>
                <a href="<?php echo admin_url( 'admin.php?page='.$this->fff03e6125a2b44f0d3b2efe0ff1f ); ?>" class="button button-primary"><?php _e('Register Activate Code', 'zhaket-guard'); ?></a>
            </p>
        <?php else:?>
            <p>
                <?php printf(esc_html__( 'Your PHP version is lower than 7. for active yoast it must be updated.', 'zhaket-guard' ), $this->a0464f20d3ee8883acb85a8907c7c); ?>
            </p>
    <?php endif; ?>
    </div>
<?php elseif( $this->d58c24374e59fdb76a1f===true ): ?>
    <div class="notice notice-error">
        <p>
            <?php printf(esc_html__( 'There is something wrong with your %s license. please check it.', 'zhaket-guard' ), $this->a0464f20d3ee8883acb85a8907c7c); ?>
            <a href="<?php echo admin_url( 'admin.php?page='.$this->fff03e6125a2b44f0d3b2efe0ff1f ); ?>" class="button button-primary"><?php _e('Check Now', 'zhaket-guard'); ?></a>
        </p>
    </div>
<?php endif; ?>