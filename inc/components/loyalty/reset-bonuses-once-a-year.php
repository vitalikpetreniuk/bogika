<?php
// Видалити бонуси раз на рік 1 січня
function bogika_cron_schedules( $schedules ) {
	if ( ! isset( $schedules["yearly"] ) ) {
		$schedules["yearly"] = array(
			'interval' => YEAR_IN_SECONDS, // 1 year in seconds
			'display'  => __( 'Once a year', 'bogika' )
		);
	}

	return $schedules;
}

add_filter( 'cron_schedules', 'bogika_cron_schedules' );

// Define the function to execute
function reset_bonuses_once_a_year() {
	$args  = array(
		'meta_query' => array(
			array(/*где баллы больше 0*/
				'key'     => 'computy_point',
				'value'   => 0,
				'compare' => '>'
			)
		),
	);

	$users = get_users( $args );
	foreach ( $users as $user ) {
		$computy_point_old = ( new BfwPoints )->getPoints( $user->ID );
		/*Запись в историю*/
		( new BfwBogikaHistory )->add_history( $user->ID, '-', $computy_point_old, '0', sprintf( __( 'Reset unused bonuses', 'bogika' ) ) );
		//Очищаем баллы клиенту
		( new BfwPoints )->updatePoints( $user->ID, 0 );
	}

}

// Create the hook and schedule the event
add_action( 'reset_bonuses_hook', 'reset_bonuses_once_a_year' );
if ( ! wp_next_scheduled( 'reset_bonuses_hook' ) ) {
	wp_schedule_event( strtotime( '2024-01-01 00:00:00' ), 'yearly', 'reset_bonuses_hook' );
}
