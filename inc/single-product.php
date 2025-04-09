<?php
add_filter( 'woocommerce_reset_variations_link', '__return_false' );

add_filter( 'wc_price', function ( $return, $price, $args, $unformatted_price, $original_price ) {
//	return $return;
	$args = apply_filters(
		'wc_price_args',
		wp_parse_args(
			$args,
			array(
				'ex_tax_label'       => false,
				'currency'           => '',
				'decimal_separator'  => wc_get_price_decimal_separator(),
				'thousand_separator' => wc_get_price_thousand_separator(),
				'decimals'           => wc_get_price_decimals(),
				'price_format'       => get_woocommerce_price_format(),
			)
		)
	);

	$original_price = $price;

	// Convert to float to avoid issues on PHP 8.
	$price = (float) $price;

	$unformatted_price = $price;
	$negative          = $price < 0;

	/**
	 * Filter raw price.
	 *
	 * @param float $raw_price Raw price.
	 * @param float|string $original_price Original price as float, or empty string. Since 5.0.0.
	 */
	$price = apply_filters( 'raw_woocommerce_price', $negative ? $price * - 1 : $price, $original_price );
	/**
	 * Filter formatted price.
	 *
	 * @param float $formatted_price Formatted price.
	 * @param float $price Unformatted price.
	 * @param int $decimals Number of decimals.
	 * @param string $decimal_separator Decimal separator.
	 * @param string $thousand_separator Thousand separator.
	 * @param float|string $original_price Original price as float, or empty string. Since 5.0.0.
	 */
	$price = apply_filters( 'formatted_woocommerce_price', number_format( $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'] ), $price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator'], $original_price );
//	var_dump( $price );
	if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $args['decimals'] > 0 ) {
		$price = wc_trim_zeros( $price );
	}
	$formatted_price = ( $negative ? '-' : '' ) . sprintf( $args['price_format'], '<span class="woocommerce-Price-currencySymbol">' . get_woocommerce_currency_symbol( $args['currency'] ) . '</span>', $price );
	$return          = $formatted_price;

//	$return          = '<div class="cost flexbox"><div class="new">330' . get_woocommerce_currency_symbol( $args['currency'] ) . '</div><div class="old">464' . get_woocommerce_currency_symbol( $args['currency'] ) . '</div></div>';

	return $return;
}, 10, 5 );

add_filter( 'woocommerce_get_price_html', function ( $price, $tthis ) {
	return $price;
}, 10, 2 );

add_filter( 'woocommerce_format_sale_price', function ( $price, $regular_price, $sale_price ) {
	$price = '<div class="old">' . ( is_numeric( $regular_price ) ? wc_price( $regular_price ) : $regular_price ) . '</div><div class="new">' . ( is_numeric( $sale_price ) ? wc_price( $sale_price ) : $sale_price ) . '</div>';

	return $price;
}, 10, 3 );

add_filter(
	'woocommerce_available_variation',
	function ( $args, $tthis, $variation ) {
		$show_variation_price = apply_filters( 'woocommerce_show_variation_price', $variation->get_price() === '' || $tthis->get_variation_sale_price( 'min' ) !== $tthis->get_variation_sale_price( 'max' ) || $tthis->get_variation_regular_price( 'min' ) !== $tthis->get_variation_regular_price( 'max' ), $tthis, $variation );
		$args['price_html']   = $show_variation_price ? '<div class="cost flexbox"><span class="price-text">' . __('Price','woocommerce'). '</span> '. $variation->get_price_html() . '</div>' : '';

		return $args;
	},
	10, 3
);
add_action( 'wp_ajax_nopriv_woocommerce_add_to_cart', 'add_to_cart' );
add_action( 'wp_ajax_woocommerce_add_to_cart', 'add_to_cart' );

add_action( 'wp_ajax_nopriv_woocommerce_add_variation_to_cart', 'so_27270880_add_variation_to_cart' );
add_action( 'wp_ajax_woocommerce_add_variation_to_cart', 'so_27270880_add_variation_to_cart' );

function so_27270880_add_variation_to_cart() {

	ob_start();

	$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
	$quantity   = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );

	$variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : '';
	$variations   = ! empty( $_POST['variation'] ) ? (array) $_POST['variation'] : '';

	$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );

	if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) ) {

		do_action( 'woocommerce_ajax_added_to_cart', $product_id );

		if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
			wc_add_to_cart_message( $product_id );
		}
		$message         = apply_filters( 'woocommerce_cart_product_cannot_add_another_message', $message, $product_data );
		$wp_button_class = wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '';

		$data['forwardbtn'] = sprintf( '<a href="%s" class="button wc-forward%s">%s</a> %s', wc_get_cart_url(), esc_attr( $wp_button_class ), __( 'View cart', 'woocommerce' ), $message );
		$data['success']    = 'success';
		wp_send_json_success( $data );

		// Return fragments
		//WC_AJAX::get_refreshed_fragments();

	} else {

		// If there was an error adding to the cart, redirect to the product page to show any errors
		$data = array(
			'error'       => true,
			'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
		);

		wp_send_json( $data );

	}

	die();
}

function update_item_from_cart() {
	$cart_item_key = $_POST['cart_item_key'];
	$quantity      = $_POST['qty'];

	// Get mini cart
	ob_start();

	foreach ( WC()->cart->get_cart() as $key => $cart_item ) {
		if ( $cart_item_key == $key ) {
			WC()->cart->set_quantity( $cart_item_key, $quantity, $refresh_totals = true );
		}
	}

	WC_AJAX::get_refreshed_fragments();
}

add_action( 'wp_ajax_update_item_from_cart', 'update_item_from_cart' );
add_action( 'wp_ajax_nopriv_update_item_from_cart', 'update_item_from_cart' );
remove_filter( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 4 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

add_action( 'wp', function () {
	if ( is_singular( 'product' ) ) {
		$product = wc_get_product( get_queried_object_id() );
		if ( $product && $product->get_type() == 'simple' ) {
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 28 );
		}
	}
} );

//add_action( 'woocommerce_single_product_summary', 'bogika_after_single_product_summary', 29 );

function bogika_after_single_product_summary() {
	global $product;
	if ( $product->get_type() !== 'simple' ) {
		return;
	}
	// Add product attributes to list.
	$attributes = array_filter( $product->get_attributes(), 'wc_attributes_array_filter_visible' );

	foreach ( $attributes as $attribute ) {
		$values = array();
		if ( $attribute->is_taxonomy() ) {
			$attribute_values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

			foreach ( $attribute_values as $attribute_value ) {
				$values[] = esc_html( $attribute_value->name );
			}
		} else {
			$values = $attribute->get_options();

			foreach ( $values as &$value ) {
				$value = esc_html( $value );
			}
		}

		$product_attributes[] = array(
			'label' => wc_attribute_label( $attribute->get_name() ),
			'value' => apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values ),
		);
	}
	?>
    <div class="info">
		<?php foreach ( $product_attributes as $product_attribute ) : ?>
            <div class="info-item">
                <div class="text"><?= $product_attribute['label'] ?>:</div>
                <label class="checkbox-container"><?= $product_attribute['value'] ?>
                    <input type="radio" checked name="radio">
                    <span class="checkmark"></span>
                </label>
            </div>
		<?php endforeach; ?>
    </div>

	<?php
	if (!$product->is_in_stock()) {
		echo wc_get_stock_html($product);
	}
}

add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
	$tabs = array(
		'description'            => array(
			'title'    => __( 'Full description', 'bogika' ),
			'priority' => 10,
			'callback' => 'woocommerce_product_description_tab',
		),
		'additional_information' => array(
			'title'    => __( 'Characteristics', 'bogika' ),
			'priority' => 20,
			'callback' => 'woocommerce_product_additional_information_tab',
		),
		'reviews'                => array(
			'title'    => __( 'Reviews', 'bogika' ),
			'priority' => 30,
			'callback' => 'comments_template',
		),
	);

//	$tabs['description']['title']            = __( 'Full description', 'bogika' );
//	$tabs['reviews']['title']                = __( 'Reviews', 'bogika' );
//	$tabs['additional_information']['title'] = __( 'Characteristics', 'bogika' );

	return $tabs;
}, 11 );
add_action( 'woocommerce_single_variation', 'woocommerce_template_single_price', 15 );

add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
	$args['posts_per_page'] = 7;

	return $args;
} );

function ajax_qty_cart() {

	// Set item key as the hash found in input.qty's name
	$cart_item_key = $_POST['hash'];

	// Get the array of values owned by the product we're updating
	$threeball_product_values = WC()->cart->get_cart_item( $cart_item_key );

	// Get the quantity of the item in the cart
	$threeball_product_quantity = apply_filters( 'woocommerce_stock_amount_cart_item', apply_filters( 'woocommerce_stock_amount', preg_replace( "/[^0-9\.]/", '', filter_var( $_POST['quantity'], FILTER_SANITIZE_NUMBER_INT ) ) ), $cart_item_key );

	// Update cart validation
	$passed_validation = apply_filters( 'woocommerce_update_cart_validation', true, $cart_item_key, $threeball_product_values, $threeball_product_quantity );

	// Update the quantity of the item in the cart
	if ( $passed_validation ) {
		WC()->cart->set_quantity( $cart_item_key, $threeball_product_quantity, true );
	}

	wp_send_json_success();
}

add_action( 'wp_ajax_qty_cart', 'ajax_qty_cart' );
add_action( 'wp_ajax_nopriv_qty_cart', 'ajax_qty_cart' );

add_filter( 'wc_add_to_cart_message_html', '__return_false' );

require_once 'comments.php';

if ( ! function_exists( 'single__product_taxonomy_pa_weigh' ) ) {
    function single__product_taxonomy_pa_weigh () {
        global $product;
        if ( $product->get_type() !== 'simple' ) {
            return;
        }
        // Add product attributes to list.
        $attributes = array_filter( $product->get_attributes(), 'wc_attributes_array_filter_visible' );

        foreach ( $attributes as $attribute ) {
            $values = array();
            if ( $attribute->is_taxonomy() ) {
                $attribute_values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

                foreach ( $attribute_values as $attribute_value ) {
                    $values[] = esc_html( $attribute_value->name );
                }
            } else {
                $values = $attribute->get_options();

                foreach ( $values as &$value ) {
                    $value = esc_html( $value );
                }
            }

            $product_attributes[] = array(
                'label' => wc_attribute_label( $attribute->get_name() ),
                'value' => apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values ),
            );
        }
        ?>
		<?php if(!empty($product_attributes)): ?>
			<div class="info">
				<?php foreach ( $product_attributes as $product_attribute ) : ?>
					<div class="info-item">
						<div class="text"><?= $product_attribute['label'] ?>:</div>
						<label class="checkbox-container"><?= $product_attribute['value'] ?>
							<input type="radio" checked name="radio">
							<span class="checkmark"></span>
						</label>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
        <?php
    };
}
