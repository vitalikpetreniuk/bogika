<?php
/* Отримати мінімум і максимум ціни по категорії */
function get_product_category_min_max() {
	$category = get_term_by( 'id', get_queried_object_id(), 'product_cat' );
	$all_ids  = get_posts( array(
		'post_type'   => 'product',
		'numberposts' => - 1,
		'post_status' => 'publish',
		'fields'      => 'ids',
		'tax_query'   => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $category->slug,
			),
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'exclude-from-catalog',
				'operator' => 'NOT IN',
			),
		)
	) );

	$min = PHP_INT_MAX;
    $max = PHP_INT_MIN;
	foreach ( $all_ids as $id ) {
		$product = wc_get_product( $id );
		if ( $product->is_type( 'simple' ) ) {
			$min = $product->get_price() < $min ? $product->get_price() : $min;
			$max = $product->get_price() > $max ? $product->get_price() : $max;
		} elseif ( $product->is_type( 'variable' ) ) {
			$prices = $product->get_variation_prices();
			$min    = current( $prices['price'] ) < $min ? current( $prices['price'] ) : $min;
			$max    = end( $prices['price'] ) > $max ? end( $prices['price'] ) : $max;
		}
	}

	return [ 'min' => $min, 'max' => $max ];
}

function generateSeo( $args ) {
	/* @var WC_Product $product */
	$product = $args['product'];
	if ( $product->get_parent_id() ) {
		$id = $product->get_parent_id();
	} else {
		$id = $product->get_id();
	}
//	$id      = $product->is_type( 'variable' ) ? $product->get_parent_id() : $product->get_id();
	$arr = [
		"item_name"  => $args['title'] ?? $product->get_name(),
		"item_id"    => (string) $id,
		"price"      => (int) ( $args['price'] ?? $product->get_price() ?? 1 ),
		"item_brand" => "Bogika",
		"quantity"   => $args['quantity'] ?? 1,
	];
	if ( isset( $args['item_list_name'] ) ) {
		$arr['item_list_name'] = $args['item_list_name'];
	}
	$terms = taxonomy_hierarchy( $id );
	if ( count( $terms ) ) {
		foreach ( $terms as $key => $term ) {
			if ( $key + 1 == 1 ) {
				$kkey = 'item_category';
			} else {
				$kkey = 'item_category' . $key + 1;
			}
			$arr[ $kkey ] = $term;
		}
	}

	return $arr;

}

function generatePagesDataLayer() {
	?>
    <script>
		<?php
		$args = [];
		global $wp;

		$user_id = get_current_user_id();

		if ( $user_id ) {
			$args['user_id'] = $user_id;
		}

		if ( is_front_page() ) {
			$args['ecomm_pagetype'] = 'home';
		} elseif ( is_search() ) {
			$args['ecomm_pagetype'] = 'searchresults';
		} elseif ( is_product_category() ) {
			$args['ecomm_pagetype'] = 'category';
		} elseif ( is_product() ) {
			$args['ecomm_pagetype'] = 'product';
			global $product;
			$args['ecomm_prodid']     = $product->get_id();
			$args['ecomm_totalvalue'] = $product->get_price();
		} elseif ( is_order_received_page() ) {
			$order_id                 = absint( $wp->query_vars['order-received'] ); // The order I
			$args['ecomm_pagetype']   = 'purchase';
			$args['ecomm_totalvalue'] = wc_get_order( $order_id )->get_total();
		} elseif ( is_checkout() ) {
			$args['ecomm_pagetype']   = 'cart';
			$args['ecomm_prodid']     = implode( ', ', array_values( wp_list_pluck( WC()->cart->get_cart(), 'product_id' ) ) );
			$args['ecomm_totalvalue'] = WC()->cart->total;
		} elseif ( is_cart() ) {
			$args['ecomm_pagetype']   = 'cart';
			$args['ecomm_prodid']     = implode( ', ', array_values( wp_list_pluck( WC()->cart->get_cart(), 'product_id' ) ) );
			$args['ecomm_totalvalue'] = WC()->cart->total;
		} else {
			$args['ecomm_pagetype'] = 'other';
		}?>
        dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        dataLayer.push(JSON.parse('<?= json_encode( $args ) ?>'))
    </script>
	<?php

}

function stringAttribute( $seo ) {
	return htmlspecialchars( json_encode( $seo ), ENT_QUOTES, 'UTF-8' );
}

function jsonSeoData( $arr ) {
	$json = "[";
	$items = [];
	foreach ( $arr as $item ) {
		$items[] = json_encode( $item );
	}
	$json .= implode( ',', $items );
	$json .= "]";

	return $json;
}

function taxonomy_hierarchy( $id = false ) {
	if ( ! $id ) {
		$id = get_the_ID();
	}
	$taxonomy = 'product_cat'; //Put your custom taxonomy term here
	$terms    = wp_get_post_terms( $id, $taxonomy );
    $arr = [];
	foreach ( $terms as $term ) {
		if ( $term->parent == 0 ) // this gets the parent of the current post taxonomy
		{
			$myparent = $term;
		}
        else $myparent = null;
	}
    if ($myparent) {
	    $arr[] = $myparent->name;
    }
	// Right, the parent is set, now let's get the children
	foreach ( $terms as $term ) {
		if ( $term->parent != 0 ) // this ignores the parent of the current post taxonomy
		{
			$child_term = $term; // this gets the children of the current post taxonomy
			$arr[] = $child_term->name;
		}
	}

    return $arr;
}