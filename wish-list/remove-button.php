<?php
/**
 * Toggle button template - Remove button
 *
 * Add or remove an item from Wishlist
 *
 * @author  WPFactory
 * @version 1.8.0
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
?>


<div data-item_id="<?php echo get_the_ID(); ?>" data-action="<?php echo esc_attr( $params['btn_data_action'] ); ?>"
     class="<?php echo esc_attr( $params['btn_class'] ); ?>">
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-add">
		<div class="basket-item-del">delete</div>
	</div>
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-remove">
		<div class="basket-item-del">delete</div>
	</div>
	<i class="loading fas fa-sync-alt fa-spin fa-fw"></i>
</div>
