<?php
add_action('woocommerce_new_order', function ($order_id, $order) {
	/* @var WC_Order $order */
	if (!is_admin()) return;
	if ($order->get_customer_id()) return;
	$to      = $order->get_billing_email();
	if (!$to) return;


	$subject = get_field( 'invite_to_register_title', 'option' );
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$data    = [
		'{display_name}' => $order->get_billing_first_name(),
	];
	$message = strtr( get_field( 'invite_to_register', 'option' ), $data );
	wp_mail( $to, $subject, $message, $headers );
}, 10, 2);
