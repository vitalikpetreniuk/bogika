<?php
add_action( 'wp_ajax_generateliqpay', 'liqpay_link_to_pay' );

function liqpay_link_to_pay() {
	require_once 'LiqPay.php';
	$public_key  = 'i91571171118';
	$private_key = 'ZzjTQInHYM1PZSvoVgnrXsa1fDCB3iLsP5WBMPct';

	$liqpay = new LiqPay( $public_key, $private_key );
	$res    = $liqpay->api( "request", array(
		'action'      => 'payqr',
		'version'     => '3',
		'amount'      => $_POST['amount'],
		'currency'    => 'UAH',
		'description' => $_POST['description'],
		'order_id'    => absint( $_POST['order_id'] ),
	) );

	try {
		if ( get_post_meta( absint( $_POST['order_id'] ), 'qr_code', true ) ) {
			wp_send_json_success( array(
				'qr_code' => get_post_meta( absint( $_POST['order_id'] ), 'qr_code', true )
			) );
		}

		if ( isset( $res->qr_code ) ) {
			update_post_meta( absint( $_POST['order_id'] ), 'qr_code', $res->qr_code );
		}
	} catch ( Error $error ) {
		var_dump( $error->getMessage() );
	}


	wp_send_json_success( $res );
}

add_action( 'woocommerce_order_actions_end', 'bogika_admin_liqpay' );

function bogika_admin_liqpay( $order_id ) {
	$order = wc_get_order( $order_id );
	?>
	<script>
        var bogikavars = {
            description: "<?= __( 'Order', 'woocommerce' ) . ' ' . $order->get_order_number() ?>",
        }
	</script>
	<hr>
	<div class="admin-actions-cont">
		<div class="button button-primary" id="getlinktoliqpay">
			Отримати посилання на liqpay
		</div>
		<input type="text" class="linktoliqpay">
	</div>
	<?php
}
