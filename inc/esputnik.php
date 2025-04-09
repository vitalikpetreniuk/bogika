<?php
define( 'ESPUTNIK_API_KEY', 'B42D35DA9829067AD8086AF756C33D14' );

/* додаємо користувача в esputnik при реєстрації */
add_action( 'woocommerce_created_customer', function ( $user_id, $user_data ) {
	esputnikInsertContact( $user_data['user_email'], $user_id );
}, 11, 2 );

/**
 * Створення контакту в срм esputnik
 *
 * @param string $email
 * @param int $user_id
 *
 * @return void
 */
function esputnikInsertContact( $email, $user_id = false ) {
	$token = base64_encode( ESPUTNIK_API_KEY . ':' . ESPUTNIK_API_KEY );

	$user_channels = [
		array(
			'type'  => 'email',
			'value' => $email,
		)
	];

	$contacts = array();

	if ( $user_id ) {
		$user = new WC_Customer( $user_id );
		if ( $user->get_billing_phone() ) {
			$user_channels[] = array(
				'type'  => 'sms',
				'value' => $user->get_billing_phone(),
			);
		}
		if ( $user->get_billing_first_name() ) {
			$contacts['firstName'] = $user->get_billing_first_name();
		}
		$contacts['externalCustomerId'] = $user_id;
	}

	$contacts['channels'] = $user_channels;

	$args = [
		'headers' => array(
			'Accept'        => 'application/json; charset=UTF-8',
			'authorization' => 'Basic ' . $token,
			'Content-Type'  => 'application/json; charset=utf-8'
		),
		'body'    => json_encode(
			array(
				'dedupeOn' => 'email',
				'contacts' =>
					array(
						$contacts
					),
			)
		),
	];

	try {
		$result = wp_remote_post( 'https://esputnik.com/api/v1/contacts', $args );
	} catch ( Exception $exception ) {

	}
}

add_action( 'wpcf7_before_send_mail', 'shypelyk_send_to_telegram', 1000, 3 );

/* Якщо це форма підписки, додаємо користувача в esputnik */
function shypelyk_send_to_telegram( $cf, &$abort, $submission ) {
	$form_id = $cf->id();

	/* Форма підписки */
	if ( $form_id == 144 ) {
		// Getting user input through the your-message field
		$email = $submission->get_posted_data( 'your-email' );
		esputnikInsertContact( $email );
	}
}
