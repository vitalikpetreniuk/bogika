<?php

/* 
add_action( 'manage_shop_order_posts_custom_column', function ( $column, $post_id ) {
	$order = wc_get_order( $post_id );
	if ( ! $order->has_shipping_method( WC_UKR_SHIPPING_NP_SHIPPING_NAME ) && ! (int) wcus_get_option( 'ttn_any_shipping' ) ) {
		return;
	}
	if ( $column == 'nova_poshta_ttn_number' ) {
		echo '<div class="ttn-phone" style="display: none">' . $order->get_billing_phone() . '</div>';
	}
}, 11, 2 );
*/
