<?php
add_filter( 'woocommerce_billing_fields', 'bogika_fields' );
function bogika_fields( $fields ) {
	$chosen_methods_pickup  = WC()->session->get( 'chosen_shipping_methods' );
	$chosen_shipping_pickup = $chosen_methods_pickup[0];

	unset( $fields['billing_company'] );
	if ( ( isset( $_POST['shipping_method'] ) &&
	       strpos( $_POST['shipping_method'][0], 'local_pickup' ) !== false ) || strpos( $chosen_shipping_pickup, 'local_pickup' ) !== false ) {
		$hidearr = [ 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_postcode', 'billing_state' ];
		foreach ( $hidearr as $item ) {
			unset( $fields[ $item ] );
		}
	}

	$fields['billing_phone_dub'] = $fields['billing_phone'];
	unset( $fields['billing_phone_dub']['validate'] );

	foreach ( $fields as &$field ) {
		$field['placeholder'] = $field['label'];
		unset( $field['label'] );
		unset( $field['label_class'] );
	}

	$fields['billing_patronym'] = array(
		'placeholder' => __( 'Patronym', 'bogika' ),
		'type'        => 'text',
		'priority'    => 30,
		'required'    => false,
	);
	if ( WC()->session->get( 'chosen_shipping_methods' ) !== null && WC()->session->get( 'chosen_shipping_methods' )[0] !== 'local_pickup:4' ) {
		$fields['billing_patronym']['required'] = 1;
	}

	$fields['billing_phone']['placeholder']     = __( 'Phone number', 'bogika' );
	$fields['billing_phone_dub']['placeholder'] = __( 'Phone number', 'bogika' );
	$fields['billing_phone_dub']['autocomplete'] = 'tel-national';
	$fields['billing_email']['placeholder']     = __( 'Email', 'bogika' );

	return $fields;
}

add_filter( 'woocommerce_checkout_fields', function ( $fields ) {
	if ( isset( $fields['order'] ) ) {
		unset( $fields['order']['order_comments']['label'] );
	}

	return $fields;
} );

remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );

add_filter( 'woocommerce_create_account_default_checked', '__return_true' );
//add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );

add_action( 'woocommerce_review_order_after_shipping', 'woocommerce_checkout_payment', 10 );

add_action( 'woocommerce_after_checkout_billing_form', function () {
	?>
    <div class="delivery-block">
        <h2><?php esc_html_e( 'Shipping method', 'bogika' ); ?></h2>
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

		<?php endif; ?>
    </div>
	<?php
}, 9 );

add_filter( 'woocommerce_get_terms_and_conditions_checkbox_text', function ( $text ) {
	$privacy_page_id = wc_privacy_policy_page_id();
	$privacy_link    = $privacy_page_id ? '<a href="' . esc_url( get_permalink( $privacy_page_id ) ) . '" class="woocommerce-privacy-policy-link" target="_blank">' . _x( 'privacy policy', 'checkout', 'woocommerce' ) . '</a>' : __( 'privacy policy', 'woocommerce' );

	return sprintf( __( 'I have read and agree to the website %s', 'bogika' ), $privacy_link );
} );

add_action( 'woocommerce_after_checkout_billing_form', function () { ?>
    <div class="wc-pickup-fields" id="wc-pickup-fields" style="display: none">
		<?php the_field( 'pickup_method_shipping', 'option' ) ?>
    </div>
	<?php
} );

add_filter( 'woocommerce_form_field_args', function ( $args, $key ) {
	if ( in_array( $key, [ 'ukrposhta_shippping_warehouse', 'ukrposhta_shippping_city' ] ) ) {
		unset( $args['label'] );
		$args['placeholder'] .= '*';
	}

	return $args;
}, 10, 2 );

function customize_wc_errors( $error ) {
	if ( strpos( $error, __( 'Billing', 'bogika' ) . ' ' ) !== false ) {
		$error = str_replace( __( 'Billing', 'bogika' ) . ' ', "", $error );
	}

	return $error;
}

add_filter( 'woocommerce_add_error', 'customize_wc_errors' );

add_filter( 'woocommerce_update_order_review_fragments', function ( $fragments ) {
	ob_start();
	wc_cart_totals_shipping_html();
	$fragments['div.woocommerce-shipping-totals'] = ob_get_clean();

	return $fragments;
} );

remove_filter( 'woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_subtotal' );

add_action( 'woocommerce_checkout_before_terms_and_conditions', 'bogika_order_comment_field', 11 );

function bogika_order_comment_field() {
	$checkout = WC()->checkout();
	?>
    <div class="woocommerce-additional-fields">
		<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

		<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>

			<?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>

                <h3><?php esc_html_e( 'Additional information', 'woocommerce' ); ?></h3>

			<?php endif; ?>

            <div class="woocommerce-additional-fields__field-wrapper">
				<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
            </div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
    </div>
	<?php
}

add_filter( 'woocommerce_form_field_args', function ( $attr ) {
	$attr['class'][] = 'wrap-input';

	return $attr;
} );

add_filter( 'woocommerce_form_field', function ( $field ) {
	return str_replace( [ '<p', '</p>' ], [ '<div', '</div>' ], $field );
} );

remove_action( 'woocommerce_before_checkout_form', 'bfwoo_spisaniebonusov_in_checkout', 9 );


/**
 * Add a 1% surcharge to your cart / checkout
 * change the $percentage to set the surcharge to a value to suit
 */
add_action( 'woocommerce_cart_calculate_fees', 'woocommerce_custom_surcharge' );
function woocommerce_custom_surcharge() {
	global $woocommerce;

	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
		return;
	}

	$user_id = get_current_user_id();

//	if ( ! $user_id || get_user_meta( $user_id, 'used_referral_discount', true ) ) {
//		return;
//	}

	$total = array_reduce( WC()->cart->get_cart(), function ( $carry, $cart_item ) {
		/* @var WC_Product $_product*/
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        $product_id = $_product->get_id();

        if (has_term(apply_filters( 'wpml_object_id', 104, 'product_cat' ), 'product_cat', $product_id) ||
            has_term(apply_filters( 'wpml_object_id', 105, 'product_cat' ), 'product_cat', $product_id) ||
            $_product->is_on_sale()
        ) {
            $price = 0;
        }else {
            $price = $_product->get_price() * $cart_item['quantity'];
        }
        return $carry + $price;
	} );

//    var_dump($total);

	if ( get_user_meta( $user_id, 'bfw_points_referral_invite', true ) ) {
		$percentage = 0.05;

		$discount = - ( $woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total ) * $percentage;
		$woocommerce->cart->add_fee( __( 'Referral discount', 'bogika' ), $discount, true, '' );
	}
}

add_action( 'woocommerce_checkout_order_created', 'save_user_used_referral_discount' );

/* @var WC_Order $order */
function save_user_used_referral_discount( $order ) {
	if ( ! get_current_user_id() && ! $order->get_fees() ) {
		return;
	}

	$user_id = get_current_user_id();

	foreach ( $order->get_fees() as $fee ) {
		if ( $fee->get_name() == __( 'Referral discount', 'bogika' ) ) {
			update_user_meta( $user_id, 'used_referral_discount', '1' );
		}
	}
}

// Hook into woocommerce_new_order_item action
add_action( 'woocommerce_checkout_order_created', 'add_shipping_method_id_to_order', 10, );

/**
 * @param WC_Order $order
 *
 * @return void
 */
function add_shipping_method_id_to_order( $order ) {
	$shipping_methods = $order->get_shipping_methods();
	$method           = array_shift( $shipping_methods );

	global $wpdb;
	$q = $wpdb->prepare( 'INSERT INTO wp_woocommerce_order_items (order_item_name, order_item_type, order_id) VALUES (%s, %s, %s)', array(
		'shipping_method_id',
		$method['method_id'],
		$order->get_id(),
	) );

	$wpdb->query( $q );
}

add_action( 'woocommerce_after_checkout_validation', 'bogika_validate_phone_number_length', 10, 2 );

function bogika_validate_phone_number_length( $fields, $errors ) {
	if ( WC()->customer ) {
		$current_cc = WC()->customer->get_billing_country();
	}

	if ( $current_cc && $current_cc == 'UA' && mb_strlen( $fields['billing_phone'] ) < 13 ) {
		$errors->add( 'validation', sprintf( __( 'Minimum phone length equals to %d symbols.', 'bogika' ), 13 ) );
	}
}

add_filter( 'woocommerce_get_order_address', function ( $address, $type, $tthis ) {
//	var_dump($tthis);
	$address['patronym'] = $tthis->billing_patronym;

	return $address;
}, 11, 3 );

add_filter( 'woocommerce_formatted_address_replacements', function ( $replacements, $args ) {
	$replacements['{name}'] = sprintf(
	/* translators: 1: first name 2: last name */
		_x( '%1$s %2$s %3$s', 'full name', 'woocommerce' ),
		$args['first_name'],
		$args['last_name'],
		$args['patronym']
	);

	return $replacements;
}, 10, 2 );

add_filter( 'woocommerce_checkout_create_order', function ( $order ) {
	/* @var WC_Order $order */
	if ( ! empty( $_POST['billing_ukrposhta_type'] ) ) {
		$order->add_meta_data( 'billing_ukrposhta_type', esc_attr( $_POST['billing_ukrposhta_type'] ) );
	}

	if ( isset( $_POST['billing_patronym'] ) ) {
		$order->update_meta_data( 'mrkvup_recipient_surname', $_POST['billing_patronym'] );

		$order->set_billing_company($_POST['billing_patronym']);
		$order->set_shipping_company($_POST['billing_patronym']);
    }
} );

add_action( "after_plugin_row_woo-ukrposhta-pro/morkvaup-plugin.php", function ($plugin_file, $plugin_data, $status ) {
	echo '<td colspan="4" class="plugin-update colspanchange">
                    <div class="update-message notice inline notice-warning notice-alt post-shiny-updates">
                        <p>
                        Не оновлювати, тільки вручну!
                        </p>
                    </div>
                </td>';
}, 10, 3);
add_action( "after_plugin_row_wc-liqpay/wc-liqpay.php", function ($plugin_file, $plugin_data, $status ) {
	echo '<td colspan="4" class="plugin-update colspanchange">
                    <div class="update-message notice inline notice-warning notice-alt post-shiny-updates">
                        <p>
                        Не оновлювати, тільки вручну!
                        </p>
                    </div>
                </td>';
}, 10, 3);


// Change billing phone number before saving it to the order
add_filter( 'woocommerce_checkout_process', 'change_billing_phone_number', 10 );
function change_billing_phone_number() {
	// Get the billing phone number from the posted data
	$billing_phone = isset( $_POST['billing_phone'] ) ? sanitize_text_field( $_POST['billing_phone'] ) : '';

	if ( str_starts_with( '+380', $billing_phone ) ) {
		$_POST['billing_phone'] = str_replace( [ '+38', '(', ')', '-' ], '', $billing_phone );
	}
}

add_filter('wc_bogof_discount_line_subtotal_prefix', function () {
    return _x("Subtotal",'mini cart bogo', 'bogika').":&nbsp";
});

//if (!is_cart()) {
	//add_filter( 'woocommerce_cart_item_price', array( (new WC_BOGOF_Cart_Template), 'before_cart_item_price' ), -1, 2 );
	//add_filter( 'woocommerce_cart_item_price', array( 'WC_BOGOF_Cart_Template', 'after_cart_item_price' ), 9999, 3 );
	//add_filter( 'woocommerce_cart_item_subtotal', array( 'WC_BOGOF_Cart_Template', 'cart_item_subtotal' ), 9999, 2 );
//}

add_action( 'woocommerce_before_calculate_totals', 'one_applied_coupon_only', 10, 1 );
function one_applied_coupon_only( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    // For more than 1 applied coupons only
    if (  sizeof($cart->get_applied_coupons()) > 1 && $coupons = $cart->get_applied_coupons() ){
        // Remove the first applied coupon keeping only the last appield coupon
        $cart->remove_coupon( reset($coupons) );
    }
}

add_filter('wcal_reminder_email_line_subtotal_header', function () {
    return 'Сума';
});

add_filter( 'woocommerce_get_return_url', 'add_get_param_to_return_url', 10, 2 );

function add_get_param_to_return_url( $url, $order ): string {
	return add_query_arg( array(
		'wc_order_id' => $order->get_id(),
	), $url );
}