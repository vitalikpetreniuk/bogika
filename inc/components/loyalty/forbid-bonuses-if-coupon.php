<?php
add_filter( 'bfw-completed-points', 'bogika_forbid_bonuses_if_coupon', 10, 3 );

/**
 * Ставимо бонуси в 0 якщо в замовленні використовувався купон
 *
 * @param $computy_point_new
 * @param int $order_id
 * @param WC_Order $order
 *
 * @return int
 */
function bogika_forbid_bonuses_if_coupon( $computy_point_new, $order_id, $order ) {
	if ( $order->get_coupons() ) {
		return 0;
	}

	return $computy_point_new;
}
