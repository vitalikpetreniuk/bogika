<?php
add_action( 'woocommerce_admin_order_data_after_order_details', function ( $order ) {
	$user_id = $order->get_customer_id();
	if ( ! $user_id ) {
		return;
	}
	$bfw_status    = get_user_meta( $user_id, 'bfw_status', true );
	$computy_point = get_user_meta( $user_id, 'computy_point', true );
	?>
    <p class="form-field form-field-wide user_computy_role"><strong>Статус
            клієнта: </strong><?= $bfw_status == 1 ? "Член клубу" : "Покупець (клієнт)" ?></p>
	<?php if ( $computy_point ) : ?>
        <p class="form-field form-field-wide user_computy_points"><strong>К-сть балів: </strong><?= $computy_point ?>
        </p>
	<?php endif; ?>
	<?php
} );
