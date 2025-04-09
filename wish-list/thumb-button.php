<?php
/**
 * Toggle button template - Thumbnail button
 *
 * Add or remove an item from Wishlist
 *
 * @author  WPFactory
 * @version 1.7.2
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div data-item_id="<?php echo esc_attr( $params['product_id'] );?>" data-action="<?php echo esc_attr( $params['btn_data_action'] ); ?>" class="<?php echo esc_attr( $params['btn_class'] ); ?>">
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-add">
		<div class="icon-heart empty">icon</div>
	</div>
	<div class="alg-wc-wl-view-state alg-wc-wl-view-state-remove">
		<div class="icon-heart full">icon</div>
	</div>
	<?php if ( $params['show_loading'] ): ?>
        <i class="loading fas fa-sync-alt fa-spin fa-fw"></i>
	<?php endif; ?>
</div>
