<?php
/* Виджет мини-карты */
add_filter( 'woocommerce_add_to_cart_fragments', 'bogika_mini_cart' );

function bogika_mini_cart( $fragments ) {
	ob_start(); ?>
    <div class="basket-box">
        <a href="<?= wc_get_cart_url(); ?>" class="btn-basket">basket <span
                    class="basket-num"> <?php echo WC()->cart->get_cart_contents_count(); ?></span></a>
    </div>
	<?php
	$fragments['div.basket-box'] = ob_get_clean();
	ob_start();
    woocommerce_mini_cart();
	$fragments['div#bogika-mini-cart'] = ob_get_clean();
	unset( $fragments['div.widget_shopping_cart_content'] );

	return $fragments;
}

add_filter( 'woocommerce_product_get_rating_html', function ( $html, $rating, $count ) {
	ob_start(); ?>
    <div class="stars stars1">
		<?php for ( $i = 1.00; $i < 6.00; $i ++ ) : ?>
			<?php if ( $i <= round( $rating ) ) : ?>
                <span class="star-full">star</span>
			<?php else: ?>
                <span class="star-empty">star</span>
			<?php endif; ?>
		<?php endfor; ?>
    </div>
	<?php $html = ob_get_contents();
	ob_end_clean();

	return $html;
	?>
	<?php
}, 11, 3 );

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 11 );

add_action( 'init', 'true_woo_no_breadcrumbs' );
function true_woo_no_breadcrumbs() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
}

add_filter( 'woocommerce_default_catalog_orderby_options', 'truemisha_remove_orderby_options' );
add_filter( 'woocommerce_catalog_orderby', 'truemisha_remove_orderby_options' );

function truemisha_remove_orderby_options( $sortby ) {
	unset( $sortby['menu_order'] ); // по умолчанию
	unset( $sortby['popularity'] ); // по популярности
	unset( $sortby['rating'] ); // по рейтингу
	unset( $sortby['date'] ); // Сортировка по более позднему
	$sortby['price']      = 'За підвищенням ціни';
	$sortby['price-desc'] = 'За зниженням ціни';

	return $sortby;
}

//add new sorting option
add_filter( 'woocommerce_default_catalog_orderby_options', 'custom_woocommerce_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'custom_woocommerce_catalog_orderby' );
function custom_woocommerce_catalog_orderby( $sortby ) {
	$sortby['recommended'] = 'Recommended';

	return $sortby;
}

//set default sorting for new option
add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_woocommerce_get_catalog_ordering_args' );
function custom_woocommerce_get_catalog_ordering_args( $args ) {
	$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	if ( 'recommended' == $orderby_value ) {
		$args['orderby']  = 'meta_value_num';
		$args['order']    = 'DESC';
		$args['meta_key'] = '_price';
	}

	return $args;
}

//adjust order to allow for featured posts
add_filter( 'posts_orderby', 'show_featured_products_orderby', 10, 2 );
function show_featured_products_orderby( $order_by, $query ) {
	global $wpdb;
	if ( is_admin() && ! $query->is_main_query() ) {
		return $order_by;
	}
	if ( ! is_product_category() ) {
		return $order_by;
	}
	$orderby_value       = ( isset( $_GET['orderby'] ) ? wc_clean( (string) $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) ) );
	$orderby_value_array = explode( '-', $orderby_value );
	$orderby             = esc_attr( $orderby_value_array[0] );
	$order               = ( ! empty( $orderby_value_array[1] ) ? $orderby_value_array[1] : 'ASC' );

	$feture_product_id = $wpdb->get_col( "SELECT   wp_posts.ID
			FROM wp_posts  LEFT JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id) LEFT  JOIN wp_icl_translations wpml_translations
							ON wp_posts.ID = wpml_translations.element_id
								AND wpml_translations.element_type = CONCAT('post_', wp_posts.post_type) 
			WHERE 1=1  AND ( 
  wp_term_relationships.term_taxonomy_id IN (15) 
  AND 
  wp_posts.ID NOT IN (
				SELECT object_id
				FROM wp_term_relationships
				WHERE term_taxonomy_id IN (14)
			)
) AND wp_posts.post_type IN ('product', 'product_variation') AND ((wp_posts.post_status = 'publish')) AND ( ( ( wpml_translations.language_code = 'uk' OR (
					wpml_translations.language_code = 'uk'
					AND wp_posts.post_type IN ( 'product' )
					AND ( ( 
			( SELECT COUNT(element_id)
			  FROM wp_icl_translations
			  WHERE trid = wpml_translations.trid
			  AND language_code = 'uk'
			) = 0
			 ) OR ( 
			( SELECT COUNT(element_id)
				FROM wp_icl_translations t2
				JOIN wp_posts p ON p.id = t2.element_id
				WHERE t2.trid = wpml_translations.trid
				AND t2.language_code = 'uk'
				AND (
					p.post_status = 'publish' OR 
					p.post_type='attachment' AND p.post_status = 'inherit'
				)
			) = 0 ) ) 
				) ) AND wp_posts.post_type  IN ('post','page','attachment','wp_block','wp_template','wp_template_part','wp_navigation','product','product_variation' )  ) OR wp_posts.post_type  NOT  IN ('post','page','attachment','wp_block','wp_template','wp_template_part','wp_navigation','product','product_variation' )  )
			GROUP BY wp_posts.ID
			ORDER BY wp_posts.menu_order, wp_posts.post_date DESC" );

	//only apply to recommended sorting option
	if ( $orderby == "recommended" && is_array( $feture_product_id ) && ! empty( $feture_product_id ) ) {
		if ( empty( $order_by ) ) {
			$order_by = "FIELD(" . $wpdb->posts . ".ID,'" . implode( "','", $feture_product_id ) . "') DESC ";
		} else {
			$order_by = "FIELD(" . $wpdb->posts . ".ID,'" . implode( "','", $feture_product_id ) . "') DESC, " . $order_by;
		}
	}

	return $order_by;
}

remove_filter( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper' );
remove_filter( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end' );

add_filter( 'alg_wc_wl_locate_template', function ( $final_link ) {
//	var_dump($final_link);
	if ( strpos( $final_link, 'thumb-button.php' ) ) {
		$final_link = get_template_directory() . '/wish-list/thumb-button.php';
	} elseif ( strpos( $final_link, 'wish-list.php' ) ) {
		$final_link = get_template_directory() . '/wish-list/wish-list.php';
	} elseif ( strpos( $final_link, 'remove-button.php' ) ) {
		$final_link = get_template_directory() . '/wish-list/remove-button.php';
	} elseif ( strpos( $final_link, 'share.php' ) ) {
		$final_link = get_template_directory() . '/wish-list/share.php';
	}

	return $final_link;
} );

require 'single-product.php';
require 'checkout.php';
require 'account.php';
require 'admin-area.php';
require 'esputnik.php';
if ( is_plugin_active( 'bonus-for-woo/index.php' ) ) {
	require 'loyalty-program.php';
}

//woocommerce_update_order_review_fragments
remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );


add_filter( 'woocommerce_product_add_to_cart_text', function ( $text, $tthis ) {
	if ( ! $tthis->is_in_stock() ) {
		return __( 'View', 'bogika' );
	}

	return $text;
}, 10, 2 );

add_filter( 'woocommerce_get_catalog_ordering_args', 'custom_catalog_ordering_args' );

function custom_catalog_ordering_args( $args ) {
	$orderby_value = isset( $_GET['orderby'] ) ? $_GET['orderby'] : 'price-desc';

	if ( 'price-desc' === $orderby_value ) {
		$args['orderby']  = 'meta_value_num';
		$args['order']    = 'DESC';
		$args['meta_key'] = '_price';
	}

	return $args;
}

require_once 'components/admin/remove-unused-company-field.php';

add_filter( 'woocommerce_cod_process_payment_order_status', 'change_cod_payment_order_status', 10, 2 );
function change_cod_payment_order_status( $order_status, $order ) {
	return 'pending';
}

remove_action( 'woocommerce_before_cart', 'bfwoo_spisaniebonusov_in_cart', 9 );

add_filter( 'woocommerce_cart_totals_coupon_label', function ( $text, $coupon ) {
	return sprintf( esc_html__( 'Coupon: %s', 'bogika' ), $coupon->get_code() );
}, 10, 2 );

require_once 'components/admin/add-column-for-nova-poshta-ttn.php';

add_action( 'woocommerce_checkout_order_created', function ( $order ) {
	WC()->mailer()->emails['WC_Email_Customer_Processing_Order']->trigger( $order->get_id() );
} );

remove_action( 'woocommerce_order_status_pending_to_processing_notification', array(
	WC()->mailer()->emails ['WC_Email_Customer_Processing_Order'],
	'trigger'
) );

require_once 'components/emails/email-invite-to-register.php';

add_action( 'wp_ajax_bogika_add_three_one', 'bogika_add_three_one' );

function bogika_add_three_one() {
	if ( isset( $_POST['order_id'], $_POST['items'] ) ) {
		$order_id = absint( $_POST['order_id'] );

		// Parse the jQuery serialized items.
		$items = array();
		parse_str( wp_unslash( $_POST['items'] ), $items ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

		// Save order items.
		wc_save_order_items( $order_id, $items );

		// Return HTML items.
		$order = wc_get_order( $order_id );

		// Get HTML to return.
		ob_start();
		include plugin_dir_path( 'woocommerce/woocommerce.php' ) . '/admin/meta-boxes/views/html-order-items.php';
		$items_html = ob_get_clean();

		ob_start();
		$notes = wc_get_order_notes( array( 'order_id' => $order_id ) );
		include plugin_dir_path( 'woocommerce/woocommerce.php' ) . '/admin/meta-boxes/views/html-order-notes.php';
		$notes_html = ob_get_clean();

		wp_send_json_success(
			array(
				'html'       => $items_html,
				'notes_html' => $notes_html,
			)
		);
	}
	wp_die();
}

/* Якщо товару немає в наявності, перенести його в кінець*/
require_once 'components/bogo/bogo-calculate-checkout-discount.php';

// Ordering products based on the selected values


if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_filter( 'posts_clauses', 'order_by_stock_status', 2000 );
}

function order_by_stock_status( $posts_clauses ) {
	global $wpdb;

	if ( is_woocommerce() && ( is_shop() || is_product_category() || is_product_tag() ) ) {
		$posts_clauses['join']    .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
		$posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
		$posts_clauses['where']   = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
	}

	return $posts_clauses;
}

require_once 'seo.php';

add_filter('paginate_links', function($link) {
    $pos = strpos($link, 'page/1/');
    if($pos !== false) {
        $link = substr($link, 0, $pos);
    }
    return $link;
});

// Change "Add to Cart" > "Add to Bag" in Shop Page
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_shop_page_add_to_cart_callback' );
function woocommerce_shop_page_add_to_cart_callback() {
    return __( 'Buy', 'woocommerce' );
}
// Change "Add to Cart" > "Add to Bag" in Single Page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_single_page_add_to_cart_callback' );
function woocommerce_single_page_add_to_cart_callback() {
    return __( 'Buy', 'woocommerce' );
}

// Change Yoast-SEO meta-tags
function yoast_meta_title_specific_page($myfilter)    {
    $page = get_query_var('paged');
    if(is_product_category()&&!empty($page)&&(int)$page!=1) return $myfilter." - ".__('page','woocommerce').' '.$page;
    if(is_product()) {
        $add_str = ' - '.__('купити в Україні','woocommerce').' | BOGIKA';
        $myfilter = str_replace($add_str, '', $myfilter).$add_str ;
        return $myfilter;
    }
    return $myfilter;
}

add_filter( 'wpseo_title', 'yoast_meta_title_specific_page' );

function yoast_meta_desc_specific_page($myfilter)    {
    $page = get_query_var('paged');
    if(is_product_category()&&!empty($page)&&(int)$page!=1) return $myfilter." - ".__('page','woocommerce').' '.$page;
    if(is_product()) {
        $add_str = __(' ⭐️ висока якість ✔️ гарні відгуки ✔️ доставка по всій Україні','woocommerce').' | BOGIKA';
        $myfilter = 'Купити '.str_replace([$add_str,'Купити '], '', $myfilter).$add_str ;
        return $myfilter;
    }
    return $myfilter;
}

add_filter( 'wpseo_metadesc', 'yoast_meta_desc_specific_page' );

// change the breadcrumb on the product page
add_filter( 'woocommerce_get_breadcrumb', 'custom_breadcrumb', 20, 2 );
function custom_breadcrumb( $crumbs, $breadcrumb ) {

    // only on the single product page
    if ( ! is_product() ) {
        return $crumbs;
    }
    if(count($crumbs) == 4 && !empty(getprimcat()) && getprimcat()->parent != 0) {
        $new_crumbs = $crumbs;
        $primcat = getprimcat();
        $parent_primcat = get_term($primcat->parent);
        $new_crumbs[2] = [$primcat->name,get_category_link($primcat->term_id)];
        $new_crumbs[1] = [$parent_primcat->name,get_category_link($parent_primcat->term_id)];
        return $new_crumbs;
    }
    else return $crumbs;

}

/*--------------------------------------------------------------
Get primary Category @lennartc
--------------------------------------------------------------*/
function getprimcat() {
    $category = wp_get_post_terms( get_the_id(), 'product_cat' );
    $term = false;
    if ( class_exists('WPSEO_Primary_Term') || $category) {
        $wpseo_primary_term = new WPSEO_Primary_Term( 'product_cat', get_the_id() );
        $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
        $term = get_term( $wpseo_primary_term );

    }
    return $term;
}

function is_premmerce_filter_page() {
    $url_arr = explode('/',$_SERVER['REQUEST_URI']);
    $new_url_arr = [];
    foreach($url_arr as $val)
        if(!empty($val)) $new_url_arr[] = $val;
    foreach($new_url_arr as $url_item) {
        if($url_item=='product-category') continue;
        $term = get_term_by( 'slug', $url_item, 'product_cat');
        if(!$term) return true;
    }
    return false;
}
//get description for premmerce seo page
function get_seo_description() {
    global $wpdb;
    $url = trim($_SERVER['REQUEST_URI'],'/');
    $results = $wpdb->get_results( "SELECT description FROM {$wpdb->prefix}premmerce_filter_seo WHERE path = '".$url."'", OBJECT );
    if(isset($results[0]->description)) return $results[0]->description;
    else return '';

}
//get term id for premmerce seo page
function get_seo_term_id() {
    global $wpdb;
    $url = trim($_SERVER['REQUEST_URI'],'/');
    $results = $wpdb->get_results( "SELECT term_id FROM {$wpdb->prefix}premmerce_filter_seo WHERE path = '".$url."'", OBJECT );
    if(isset($results[0]->term_id)) return $results[0]->term_id;
    else return 0;
}

//get noindex value for premmerce seo page
function get_seo_noindex() {
    global $wpdb;
    $url = trim($_SERVER['REQUEST_URI'],'/');
    $results = $wpdb->get_results( "SELECT discourage_search FROM {$wpdb->prefix}premmerce_filter_seo WHERE path = '".$url."'", OBJECT );
    if(isset($results[0]->discourage_search)) return $results[0]->discourage_search;
    else return 0;
}

//get noindex value for premmerce seo settings
function get_seo_noindex_permalinks() {
    global $wpdb;
    $results = $wpdb->get_results( "SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = 'premmerce_filter_permalink_settings'", OBJECT );
    if(isset($results[0]->option_value)) {
        $settings =  unserialize($results[0]->option_value);
        if($settings['discourage_search_all'] == 'on') return true;
        else return false;
    }
    else return false;
}



/**
 * Filters the canonical URL.
 *
 * @param string $canonical The current page's generated canonical URL.
 *
 * @return string The filtered canonical URL.
 */
function prefix_filter_canonical_example( $canonical ) {
    if ( is_premmerce_filter_page() ) {
        $term_id = (int)get_seo_term_id();
        if($term_id ) $canonical = home_url().$_SERVER['REQUEST_URI'];
    }

    return $canonical;
}

add_filter( 'wpseo_canonical', 'prefix_filter_canonical_example' );

function add_meta_robots() {
    $is_index = true;
    if(is_product_category()&&is_premmerce_filter_page()&&(empty(get_seo_term_id())||!empty(get_seo_noindex()))) $is_index = false;
    if($is_index) { ?>
        <meta name=robots content='index, follow'/>
    <?php   } else { ?>
        <meta name=robots content='noindex, nofollow'/>
    <?php }
}
add_action('wp_head','add_meta_robots', 1,1);

add_action( 'pre_get_posts', function( $query ) {
    if(is_product_category()&&is_premmerce_filter_page()) {
        $path_arr = explode('/', trim(parse_url($_SERVER['REQUEST_URI'])['path'], '/'));
        if (is_array($path_arr)&&(in_array('page',$path_arr)))
            $key = array_search('page',$path_arr);
            if($key) {
                $page = $path_arr[$key + 1];
                $query->set('paged', $page);
            }
    }
});