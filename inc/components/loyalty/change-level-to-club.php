<?php
/* Код при створенні нового замовлення перевіряє чи ввімкнута зміна дефолтного порогу
для того щоб потрапити в клуб богіки*/
//add_action( 'woocommerce_order_status_completed', 'change_level_to_club', 10, 1 );

function change_level_to_club( $order_id ) {
	/* @var WC_Order $order */
	/* Перевірка чи ввімкнута дана опція*/
	if ( ! get_field( 'turn_on_club_level_change', 'option' ) ) {
		return;
	}
	$order = wc_get_order( $order_id );
	/* Якщо покупець незареєстрований - вихід */
	if ( ! $customer_id = $order->get_customer_id() ) {
		return;
	}

	/* Новий поріг входу */
	$new_level = get_field( 'new_level_club_participation', 'option' );

	/* Отримати замовлення людини за період */
	$orders = new WP_Query( array(
		'post_type'      => 'shop_order',
		'post_status'    => 'wc-completed',
		'posts_per_page' => - 1,
		'meta_key'       => '_customer_user',
		'meta_value'     => $order->get_customer_id(),
		'fields'         => 'ids',
		'date_query'     => [
			[
				'after'     => get_field( 'data_start_change_level', 'option' ),
				'before'    => get_field( 'data_end_change_level', 'option' ),
				'inclusive' => true,
			],
		],
	) );


//	/* Сума замовлень */
	$sum_orders_by_period = array_reduce( $orders->posts, function ( $sum, $id ) {
//		$sum += (int) get_post_meta( $id, 'bonuses_total', true );
		return $sum += wc_get_order( $id )->get_subtotal();
	}, 0 );

	/* Якщо сума перевищила поріг - міняємо статус людини  */
	if ( $sum_orders_by_period >= $new_level ) {
		update_user_meta( $customer_id, 'bfw_status', 1 );
	}
}


/* При активації тимчасового ліміту на статус члена в клубі змінювати відповідне налаштування в плагіні*/
add_filter( 'acf/update_value/key=field_64b794403215d', function ( $value, $post_id, $field, $original ) {
//	var_dump( $value );
	global $wpdb;
	$summa_start = $value;
	if ((int)$value === 0) {
		$summa_start = 5000;
	};
	$wpdb->update( $wpdb->prefix . "bfw_computy",
		array( 'summa_start' => $summa_start ),
		array( 'id' => 1 )
	);

	return $value;
}, 10, 4 );
