<?php
/*Списание баллов в корзине и оформлении заказа
 *
 * @param WC_Order $order
 * @version 5.3.3
 */
/**
 * @param WC_Order $order
 *
 * @return array
 */
function bfw_admin_return_spisanie( $order ): array {
	$val = get_option( 'bonus_option_name' );

	if ( ! empty( $val['exclude-fees-coupons'] ) ) {
		/*Сумма товаров в корзине без учета купонов и скидок*/
		$items = $order->get_items();
		$total = 0;
		foreach ( $items as $item => $values ) {
			$price = get_post_meta( $values['product_id'], '_price', true ) * $values['quantity'];
			$total += $price;
		}

	} else {
		$total = $order->get_total();//сумма в корзине

		/*Убираем доставку из общей суммы*/
		if ( $order->get_shipping_total() > 0 ) {
			$total = $total - $order->get_shipping_total();
		}

		/*Убираем левые комиссии из общей суммы*/
		$fees = $order->get_fees();
		foreach ( $fees as $fee ) {
			$name   = $fee->get_name();
			$amount = $fee->get_amount();
			if ( $name != $val['bonus-points-on-cart'] ) {
				$total = $total - $amount;
			}
		}

	}


	$alternative = '';

	$computy_point = ( new BfwPoints )->getPoints( $order->get_customer_id() ); //всего баллов у покупателя


	/*Процент списание баллов для про*/
	if ( ( new BfwRoles )->is_pro() ) {
		$max_percent = $val['max-percent-bonuses'] ?? 100;
		$max_percent = apply_filters( 'max-percent-bonuses-filter', $max_percent, $total );
	} else {
		$max_percent = 100;
	}
	/*Процент списание баллов для про*/


	$displaynone = '';

	/*Исключение категорий */
	if ( ( new BfwRoles )->is_pro() ) {
		$categoriexs = $val['exclude-category-cashback'] ?? 'not';

		$exclude_tovar = $val['exclude-tovar-cashback'] ?? '';
		$tovars        = apply_filters( 'bfw-excluded-products-filter', explode( ",", $exclude_tovar ), $exclude_tovar, $order->get_items() );

		foreach ( $order->get_items() as $item ) {
			$_product = wc_get_product( $item->get_product_id() );
			if ( in_array( $item->get_product_id(), $tovars ) ) {
				$sum_exclud_tov = $_product->get_price() * $item['quantity'];
				$total          = $total - $sum_exclud_tov;
			}
		}
	}
	/*Исключение товаров*/


	$i = 0;
	$s = 0;
	foreach ( $order->get_items() as $item ):
		$id_tovara_vkorzine = $item['product_id'];
		$s ++;
		$product = wc_get_product( $id_tovara_vkorzine );
		if ( $product->is_on_sale() ) {
			$i ++;
			if ( ! empty( $val['spisanie-onsale'] ) ) {
				/*Исключаем возможность тратить кешбэк на товары со скидкой */
				$sum_exclud_sale = $item['data']->get_price() * $item['quantity'];
				$total           = $total - $sum_exclud_sale;
			}

		}
	endforeach;

	$user_fast_points = ( new BfwPoints )->getFastPoints( get_current_user_id() );

	$total_plus_fast = $total + $user_fast_points;

	$total_max_percent = $total_plus_fast * $max_percent / 100;
	$total_max_percent = ( new BfwPoints() )->roundPoints( $total_max_percent );/*округляем если надо*/

	$computy_point = ( new BfwPoints() )->roundPoints( $computy_point );

	if ( $total_max_percent > $computy_point ) {
		$total_max_percent = $computy_point;
	}

	$vozmojniy_ball_true = $total_max_percent - $user_fast_points;

	if ( $vozmojniy_ball_true < $user_fast_points ) {
		$vozmojniy_ball_true = $user_fast_points;
	}
	if ( $vozmojniy_ball_true < 0 ) {
		$vozmojniy_ball_true = $total_max_percent;
	}


	/*Высчитывание минимальной суммы заказа*/
	$minimal_amount = 100000;
	if ( ! empty( $val['minimal-amount'] ) ) {
		if ( $val['minimal-amount'] > 0 ) {
			$minimal_amount = $total - $val['minimal-amount'];
			if ( $minimal_amount < 0 ) {
				$minimal_amount = 0;
			}
		}
	}


	if ( $computy_point > 0 ) {


		/*если есть другие комиссии, то вычесть их из возможных баллов*/


		if ( $user_fast_points > 0 ) {
			$vozmojniy_ball_true = $vozmojniy_ball_true + $user_fast_points;
			$vozmojniy_ball_true = ( new BfwPoints() )->roundPoints( $vozmojniy_ball_true );
			if ( $total < $vozmojniy_ball_true ) {
				$total = $vozmojniy_ball_true;
			}
		}


		$vozmojniy_ball = min( $computy_point, $vozmojniy_ball_true, $total, $minimal_amount, $total_max_percent );
		$vozmojniy_ball = ( new BfwPoints() )->roundPoints( $vozmojniy_ball );/*округляем если надо*/
	}

	return [ 'computy_point' => $computy_point, 'vozmojniy_ball' => $vozmojniy_ball ];
}

add_action( 'woocommerce_order_item_add_action_buttons', function () {
	global $post;
	$id = $post->ID;
	if ( ! $id ) {
		return;
	}
	$order = wc_get_order( $id );
	$val   = get_option( 'bonus_option_name' );

	if ( $order->get_fees() ) {
		foreach ( $order->get_fees() as $fee ) {
			if ( $fee->get_name() === $val['bonus-points-on-cart'] ) {
				return;
			}
		}
	}
	?>
    <button type="button" class="button add-points" data-points="">Списати бали</button>
	<?php
} );
function bogika_admin_available_points() {
	$order_id = absint( $_POST['order_id'] );
	$order    = wc_get_order( $order_id );
	extract( bfw_admin_return_spisanie( $order ) );
	wp_send_json_success( [
		'message'        => sprintf( __( 'For this order, you can spend %s of %s %s', 'bonus-for-woo' ), $vozmojniy_ball, $computy_point, ( new BfwPoints() )->pointsLabel( 5 ) ) . ' Введіть кількість балів для списання.',
		'vozmojniy_ball' => $vozmojniy_ball,
		'computy_point'  => $computy_point,
	] );
}


add_action( 'wp_ajax_bogika_admin_available_points', 'bogika_admin_available_points' );
/* Видалення балів */
add_action( 'wp_ajax_bogika_admin_use_points', function () {
	if ( ! current_user_can( 'edit_shop_orders' ) ) {
		wp_die( - 1 );
	}

	$points = (int) sanitize_text_field( $_POST['points'] );

	if ( ! isset( $_POST['order_id'] ) ) {
		wp_send_json_error();
	}

	$order_id = absint( $_POST['order_id'] );
	$order    = wc_get_order( $order_id );

	$val = get_option( 'bonus_option_name' );

	$fee = new WC_Order_Item_Fee();
	$fee->set_amount( - $points );
	$fee->set_total( - $points );
	/* translators: %s fee amount */
	$fee->set_name( $val['bonus-points-on-cart'] );

	$new_points = ( new BfwPoints() )->getPoints( $order->get_customer_id() ) - $points;

	( new BfwPoints() )->updatePoints( $order->get_customer_id(), $new_points );

    /* Додаємо запис що бали списані */
	$prichina = sprintf(__('Use of %s', 'bonus-for-woo'), (new BfwPoints())->pointsLabel(5));
	(new BfwHistory)->add_history($order->get_customer_id(),'-',$points,$order_id,$prichina);

    $order->add_item( $fee );
	$order->calculate_totals( false );
	$order->save();

	wp_send_json_success();
} );

//add_filter( 'woocommerce_coupon_is_valid', 'disable_coupon_for_cod_orders', 10, 2 );

//function disable_coupon_for_cod_orders( $is_valid, $coupon ) {
//	// Check if COD is selected
//	if ( isset( $_POST['payment_method'] ) && $_POST['payment_method'] == 'cod' ) {
//		$is_valid = false;
//		wc_add_notice( __( 'Sorry, you cannot use a coupon for COD orders.' ), 'error' );
//	}
//	return $is_valid;
//}
