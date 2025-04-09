<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


global $product;
if ( function_exists( 'generateSeo' ) ) {
$seo = generateSeo( [
	'quantity' => 1,
	'product'=>$product
] );
} else {
	$seo = [];
}
echo apply_filters(
	'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
	sprintf(
		'<a href="%s" data-quantity="%s" data-seo=\'%s\' data-product_id="%s" class="%s" %s>%s</a>',
        ($product->get_type() == 'variable')?esc_url( $product->add_to_cart_url() ):'',
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		stringAttribute($seo),
		esc_attr( $product->get_id() ),
		esc_attr( isset( $args['class'] ) ? $args['class'] . ' btn' : 'button btn' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		esc_html( $product->add_to_cart_text() )
	),
	$product,
	$args
);
