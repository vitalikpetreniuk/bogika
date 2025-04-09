<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );
?>
	<div class="registration-section">
		<h1><?php esc_html_e('Restore password', 'bogika'); ?></h1>
		<div class="user-form">
			<form method="post" class="woocommerce-ResetPassword lost_reset_password lost_reset_password2">

				<div class="woocommerce-form-row wrap-input woocommerce-form-row--first form-row form-row-first">
					<input class="woocommerce-Input woocommerce-Input--text input-text" required placeholder="<?php esc_html_e( 'Email', 'woocommerce' ); ?>*" type="text" name="user_login" id="user_login" autocomplete="username" />
				</div>

				<?php do_action( 'woocommerce_lostpassword_form' ); ?>

				<div class="woocommerce-form-row wrap-input button form-row">
					<input type="hidden" name="wc_reset_password" value="true" />
					<button type="submit" class="btn woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> btn" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php esc_html_e( 'Reset password', 'woocommerce' ); ?></button>
				</div>

				<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
				<p class="form-bottom-link"><a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>"><?php esc_html_e( 'Sign in', 'bogika' ); ?></a></p>
			</form>
		</div>
	</div>

<?php
do_action( 'woocommerce_after_lost_password_form' );
