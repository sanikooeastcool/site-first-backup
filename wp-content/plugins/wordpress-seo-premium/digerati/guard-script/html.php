<?php defined('ABSPATH') || exit ("no access");  ?>
<div id="main-guard-inner">
    <div class="license-input">
        <h1> <?php printf(esc_html__('Activation %s', 'zhaket-guard'), $this->a0464f20d3ee8883acb85a8907c7c); ?></h1>
        <?php if ($this->f026e2007e25d0a9437665b1f952ebaf): ?>
            <h3><?php esc_html_e('Your activation key:', 'zhaket-guard') ?></h3>
            <code id="code-style"><?php echo $this->e9762fc9cb268a7666cfb5002430afd() ?></code>
            <div class="text-left">
                    <span id="recheck-license" onclick="recheck_licence(this)"><?php esc_html_e('recheck license', 'zhaket-guard') ?></span>
                    <span id="remove-license" onclick="remove_licence(this)"><?php esc_html_e('remove / change key', 'zhaket-guard') ?></span>
            </div>
            <div id="license-message" style="display: flex; <?php echo ($this->d58c24374e59fdb76a1f===true)? 'background:red;':''?>">
                <div class="result" style=""><?php echo $this->f3491c0bb5d00e461bfbb0b66a('last_message'); ?></div>
            </div>
            <!-- /#license-message -->
        <?php else: ?>
            <h3><?php esc_html_e('Enter your activation key:', 'zhaket-guard') ?></h3>
            <input id="license-input" type="text" value="">
            <div class="text-left">
                    <span id="install-license" onclick="install_licence(this)"><?php esc_html_e('Activate',
                            'zhaket-guard') ?></span>
            </div>
            <div id="license-message">
            </div>
        <?php endif; ?>

        <!-- /#license-message -->
        <?php
        $crons=get_option('cron');
        $last_cron_run=false;
        foreach ($crons as $time => $cron){
            if (!is_array($cron)){
                continue;
            }
            if (isset($cron['zhaket_guard_daily_validator'])) {
                $last_cron_run = $time;
            }
        }
        if (empty($last_cron_run)){
            ?>
            <div id="license-message" style="display: flex; background:red;">
                <div class="result" ><?php esc_html_e('No cron jobs found. Your license can not be activated.', '###text-domain###'); ?></div>
            </div>
            <?php
        }elseif($last_cron_run < time()-90000 || $last_cron_run > time()+90000){
            ?>
            <div id="license-message" style="display: flex; background:red;">
                <div class="result" ><?php printf(__('Number of failed Activation checks is reached (%s). your plugin or theme will be disabled soon. Please check cron job settings on your WordPress or host. <a href="%s">cron manual</a>', '###text-domain###'),human_time_diff( $last_cron_run),'https://w3class.ir/cron-manual'); ?></div>
            </div>
            <?php
        }
        ?>
        <div id="license-help">
            <strong><?php esc_html_e('manual:', 'zhaket-guard') ?></strong>
            <ul>
                <?php if ($this->f026e2007e25d0a9437665b1f952ebaf): ?>
                    <li>
                        <?php esc_html_e('Your key is used in this website and it is not possible to your on other website.',
                            'zhaket-guard') ?>
                    </li>
                    <li>
                        <?php esc_html_e('if you want to move this license to another website, first use button "remove / change key" in this website, and next login in your zhaket website account and with click on change domain button , enter new website domain and now you can use from this key in new website.',
                            'zhaket-guard') ?>
                    </li>
                <?php else: ?>
                    <li>
                        <?php esc_html_e('For use from this product , must enter license key. for find your license key, login in your zhaket website account and go to download product section , and find this product and copy your license key or click on create license button and copy your license key.',
                            'zhaket-guard') ?>
                    </li>
                    <li>
                        <?php esc_html_e('every license activation is for one website only.', 'zhaket-guard') ?>
                    </li>
                    <li>
                        <?php esc_html_e('if your license if activated on another website, first use button "remove / change key" on another website, and next login in your zhaket website account and with click on change domain button , enter this website domain and now you can use this key in this website.',
                            'zhaket-guard') ?>
                    </li>
                <?php endif; ?>
            </ul>
            <?php
            if ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ) {
                echo '<hr>';
                echo sprintf( esc_html__( 'The %s constant is set to true. WP-Cron spawning is disabled.', '###text-domain###' ), 'DISABLE_WP_CRON' );
            }
            if ( defined( 'ALTERNATE_WP_CRON' ) && ALTERNATE_WP_CRON ) {
                echo '<hr>';
                echo sprintf( esc_html__( 'The %s constant is set to true.', '###text-domain###' ), 'ALTERNATE_WP_CRON'
                );
            }

            ?>
        </div>


    </div>
    <!-- /.license-input -->
    <div class="background-status">
        <?php if ($this->f026e2007e25d0a9437665b1f952ebaf): ?>
            <img src="<?php echo $this->b824eeb44894df3eb85e3c6323cd ?>assets/unlocked.png" alt="">
        <?php else: ?>
            <img src="<?php echo $this->b824eeb44894df3eb85e3c6323cd ?>assets/locked.png" alt="">
        <?php endif; ?>
    </div>
    <!-- /.background-status -->
</div>
<!-- /#main-guard-inner -->