<?php

class BirthdayPromo {
	protected static $_instance = null;

	public function __construct() {
		if ( ! wp_next_scheduled( 'send_birthday_promo' ) ) {
			wp_schedule_event( time(), 'daily', 'send_birthday_promo' );
		}
		add_action( 'send_birthday_promo', array( $this, 'send_birthday_promo' ) );
	}

	public function send_birthday_promo() {
		$user_ids = $this::get_users();
		foreach ( $user_ids as $user_id ) {
			$user_id = (int) $user_id;
			$dob = get_user_meta( $user_id, 'dob', true );

			if ( $dob ) {
				$dob        = DateTime::createFromFormat( 'Y-m-d', $dob );

				if (!$dob) $dob = DateTime::createFromFormat( 'd.m.Y', $dob );

				/* Створюємо дату але з поточним роком*/
				$year = date('Y');
				$start_date = new DateTime($dob->format($year."-m-d"));;
				$start_date->modify( '-2 days' );

				$promo_code = $this->generate_promo_code( $user_id, $dob );
				$this->create_coupon( $promo_code, $user_id, $start_date );

				$this->send_email( $user_id, $promo_code );
			}
		}
	}

	private function generate_promo_code( $user_id, $dob ) {
		$start_date = clone $dob;
		$start_date->modify( '-2 days' );
		$end_date = clone $dob;
		$end_date->modify( '+2 days' );
		$promo_code = 'BDAY' . $user_id . $start_date->format( 'md' ) . $end_date->format( 'md' ).time();

		return $promo_code;
	}

	private function create_coupon( $promo_code, $user_id, $start_date ) {
		$coupon = new WC_Coupon();
		$coupon->set_code( $promo_code );
		$coupon->set_discount_type( 'percent' );
		$coupon->set_amount( 10 ); // Set discount amount
		$coupon->set_individual_use( true );
		$coupon->set_usage_limit( 1 );
		// Set expiration date
		$expiration_date = clone $start_date;
		$expiration_date->modify( '+4 days' );
		$coupon->set_date_expires( $expiration_date->getTimestamp() );
		// Restrict usage to user who received it
		$user = get_user_by( 'id', $user_id );
		if ( $user ) {
			$coupon->set_email_restrictions( array( $user->user_email ) );
		}
		// Save coupon
		$coupon->save();
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function get_users() {
		global $wpdb;
		$request = "SELECT user_id FROM wp_usermeta WHERE meta_key = 'dob' AND MONTH(meta_value) = MONTH(DATE_ADD(NOW(), INTERVAL + 2 DAY)) AND DAY(meta_value) = DAY(DATE_ADD(NOW(), INTERVAL +2 DAY));";
		$user_ids = $wpdb->get_col( $request);

		return $user_ids;
	}

	private function send_email( $user_id, $promo_code ) {
		$user = get_user_by( 'id', $user_id );
		if ( $user ) {
			$to      = $user->user_email;
			$subject = get_field( 'wishes_to_birthday_title', 'option' );
			$data    = [
				'{display_name}' => $user->display_name,
				'{promocode}'    => $promo_code,
			];
			$headers = array('Content-Type: text/html; charset=UTF-8');
			$message = strtr( get_field( 'wishes_to_birthday', 'option' ), $data );
			wp_mail( $to, $subject, $message, $headers );
		}
	}
}
new BirthdayPromo();
