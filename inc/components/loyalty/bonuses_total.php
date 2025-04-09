<?php
/**
 * Збереження суми на яку може нараховуватися бали
 * щоб не рахувати це динамічно кожен роз
 *
 * @param mixed $order_id order id or order object
 *
 * @return float|int|mixed
 */
function countOrderTotalForBonuses( $order_id ) {
	if ( is_numeric( $order_id ) ) {
		$order = wc_get_order( $order_id );
	} else {
		$order = $order_id;
		$order_id = $order->get_id();
	}
	$excluded_products = woo_excluded_products();
	$total_order       = $order->get_subtotal();

	foreach ( $order->get_items() as $order_item ) {
		/* @var WC_Order_Item $order_item */
		if ( in_array( $order_item->get_product_id(), $excluded_products ) ) {
			$total_order -= $order_item['total'];
		}
	}

    if ($order->get_coupons()) {
	    $total_order = 0;
    }

	update_post_meta( $order_id, 'bonuses_total', $total_order );
}

add_action( 'woocommerce_saved_order_items', 'countOrderTotalForBonuses', 1, 2 );
add_action( 'woocommerce_checkout_order_created', 'countOrderTotalForBonuses', 1, 2 );

add_action( 'woocommerce_admin_order_totals_after_discount', function ( $order_id ) {
	$order         = wc_get_order( $order_id );
	$bonuses_total = get_post_meta( $order_id, 'bonuses_total', true )
	?>
    <tr>
        <td class="label">Сума на яку можуть нараховуватися бонуси</td>
        <td width="1%"></td>
        <td class="total">
			<?php echo wc_price( $bonuses_total ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </td>
    </tr>
	<?php if ( $order->get_customer_id() !== null &&
               $bonuses_total &&
               get_user_meta( $order->get_customer_id(), 'bfw_status', true ) ) : ?>
        <tr>
            <td class="label">Балів за це замовлення (при статус виконано)</td>
            <td width="1%"></td>
            <td class="total">
				<?= round( $bonuses_total * 0.03 ); ?>
            </td>
        </tr>
	<?php endif; ?>
	<?php
} );
