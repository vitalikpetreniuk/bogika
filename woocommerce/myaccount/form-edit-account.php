<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
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

do_action( 'woocommerce_before_edit_account_form' ); ?>

<div class="user-form">
    <form class="woocommerce-EditAccountForm edit-account" action=""
          method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?>>
        <div class="heading">
            <h2><?php esc_html_e( 'Personal info', 'bogika' ); ?></h2>
        </div>
        <div class="contact-details">
            <div class="container-form flexbox">
                <?php do_action( 'woocommerce_edit_account_form_start' ); ?>

                <div class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first wrap-input">
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                           name="account_first_name"
                           id="account_first_name" autocomplete="given-name"
                           placeholder="<?php esc_html_e( 'First name', 'bogika' ); ?>"
                           value="<?php echo esc_attr( $user->first_name ); ?>"/>
                </div>
                <div class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last wrap-input">
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text"
                           name="account_last_name"
                           id="account_last_name" autocomplete="family-name"
                           placeholder="<?php esc_html_e( 'Last name', 'bogika' ); ?>"
                           value="<?php echo esc_attr( $user->last_name ); ?>"/>
                </div>
                <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide wrap-input only">
                    <?php
                    $dob = esc_attr( $user->dob );
                    if(strpos($dob, '-')!=0) $new_dob = substr($dob,8,2).'-'.substr($dob,5,2).'-'.substr($dob,0,4);
                    else $new_dob = substr($dob,6,2).'-'.substr($dob,4,2).'-'.substr($dob,0,4);
                    ?>
                    <input type="text" class="woocommerce-Input woocommerce-Input--birthday input-text"
                           name="dob2"
                           id="account_birthday" autocomplete="birthday"
                           placeholder="<?php esc_html_e( 'Birthday date', 'bogika' ); ?>"
                           value="<?php echo $new_dob; ?>"/>
                    <input type="text" style="display: none"
                           name="dob"
                           id="actualDate" autocomplete="birthday"
                           value="<?php echo esc_attr( $user->dob ); ?>"/>
                </div>
                <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide wrap-input">
                    <input type="email" class="woocommerce-Input woocommerce-Input--email input-text"
                           name="account_email"
                           id="account_email" autocomplete="email"
                           placeholder="<?php esc_html_e( 'Email', 'bogika' ); ?>"
                           value="<?php echo esc_attr( $user->user_email ); ?>"/>
                </div>
                <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide wrap-input">
                    <input type="text" class="woocommerce-Input woocommerce-Input--phone input-text"
                           name="billing_phone" id="billing_phone"
                           placeholder="<?php esc_html_e( 'Phone number', 'bogika' ); ?>"
                           value="<?php echo esc_attr( $user->billing_phone ); ?>"/>
                </div>
            </div>
<!-- 
            <div>
                <?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
                <button type="submit"
                        class="woocommerce-Button btn default button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"
                        name="save_account_details"
                        value="<?php esc_attr_e( 'Save', 'bogika' ); ?>"><?php esc_html_e( 'Save', 'bogika' ); ?></button>
                <input type="hidden" name="action" value="save_account_details"/>
            </div> -->
        </div>

        <div class="contact-details new">
            <fieldset>
                <h3><?php esc_html_e( 'Password change', 'woocommerce' ); ?></h3>
                <div class="container-form">
                    <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide wrap-input">
                        <input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
                               name="password_current" id="password_current"
                               placeholder="<?php esc_html_e('Enter current password', 'bogika'); ?>"
                               autocomplete="off"/>
                    </div>
                    <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide wrap-input">
                        <input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
                               name="password_1" id="password_1" autocomplete="off"
                               placeholder="<?php esc_html_e('Type new password', 'bogika'); ?>"
                        />
                    </div>
                    <div class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide wrap-input">
                        <input type="password" class="woocommerce-Input woocommerce-Input--password input-text"
                               name="password_2" id="password_2" autocomplete="off"
                               placeholder="<?php esc_html_e('Retype new password', 'bogika'); ?>"
                        />
                    </div>
                </div>
            </fieldset>

            <?php do_action( 'woocommerce_edit_account_form' ); ?>

            <div>
                <?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
                <button type="submit"
                        class="woocommerce-Button btn default button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"
                        name="save_account_details"
                        value="<?php esc_attr_e( 'Save', 'bogika' ); ?>"><?php esc_html_e( 'Save', 'bogika' ); ?></button>
                <input type="hidden" name="action" value="save_account_details"/>
            </div>

            <?php do_action( 'woocommerce_edit_account_form_end' ); ?>
        </div>
    </form>

</div>

<script>
	<?php if (isset( $_GET['logged'] ) || isset( $_GET['sign_up'] )) : ?>
	<?php isset( $_GET['logged'] ) ? $event = 'login' : $event = 'sign_up';  ?>
    dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
    dataLayer.push({
        event: "<?= $event ?>",
        user_id: "<?= get_current_user_id() ?>",
        ecommerce: {
            method: "email"
        }
    });
	<?php endif; ?>
</script>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
