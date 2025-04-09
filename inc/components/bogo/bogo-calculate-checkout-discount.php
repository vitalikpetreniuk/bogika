<?php
/* Порахувати бого знижку */
function bogo_calculate_checkout_discount() {
	if ( ! WC()->cart->get_cart() ) {
		return 0;
	}
	$disc = 0;
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		if ( WC_BOGOF_Cart::is_valid_discount( $cart_item['data'] ) ) {
			$disc += $cart_item['data']->_bogof_discount->get_base_price() * array_shift($cart_item['_bogof_discount']);
		}
	}

	return $disc;
}
