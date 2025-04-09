<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<?php
	$seo = generateSeo(
		[
			'quantity' => 1,
            'product'=>$product
		]
	);
	?>
	<a href="" data-quantity="1" data-seo='<?= stringAttribute($seo) ?>' data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button wp-element-button product_type_simple ajax_add_to_cart add_to_cart_button button btn alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" aria-label="<?php wc_add_to_cart_message($product->get_id()) ?>" rel="nofollow" tabindex="0"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></a>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
