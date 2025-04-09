<?php
get_header();
$path = WP_CONTENT_DIR . '/plugins/custom-data-inserter';
require_once WP_CONTENT_DIR . '/plugins/custom-data-inserter/vendor/autoload.php';

use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;

/**
 * Bring in used classes to make the code cleaner
 */

/**
 * Set the path to our credentials JSON file
 * IMPORTANT: Do not place this in a web accessible location!
 */
putenv( "GOOGLE_APPLICATION_CREDENTIALS=$path/credentials.json" );

$weeklySpreadsheet = '1oIJknzSLrivxY6uXNeceqoPLiWt3q_H02m1YgHXsLM0';

try {
	$client = new Client();
	$client->useApplicationDefaultCredentials();
	$client->addScope( Drive::DRIVE );
	$client->addScope( Sheets::SPREADSHEETS );

	$service = new Google_Service_Sheets( $client );
	$result  = $service->spreadsheets_values->get( $weeklySpreadsheet, 'Категорія!A1:E1' );

	global $wpdb;
	$pre_date           = ( new DateTime() )->modify( '-2 days' );
	$cur_date           = ( new DateTime() )->modify( 'today' );
	$pre_date_formatted = $pre_date->format( 'Y-m-d' );
	$cur_date_formatted = $cur_date->format( 'Y-m-d' );

	$transactions_sql     = $wpdb->prepare( 'SELECT sessionSourceMedium, SUM(purchaseRevenue) as purchaseRevenue, campaignType, SUM(sessions) as sessions, SUM(advertiserAdCost) as advertiserAdCost, SUM(newUsers) as newUsers, SUM(totalUsers) as totalUsers FROM traffic WHERE date BETWEEN %s AND %s GROUP BY sessionSourceMedium, campaignType', $pre_date_formatted, $cur_date_formatted );
	$transactions_results = $wpdb->get_results( $transactions_sql, ARRAY_A );

	$currentValues  = $result->getValues();
	$last_row       = array_pop( $currentValues )[0];
	$last_row_index = 1;
	if ( is_numeric( $last_row[0] ) ) {
		$last_row_index = $last_row[0];
	}

	$values = [];

	/* Дати (приклад: 1 - 7 april) */

	$dates = ( new DateTime() )->modify( '-7 days' )->format( 'd' ) . ' - ' . ( new DateTime() )->format( 'd M' );

	/* Рік */

	$year = ( new DateTime() )->format( 'Y' );


	foreach ( $transactions_results as $transactions_result ) {
		$purchaseRevenue = $transactions_result['purchaseRevenue'];
		/* Відмінені замовлення */
		$pre_res                      = $wpdb->prepare( 'SELECT wp_posts.post_status, COUNT(transactionId) as count, SUM(purchaseRevenue) as revenue FROM transactions 
    INNER JOIN wp_posts ON transactions.transactionId = wp_posts.ID 
    WHERE date BETWEEN %s AND %s 
    AND sessionSourceMedium = %s
    AND campaignType = %s
    GROUP BY wp_posts.post_status
    ', $pre_date_formatted, $cur_date_formatted, $transactions_result['sessionSourceMedium'], $transactions_result['campaignType'] );
		$res                          = $wpdb->get_results( $pre_res, ARRAY_A );
		$totalOrders                  = array_reduce( $res, function ( $carry, $item ) {
			$carry += ( (int) $item['count'] ?? 0 );

			return $carry;
		}, 0 );
		if ($totalOrders == 0) {
			$totalCountWithoutCancelled = 0;
			$totalRevenueWithoutCancelled = 0;
		}else {
			$totalCountWithoutCancelled   = array_reduce( $res, function ( $carry, $item ) {
				if ( in_array( $item['post_status'], [ 'wc-cancelled', 'wc-refunded', 'wc-failed' ] ) ) {
					return $carry;
				}
				$carry += ( (int) $item['count'] ?? 0 );

				return $carry;
			}, 0 );
			$totalRevenueWithoutCancelled = array_reduce( $res, function ( $carry, $item ) {
				if ( in_array( $item['post_status'], [ 'wc-cancelled', 'wc-refunded', 'wc-failed' ] ) ) {
					return $carry;
				}
				$carry += ( (int) $item['revenue'] ?? 0 );

				return $carry;
			}, 0 );
		}


		$values[] = [
			$last_row_index,
			$dates,
			$year,
			'sessionSourceMedium'     => $transactions_result['sessionSourceMedium'],
			'campaignType'            => $transactions_result['campaignType'],
			'sessions'                => $transactions_result['sessions'],
			'advertiserAdCost'        => $transactions_result['advertiserAdCost'],
			'totalOrders'             => $totalOrders,
			'purchaseRevenue'         => $transactions_result['purchaseRevenue'],
			'totalUsers'              => $transactions_result['totalUsers'],
			'newUsers'                => $transactions_result['newUsers'],
			'totalWithoutCancelled'   => $totalCountWithoutCancelled,
			'revenueWithoutCancelled' => $totalRevenueWithoutCancelled,
		];
		$last_row_index ++;
	}
//	echo '<pre>';
//	var_dump( $values );
//	echo '</pre>';
//	die();

	$values = [
		$last_row_index,
		$dates,
		$year
	]; //add the values to be appended
	//execute the request
//	$body = new Google_Service_Sheets_ValueRange([
//		'values' => [
//			array('A2', 'Title A'),
//			array('B2', 'Title B'),
//			array('A3', 'Value A')
//		]
//	]);
//	$params = [
//		'valueInputOption' => 'USER_ENTERED'
//	];
//	$result = $service->spreadsheets_values->append($weeklySpreadsheet, 'A1', $body, $params);
//	printf("%d cells appended.", $result->getUpdates()->getUpdatedCells());

//	var_dump( $values );

	return $result;
} catch ( \Google\Exception $e ) {
	var_dump( $e->getMessage() );
}


get_footer();
