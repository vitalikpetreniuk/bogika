<?php
require_once 'components/loyalty/admin-history-with-authors.php';

add_filter( 'bfw-excluded-products-filter', 'woo_excluded_products', 10, 2 );

function woo_excluded_products( $value = [], $string = '' ): array {
	$sale  = wc_get_product_ids_on_sale();
	$args  = array(
		'post_type'      => 'product',
		'posts_per_page' => PHP_INT_MAX,
		'fields'         => 'ids',
		'tax_query'      => array(
			[
				'taxonomy' => 'product_cat',
				'field'    => 'id',
				'terms'    => [
					apply_filters( 'wpml_object_id', 78, 'product_cat' ),
					apply_filters( 'wpml_object_id', 104, 'product_cat' )
				]
			],
		)
	);
	$query = new WP_Query( $args );

	return array_merge( $query->posts, $sale, $value );
}

/**
 * Отримати суму товарів на які нараховуються бонуси
 * @return float|int|mixed
 */
function countCartTotalForBonuses() {
	$excluded_products = woo_excluded_products();
	$total_order       = WC()->cart->get_subtotal();

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		if ( in_array( $cart_item['product_id'], $excluded_products ) ) {
			$sum_exclud_tov = $_product->get_price() * $cart_item['quantity'];
			$total_order    = $total_order - $sum_exclud_tov;
		}
	}

	return $total_order;
}

require_once 'components/loyalty/bonuses_total.php';

add_filter( 'bfw-update-points-filter', 'limitMaxPercentBonuses', 10, 2 );

function limitMaxPercentBonuses( $max_percent, $user_id = false ) {
	$user_fast_points = ( new BfwPoints )->getFastPoints( get_current_user_id() );
	$computy_point    = ( new BfwPoints )->getPoints( get_current_user_id() ); //всего баллов у покупателя

	if ( ! $user_fast_points || ! $computy_point ) {
		return $max_percent;
	}
	if ( ( $user_fast_points / $computy_point ) < 2 ) {
		$user_fast_points = $computy_point / 2;
		$max_percent      = $user_fast_points * 100 / countCartTotalForBonuses();
	}

	return min( $max_percent, 50 );
}

require_once 'components/loyalty/spisaniebonusov_in_checkout.php';


/* Отримуємо суму на яку людина має купити щоб стати членом клубу*/
function bogika_sum_to_become_a_member() {
	if ( get_field( 'turn_on_club_level_change', 'option' ) ) {
		return get_field( 'new_level_club_participation', 'option' );
	}

	return 5000;
}

require_once 'admin-loyalty-program.php';
require_once 'components/loyalty/change-level-to-club.php';
require_once 'components/loyalty/reset-bonuses-once-a-year.php';
require_once 'components/loyalty/cashback_in_cart.php';
require_once 'components/loyalty/forbid-bonuses-if-coupon.php';

