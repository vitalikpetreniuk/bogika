<?php
remove_action('woocommerce_review_order_after_order_total', 'bfw_get_cashback_in_cart');
remove_action('woocommerce_cart_totals_after_order_total', 'bfw_get_cashback_in_cart');
add_action( 'woocommerce_cart_totals_after_order_total', 'bogika_get_cashback_in_cart', 10 );
add_action( 'woocommerce_review_order_after_order_total', 'bogika_get_cashback_in_cart', 10 );

function bogika_get_cashback_in_cart(): void {
	$val       = get_option( 'bonus_option_name' );
	$percentUp = '';
	$upto      = '';
	global $woocommerce;

	if ( ! empty( $val['exclude-fees-coupons'] ) ) {
		/*сумма товаров в корзине без учета купонов и скидок*/
		$total_order = $woocommerce->cart->get_subtotal();
	} else {
		$total_order = $woocommerce->cart->total;
	}


	/*Исключение категорий*/
	$categoriexs = $val['exclude-category-cashback'] ?? 'not';
	if ( ( new BfwRoles )->is_pro() and $categoriexs != 'not' and empty( $val['addkeshback-exclude'] ) ) {

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			if ( has_term( $categoriexs, 'product_cat', $cart_item['product_id'] ) ) {
				$sum_exclud_cat = $_product->get_price() * $cart_item['quantity'];
				$total_order    = $total_order - $sum_exclud_cat;
			}
		}
	}
	/*Исключение категорий*/

	/*Исключение товаров*/
	if ( ( new BfwRoles )->is_pro() ) {
		$exclude_tovar = $val['exclude-tovar-cashback'];
		$tovars        = apply_filters( 'bfw-excluded-products-filter', explode( ",", $exclude_tovar ), $exclude_tovar );
		if ( empty( $val['addkeshback-exclude'] ) ) {/*если можно начислять кешбэк*/
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				if ( in_array( $cart_item['product_id'], $tovars ) ) {
					$categoriexs = $val['exclude-category-cashback'] ?? 'not';
					if ( ! has_term( $categoriexs, 'product_cat', $cart_item['product_id'] ) ) {/*если еще не исключены категории, то:*/
						$sum_exclude_tov = $_product->get_price() * $cart_item['quantity'];
						$total_order     = $total_order - $sum_exclude_tov;
					}
				}
			}
		}

	}
	/*Исключение товаров*/


	if ( is_user_logged_in() ) {
		$userid         = get_current_user_id();
		$cashback_title = __( 'Cashback', 'bonus-for-woo' );
		global $wpdb;
		if ( ( new BfwRoles )->is_invalve() ) {
			/*Проверяем если заказ достоин большего кэшбэка*/


			$total_all = ( new BfwPoints )->getSumUserOrders( $userid ); //находим сумму всех оплаченных заказов

			if ( ! empty( $val['exclude-fees-coupons'] ) ) {
				/*Сумма товаров в корзине без учета купонов и скидок*/
				$total_order_user = $woocommerce->cart->get_subtotal();
			} else {
				$total_order_user = $woocommerce->cart->total;
			}


			$sumbudet = $total_all + $total_order_user;

			$allrole = $wpdb->get_results( "SELECT summa_start FROM " . $wpdb->prefix . "bfw_computy" );
			$allrole = json_decode( json_encode( $allrole ), true );/* Преобразование в ассоциативный массив*/
			$allrole = array_multisort_value( $allrole, 'summa_start', SORT_ASC );

			$summa = 0;
			foreach ( $allrole as $a ) {
				if ( abs( $a['summa_start'] - $sumbudet ) <= $sumbudet and $sumbudet >= $a['summa_start'] ) {
					$summa = $a['summa_start'];
				}
			}


			$you_next_role = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "bfw_computy WHERE summa_start='$summa'" );

			$this_percent = ( new BfwRoles )->getRole( $userid );
			$percent      = $this_percent['percent'] ?? 0;
			$percentUp    = ' ' . $percent . '%';
			if ( $you_next_role ) {
				if ( $you_next_role[0]->percent != $this_percent['percent'] ) {
					$percent   = $you_next_role[0]->percent;
					$percentUp = ' ' . $percent . "% ▲";
				}
				/*Проверяем если заказ достоин большего кэшбэка*/
			}


		}


	}

	/*
	 * @version 5.1.2
	 * */
	if ( empty( $percent ) ) {
		$percent = 0;
	}

	if ( ! empty( $val['cashback-for-shipping'] ) ) {
		/*Убираем сумму доставки из кешбэка*/
		$tot                 = $total_order - $woocommerce->cart->shipping_total;
		$cashback_this_order = $percent * $tot / 100;

	} else {
		$cashback_this_order = $percent * $total_order / 100;
	}

	if ( $woocommerce->cart->get_applied_coupons() ) {
		$cashback_this_order = 0;
	}

	if ( ( new BfwRoles )->is_pro() ) {
		/*Минимальная сумма заказа*/
		if ( isset( $val['minimal-amount'] ) ) {
			if ( $total_order < $val['minimal-amount'] ) {
				if ( ! empty( $val['minimal-amount-cashback'] ) ) {
					$cashback_this_order = 0;
				}

			}
		}
		/*Минимальная сумма заказа*/


		/* Товар "баллы". На него кешбэк 100% */
		if ( ! empty( $val['buy_balls-cashback'] ) ) {
			$bay_balls = array( $val['buy_balls-cashback'] );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				if ( in_array( $cart_item['product_id'], $bay_balls ) ) {
					$sum_percent = 0;
					if ( $percent > 0 ) {
						$sum_percent = $_product->get_price() / 100 * $percent * $cart_item['quantity'];/*убираем лишний кешбэк на кешбэк*/
					}
					$sum_bay_balls       = $_product->get_price() * $cart_item['quantity'] - $sum_percent;
					$cashback_this_order = $cashback_this_order + $sum_bay_balls;
				}
			}
		}
		/* Товар "баллы". На него кешбэк 100% */
	}


	if ( is_user_logged_in() ) {
		$userid             = get_current_user_id();
		$upto               = '';
		$computy_point_fast = ( new BfwPoints )->getFastPoints( $userid );
		if ( ! empty( $val['yous_balls_no_cashback'] ) and ( new BfwRoles )->is_pro() and $computy_point_fast > 0 ) {
			/* запрещено начислять кешбэк когда клиент тратит баллы*/
			$cashback_this_order = 0;
		}
	} else {
		$percentUp = '';
	}

	if ( $cashback_this_order > 0 ) {

		?>
        <tr class="order-cashback">
            <th><span class="order-cashback-title"><?php echo $cashback_title . $percentUp; ?></span></th>
            <td data-title="<?php echo $cashback_title . $percentUp; ?>">
                <span class="order-cashback-value"><?php
	                echo ( new BfwPoints() )->roundPoints( $cashback_this_order ) . ' ';/*округляем если надо*/

	                echo ( new BfwPoints() )->howLabel( $upto, $cashback_this_order );
	                ?>
                </span></td>
        </tr>
		<?php
	}

}
