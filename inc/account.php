<?php
//Заміна класів на такі як в верстці
add_filter( 'woocommerce_account_menu_item_classes', function ( $classes ) {
	return str_replace( 'is-active', 'active', $classes );
} );

add_filter( 'woocommerce_save_account_details_required_fields', 'wc_save_account_details_required_fields' );
function wc_save_account_details_required_fields( $required_fields ) {
    unset( $required_fields['account_display_name'] );
    unset( $required_fields['account_last_name'] );

    return $required_fields;
}

// Зберегти дані при реєстрації
function action_woocommerce_save_account_details( $user_id ) {
    if ( isset( $_POST['billing_phone'] ) && ! empty( $_POST['billing_phone'] ) ) {
        update_user_meta( $user_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }
    if ( isset( $_POST['dob'] ) && ! empty( $_POST['dob'] ) ) {
        update_user_meta( $user_id, 'dob', sanitize_text_field( $_POST['dob'] ) );
    }
}

/* Додати помилки при надсиланні форми реєстрації*/
add_action('woocommerce_save_account_details_errors', function ($errors) {

});

add_action( 'woocommerce_save_account_details', 'action_woocommerce_save_account_details', 10, 1 );

if (!is_admin()) {
    add_filter( 'woocommerce_account_menu_items', 'bogika_account_menu_items', 99 );
}

function bogika_account_menu_items() {
    $userid   = get_current_user_id();
    $newitems = [
        'edit-account' => __( 'Personal info', 'bogika' ),
        'orders'       => __( 'History of orders', 'bogika' ),
    ];

    if (class_exists('BfwRoles')) {
        $nextrole = ( new BfwRoles )->getNextRole( $userid );
        if ( $nextrole['status'] !== 'admin' ) {
            $newitems['bonuses'] = __( 'Club BOGIKA', 'bogika' );
        }
    }

    $newitems['my-wish-list'] = __( 'Wish list', 'bogika' );
    $newitems['customer-logout'] = __( 'Logout', 'woocommerce' );

    return $newitems;
}

add_action( 'template_redirect', 'bbloomer_my_account_redirect_to_downloads' );

function bbloomer_my_account_redirect_to_downloads() {
    if ( is_account_page() && empty( WC()->query->get_current_endpoint() ) ) {
        wp_safe_redirect( wc_get_account_endpoint_url( 'edit-account' ) );
        exit;
    }
}

function woocom_validate_extra_register_fields( $username, $email, $validation_errors ) {
    if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
        $validation_errors->add( 'billing_first_name_error', __( 'First Name is required!', 'woocommerce' ) );
    }
    if ( $_POST['password'] !== $_POST['pass2'] ) {
        $validation_errors->add( "passwords_doesnt_match", __( "Password doesn't match", 'bogika' ) );
    }

    return $validation_errors;
}

add_action( 'woocommerce_register_post', 'woocom_validate_extra_register_fields', 10, 3 );

/**
 * Збереження полів на сторінці акаунту
 */
function wooc_save_extra_register_fields( $customer_id ) {
    if ( isset( $_POST['billing_phone'] ) ) {
        // Phone input filed which is used in WooCommerce
        update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
    }
    if ( isset( $_POST['dob'] ) ) {
        // DOB input filed which is used in WooCommerce
        update_user_meta( $customer_id, 'dob', sanitize_text_field( $_POST['dob'] ) );
    }
    if ( isset( $_POST['billing_first_name'] ) ) {
        //First name field which is by default
        update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
        // First name field which is used in WooCommerce
        update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
    }
    if ( isset( $_POST['billing_last_name'] ) ) {
        // Last name field which is by default
        update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
        // Last name field which is used in WooCommerce
        update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
    }

    if ( isset( $_POST['password'] ) ) {
        wp_update_user(
            array(
                'ID'        => $customer_id,
                'user_pass' => $_POST['password'],
            )
        );
    }
}

add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );

/* Забрати текст політики конфіденційності */
remove_action( 'woocommerce_register_form', 'wc_registration_privacy_policy_text', 20 );

/* Прибрати copyright плагіна bonus for woo */
remove_action( 'computy_copyright', array( 'BfwFunctions', 'computy_copyright' ), 25 );

add_filter( 'woocommerce_registration_redirect', function ( $redirect ) {
	return $redirect . '?sign_up=true';
} );
add_filter( 'woocommerce_login_redirect', function ( $redirect ) {
	return $redirect . '?logged=true';
} );