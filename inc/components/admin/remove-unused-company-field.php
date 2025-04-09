<?php

// Admin billing fields
add_filter( 'woocommerce_admin_billing_fields', 'bogika_admin_billing_fields', 10, 1 );
add_filter( 'woocommerce_admin_shipping_fields', 'bogika_admin_shipping_fields', 10, 1 );
function bogika_admin_billing_fields( $billing_fields ) {
	global $pagenow;
	if ( $pagenow === 'post-new.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'shop_order' ) {
		unset( $billing_fields['company'] );
	}

	return $billing_fields;
}

function bogika_admin_shipping_fields( $shipping_fields ) {
	global $pagenow;
	if ( $pagenow === 'post-new.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'shop_order' ) {
		unset( $shipping_fields['company'] );
	}

	return $shipping_fields;
}
