<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
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

do_action( 'woocommerce_before_reset_password_form' );
?>
	<div class="registration-section">
		<h1><?php esc_html_e( 'Set password', 'bogika' ); ?></h1>
		<div class="user-form">
			<form method="post" class="woocommerce-ResetPassword lost_reset_password lost_reset_password1">

				<p><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'woocommerce' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>

				<div class="woocommerce-form-row wrap-input woocommerce-form-row--first form-row form-row-first">
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text"
					       name="password_1" placeholder="<?php esc_html_e( 'New password', 'woocommerce' ); ?>*"
					       id="password_1" autocomplete="new-password"/>
				</div>
				<div class="woocommerce-form-row wrap-input woocommerce-form-row--last form-row form-row-last">
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text"
					       placeholder="<?php esc_html_e( 'Re-enter new password', 'woocommerce' ); ?>*"
					       name="password_2" id="password_2" autocomplete="new-password"/>
				</div>

				<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>"/>
				<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>"/>

				<?php do_action( 'woocommerce_resetpassword_form' ); ?>

				<div class="woocommerce-form-row wrap-input form-row button">
					<input type="hidden" name="wc_reset_password" value="true"/>
					<button type="submit"
					        class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> btn"
					        value="<?php esc_attr_e( 'Save', 'woocommerce' ); ?>"><?php esc_html_e( 'Save', 'woocommerce' ); ?></button>
				</div>

				<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>
				<p class="form-bottom-link"><a href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>"><?php esc_html_e( 'Sign in', 'bogika' ); ?></a></p>
			</form>
		</div>
	</div>


<?php
do_action( 'woocommerce_after_reset_password_form' );

