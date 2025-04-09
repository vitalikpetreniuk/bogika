<?php
add_filter( 'woocommerce_register_shop_order_post_statuses', 'bogika_register_shop_order_post_statuses' );

function bogika_register_shop_order_post_statuses( $order_statuses ) {
	$order_statuses['wc-pending']['label']       = _x( 'Нове', 'Order status', 'bogika' );
	$order_statuses['wc-pending']['label_count'] = _n_noop( 'Нове <span class="count">(%s)</span>', 'Нові <span class="count">(%s)</span>', 'bogika' );

	$order_statuses['wc-processing']['label']       = _x( 'Сплачено', 'Order status', 'bogika' );
	$order_statuses['wc-processing']['label_count'] = _n_noop( 'Сплачено <span class="count">(%s)</span>', 'Сплачено <span class="count">(%s)</span>', 'bogika' );

	$order_statuses['wc-on-hold']['label']       = _x( 'Прийнято', 'Order status', 'bogika' );
	$order_statuses['wc-on-hold']['label_count'] = _n_noop( 'Прийнято <span class="count">(%s)</span>', 'Прийнято <span class="count">(%s)</span>', 'bogika' );

	$order_statuses['wc-completed']['label']       = _x( 'Виконано', 'Order status', 'bogika' );
	$order_statuses['wc-completed']['label_count'] = _n_noop( 'Виконано <span class="count">(%s)</span>', 'Виконано <span class="count">(%s)</span>', 'bogika' );

	$order_statuses['wc-cancelled']['label']       = _x( 'Скасовано', 'Order status', 'bogika' );
	$order_statuses['wc-cancelled']['label_count'] = _n_noop( 'Скасовано <span class="count">(%s)</span>', 'Скасовано <span class="count">(%s)</span>', 'bogika' );

	$order_statuses['wc-failed']['label']       = _x( 'Відмінена оплата', 'Order status', 'bogika' );
	$order_statuses['wc-failed']['label_count'] = _n_noop( 'Відмінена оплата <span class="count">(%s)</span>', 'Відмінена оплата <span class="count">(%s)</span>', 'bogika' );

	return $order_statuses;
}

add_filter( 'wc_order_statuses', 'bogika_rename_completed_order_status' );

function bogika_rename_completed_order_status( $order_statuses ) {
	$order_statuses = array(
		'wc-pending'    => _x( 'New', 'Order status', 'bogika' ),
		'wc-processing' => _x( 'Paid', 'Order status', 'bogika' ),
		'wc-on-hold'    => _x( 'Processing', 'Order status', 'bogika' ),
		'wc-completed'  => _x( 'Completed', 'Order status', 'woocommerce' ),
		'wc-cancelled'  => _x( 'Cancelled', 'Order status', 'woocommerce' ),
		'wc-failed'     => _x( 'Failed payment', 'Order status', 'bogika' ),
	);

	return $order_statuses;
}

add_filter( 'bulk_actions-edit-shop_order', 'define_bulk_actions', 11 );

function define_bulk_actions( $actions ) {
	if ( isset( $actions['edit'] ) ) {
		unset( $actions['edit'] );
	}
	if ( isset( $actions['mark_cancelled'] ) ) {
		unset( $actions['mark_cancelled'] );
	}

	$actions['mark_processing'] = __( 'Change status to processing', 'bogika' );
	$actions['mark_on-hold']    = __( 'Change status to paid', 'bogika' );
	$actions['mark_completed']  = __( 'Change status to completed', 'bogika' );
//	$actions['mark_cancelled']  = __( 'Change status to cancelled', 'bogika' );

	if ( wc_string_to_bool( get_option( 'woocommerce_allow_bulk_remove_personal_data', 'no' ) ) ) {
		$actions['remove_personal_data'] = __( 'Remove personal data', 'woocommerce' );
	}

	return $actions;
}

add_action( 'woocommerce_admin_order_data_after_billing_address', 'bogika_display_ukrposhta_type', 10, 1 );
/* @var WC_Order $order */
function bogika_display_ukrposhta_type( $order ) {
	$shipping_methods   = $order->get_shipping_methods();
	$shipping_method    = @array_shift( $shipping_methods );
	$shipping_method_id = $shipping_method['method_id'];
	if ( $shipping_method_id !== 'ukrposhta_shippping' ) {
		return;
	}
	if ( ! $order->get_meta( 'billing_ukrposhta_type' ) ) {
		return;
	}
	echo '<p><strong>Умова доставки укрпошти:</strong> ' . ucfirst( $order->get_meta( 'billing_ukrposhta_type' ) ) . '</p>';
}

add_action( 'woocommerce_admin_order_totals_after_discount', function ( $order_id ) {
	echo '<tr><td class="label">Позицій</td><td width="1%"></td><td class="total">' . count( wc_get_order( $order_id )->get_items() ) . '</td></tr>';
} );

add_action( 'woocommerce_order_actions_end', function ( $order_id ) {
	$order = wc_get_order( $order_id );
	?>
    <div class="user-contact-actions admin-actions-cont">
        <div class="user-contact-actions-title">
            <strong>Дії</strong>
        </div>
        <div class="user-contact-actions-button">
            <a href="https://t.me/<?= $order->get_billing_phone() ?>" target="_blank" class="button">Telegram</a>
            <a href="viber://chat?number=<?= str_replace( '+', '', $order->get_billing_phone() ) ?>" class="button">Viber</a>
            <a href="https://wa.me/<?= $order->get_billing_phone() ?>" target="_blank" class="button">Whatsapp</a>
        </div>
        <div class="user-contact-actions-title">
            <strong>Надіслати повідомлення</strong>
        </div>
        <fieldset id="sms-integration">
            <legend>Надіслати через:</legend>
            <div class="radio-field">
                <input type="radio" checked name="via" id="via-sms" value="sms">
                <label for="via-sms">SMS</label>
            </div>
            <div class="radio-field">
                <input type="radio" name="via" id="via-viber" value="viber">
                <label for="via-viber">Viber</label>
            </div>
            <input type="hidden" id="sms-integration-tel" name="tel"
                   value="<?= str_replace( [ ' ', '(', ')', '-', '+' ], '', $order->get_billing_phone() ); ?>">
            <textarea name="message" id="sms-integration-message" cols="30" rows="10"
                      placeholder="Повідомлення"></textarea>
            <button id="sms-integration-send" class="button button-primary" type="button">Надіслати</button>
        </fieldset>
    </div>
	<?php
} );

// Add manager column to order list table
add_filter( 'manage_edit-shop_order_columns', 'add_manager_column_to_order_list' );
add_filter( 'manage_edit-shop_order_sortable_columns', 'add_manager_column_to_order_list' );
function add_manager_column_to_order_list( $columns ) {
	$columns['manager'] = __( 'Manager', 'bogika' );

	return $columns;
}

// Populate manager column with note value
add_action( 'manage_shop_order_posts_custom_column', 'populate_manager_column' );
function populate_manager_column( $column ) {
	global $post;
	if ( $column == 'manager' && is_array( get_field( 'manager', $post ) ) ) {
		echo get_field( 'manager', $post )['display_name'];
	}
}

// Custom function where metakeys / labels pairs are defined
function get_filter_shop_order_meta( $domain = 'woocommerce' ) {
	// Add below the metakey / label pairs
	return array(
		'manager' => __( 'Manager', $domain ),
		// ... (add more metakey / label pairs below)
	);
}

// Add a dropdown to filter orders by custom meta fields
add_action( 'restrict_manage_posts', 'filter_orders_by_custom_meta_fields' );
function filter_orders_by_custom_meta_fields() {
	global $pagenow, $post_type;

	if ( 'shop_order' === $post_type && 'edit.php' === $pagenow ) {
		$filter_id = 'filter_shop_order_meta';
		$current   = isset( $_GET[ $filter_id ] ) ? $_GET[ $filter_id ] : '';
		$meta_keys = get_filter_shop_order_meta();

		echo '<select name="' . $filter_id . '">
        <option value="">' . __( 'Manager', 'bogika' ) . '</option>';

		foreach ( $meta_keys as $label ) {
			// Get users by role
			$args  = array(
				'role'   => 'shop_manager',
				'fields' => array( 'ID', 'display_name' )
			);
			$users = get_users( $args );
			// Loop through users and add them as options
			foreach ( $users as $user ) {
				printf( '<option value="%s"%s>%s</option>', $user->ID,
					$user->ID === $current ? '" selected="selected"' : '', $user->display_name );
			}
		}
		echo '</select>';
	}
}

// Process the filter dropdown for orders by custom meta fields
add_filter( 'request', 'process_admin_shop_order_by_custom_meta_fields', 99 );
function process_admin_shop_order_by_custom_meta_fields( $vars ) {
	global $pagenow, $typenow;

	$filter_id = 'filter_shop_order_meta';

	if ( $pagenow == 'edit.php' && 'shop_order' === $typenow
	     && isset( $_GET[ $filter_id ] ) && ! empty( $_GET[ $filter_id ] ) ) {
		$vars['meta_key']     = 'manager'; // Change this to your custom meta key
		$vars['meta_value']   = $_GET[ $filter_id ];
		$vars['meta_compare'] = '=';
	}

	return $vars;
}

add_action( 'restrict_manage_posts', 'display_shipping_dropdown' );
function display_shipping_dropdown() {
	global $typenow;
	if ( isset( $typenow ) && $typenow == 'shop_order' ) {

		$current = isset( $_GET['shipping_method'] ) ? $_GET['shipping_method'] : '';

		$methods = [
			'local_pickup'         => 'Самовивіз',
			'nova_poshta_shipping' => 'Доставка службой "Новая почта"',
			'ukrposhta_shippping'  => 'Доставка службою "Укрпошта"',
		]
		?>
        <select name="shipping_method">
            <option value=""><?php _e( 'All Shipping Methods', 'bogika' ); ?></option>
			<?php foreach ( $methods as $key => $method ) {
				printf( '<option value="%s"%s>%s</option>', $key,
					$key === $current ? '" selected="selected"' : '', $method );
				?>
			<?php } ?>
        </select>
		<?php
	}
}

if ( is_admin() ) {
	add_filter( 'posts_where', 'admin_shipping_filter', 10, 2 );
}
function admin_shipping_filter( $where, &$wp_query ) {
	global $pagenow;
	global $wpdb;
	if ( ! isset( $_GET['shipping_method'] ) ) {
		return $where;
	}
	$shipping_method = $_GET['shipping_method'];
	if ( $pagenow == 'edit.php' && ! empty( $shipping_method ) ) {
		$where .= " AND ID IN (SELECT order_id FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_name = 'shipping_method_id' AND order_item_type = '" . $shipping_method . "')";
	}

	return $where;
}

function new_modify_user_table( $column ) {
	$begin = array_slice( $column, 0, 1 );
	$end   = array_slice( $column, 0, count( $column ) - 1 );

	return array_merge( $begin, [ 'id' => 'ID' ], $end );
}

add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
	switch ( $column_name ) {
		case 'id' :
			return $user_id;
		default:
	}

	return $val;
}

add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );

require_once 'components/admin/liqpay-link-to-pay.php';

//add_action('woocommerce_shop_manager_editable_roles', function ($roles) {
//    var_dump($roles);
//    return $roles;
//});