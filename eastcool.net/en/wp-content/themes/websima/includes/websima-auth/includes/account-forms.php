<?php
function websima_auth_account_detection_form()
{
?>
    <div id="account_detection_form_wrapper">
        <form method="post" id="account_detection_form" novalidate="novalidate">
            <div class="form-row">
                <div class="form-group col-12 col-md-12">
                    <label class="text-muted">phone number</label>
                    <input type="number" name="mobile" id="mobile" class="form-control ltr" placeholder="09*********" maxlength="11">
                </div>
            </div>

            <?php wp_nonce_field('account_detection_nonce', 'account_detection_nonce_field'); ?>
            <input type="hidden" name="action" value="websima_auth_account_detection" />
            <button type="submit" class="button btn btn-primary">send</button>
        </form>
    </div>
<?php
}

function websima_auth_account_login_form()
{
    $auth_password_strategy = get_field('auth_password_strategy', 'option');
?>
    <div id="account_login_form_wrapper">
        <form method="post" id="account_login_form" novalidate="novalidate">
            <div class="form-row">
                <div class="form-group col-12 col-md-12">
                    <label class="text-muted">password</label>
                    <?php if ($auth_password_strategy == 'otp') { ?>
                        <small class="form-text">Please enter the one-time password that was sent to you via SMS</small>
                    <?php } ?>
                    <input type="number" name="password" id="password" class="form-control ltr" placeholder="******" maxlength="6">
                </div>
            </div>

            <div class="form-row buttons-wrapper">
                <?php wp_nonce_field('account_login_nonce', 'account_login_nonce_field'); ?>
                <input type="hidden" name="action" value="websima_auth_account_login" />
                <button type="submit" class="button btn btn-primary">login</button>

                <?php
                if ($auth_password_strategy == 'otp') {
                    $label = 'otp password';
                    $type = 'otp-password';
                } else {
                    $label = 'reset password';
                    $type = 'reset-password';
                }
                ?>
                <div class="resend_code_wrapper">
                    <span class="resend-code" data-type="<?php echo esc_attr($type); ?>"><?php echo esc_html($label); ?></span>
                </div>
            </div>
        </form>
    </div>
<?php
}

function websima_auth_account_register_form()
{
?>
    <div id="account_register_form_wrapper">
        <form method="post" id="account_register_form" novalidate="novalidate">
            <div class="form-row">
                <div class="form-group col-12 col-md-12">
                    <label class="text-muted">Verification code</label>
                    <small class="form-text">Please enter the confirmation code that was sent to you via SMS</small>
                    <input type="number" name="verification_code" id="verification_code" class="form-control ltr" placeholder="******" maxlength="6">
                </div>
            </div>

            <div class="form-row buttons-wrapper">
                <?php wp_nonce_field('account_register_nonce', 'account_register_nonce_field'); ?>
                <input type="hidden" name="action" value="websima_auth_account_register" />
                <button type="submit" class="button btn btn-primary">Sign-Up</button>

                <div class="resend_code_wrapper">
                    <span class="resend-code" data-type="verification-code">Send verification code</span>
                </div>
            </div>
        </form>
    </div>
    <?php
}

function websima_auth_account_profile_form()
{
    if (websima_auth_register_extra_step()) {
        $fullname_active = get_field('auth_fullname_active', 'option');
        $email_active = get_field('auth_email_active', 'option');
        $password_strategy = get_field('auth_password_strategy', 'option');
    ?>
        <div id="account_profile_form_wrapper">
            <form method="post" id="account_profile_form" novalidate="novalidate">
                <p class="form-description">Please enter the following additional information to complete the Sign-Up process</p>
                <?php if ($fullname_active) { ?>
                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label class="text-muted">first name *</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="hamid">
                        </div>
                        <div class="form-group col-12 col-md-6">
                            <label class="text-muted">last name *</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="hamoon">
                        </div>
                    </div>
                <?php } ?>

                <?php if ($email_active) { ?>
                    <div class="form-row">
                        <div class="form-group col-12 col-md-12">
                            <label class="text-muted">email *</label>
                            <input type="email" name="email" id="email" class="form-control ltr" placeholder="info@company.com">
                        </div>
                    </div>
                <?php } ?>

                <?php if ($password_strategy == 'user_choice') { ?>
                    <div class="form-row">
                        <div class="form-group col-12 col-md-12">
                            <label class="text-muted">password *</label>
                            <small class="form-text">The password must be numeric and contain 6 characters</small>
                            <input type="number" name="new_password" id="new_password" class="form-control ltr" placeholder="******" maxlength="6">
                        </div>
                    </div>
                <?php } ?>

                <?php wp_nonce_field('account_profile_nonce', 'account_profile_nonce_field'); ?>
                <input type="hidden" name="action" value="websima_auth_account_profile" />
                <button type="submit" class="button btn btn-primary">Complete Sign-Up</button>
            </form>
        </div>
<?php
    }
}
