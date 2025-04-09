<?php
/*
 * Template Name: Registration Form
 * */
if ( is_user_logged_in() ) {
    wp_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) );
}
get_header();
?>

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class="registration-section">
    <h1><?php esc_html_e( 'New user', 'personalize-login' ); ?></h1>
    <div class="user-form class">
        <form method="post"
              class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
            <?php do_action( 'woocommerce_register_form_start' ); ?>
            <div class="wrap-input class">
                <input type="text" name="billing_first_name"
                       required
                       placeholder="<?php _e( 'First name', 'personalize-login' ); ?>*"
                       autocomplete="first-name" id="first-name">
            </div>
            <div class="wrap-input">
                <input type="tel" id="billing_phone" name="billing_phone" placeholder="<?php esc_html_e('Phone number', 'bogika'); ?>">
            </div>
            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
                <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide wrap-input">
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                           id="reg_username" autocomplete="username"
                           placeholder="<?php esc_html_e( 'Username', 'woocommerce' ); ?>*"
                           value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
                </div>
            <?php endif; ?>
            <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide wrap-input">
                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email"
                       id="reg_email" autocomplete="email"
                       placeholder="<?php esc_html_e( 'Email address', 'woocommerce' ); ?>*"
                       required
                       value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>"/><?php // @codingStandardsIgnoreLine ?>
            </div>
            <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide wrap-input">
                <input type="text" class="woocommerce-Input woocommerce-Input--birthday input-text"
                       name="dob2" required
                       id="account_birthday" autocomplete="birthday"
                       placeholder="<?php esc_html_e( 'Birthday date', 'bogika' ); ?>*"
                       value=""/>
                <input type="text" style="display: none"
                       name="dob"
                       id="actualDate" autocomplete="birthday"
                       value=""/>
            </div>

            <!--			--><?php //if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
            <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide wrap-input">
                <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password"
                       placeholder="<?php esc_html_e( 'Password', 'woocommerce' ); ?>*"
                       required
                       id="password-field" autocomplete="new-password"/>
            </div>

            <div class="wrap-input">
                <input type="password" name="pass2" autocomplete="new-password"
                       required
                       placeholder="<?php esc_html_e( 'Repeat password', 'bogika' ); ?>*" id="password-field-2">
            </div>

            <div id="pass-notice" style="display: none">
						<span class="alert alert-danger">
							<?php _e( 'The two passwords you entered don\'t match.', 'personalize-login' ); ?>
						</span>
            </div>

            <!--			--><?php //else : ?>
            <!--				<p>--><?php //esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?><!--</p>-->
            <!--			--><?php //endif; ?>

            <?php do_action( 'woocommerce_register_form' ); ?>
            <div class="woocommerce-form-row form-row wrap-input button">
                <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                <button type="submit"
                        class="woocommerce-Button woocommerce-button button btn woocommerce-form-register__submit"
                        name="register"
                        value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
            </div>
            <?php do_action( 'woocommerce_register_form_end' ); ?>
            <div class="form-bottom-link"><a
                        href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>"><?php esc_html_e( 'I already have an account', 'personalize-login' ); ?></a>
            </div>
        </form>
    </div>
</div>
<script>
    jQuery(function () {
        $('#password-field-2').focusout(function () {
            var pass1 = $('#password-field').val(),
                pass2 = $(this).val();
            if (pass1 != pass2) {
                $('#pass-notice').show()
            }
        });
        $('#password-field-2').focusin(function () {
            $('#pass-notice').hide()
        });
    })
</script>
<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
<?php get_footer(); ?>
