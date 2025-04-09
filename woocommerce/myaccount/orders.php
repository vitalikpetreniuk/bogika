<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>
	<div class="heading">
		<h2><?php esc_html_e( 'History of orders', 'bogika' ); ?></h2>
	</div>
	<div
		class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<ul class="accordion accordion-goods">
			<?php foreach ( $customer_orders->orders as $customer_order ) {
				$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$item_count = $order->get_item_count() - $order->get_item_count_refunded();
				?>
				<li class="">
					<div class="opener">
						<div class="goods-order">
							<div class="text"><?php esc_html_e( 'Order', 'bogika' ); ?> <span
									class="number">№<?= $order->get_order_number() ?></span> <?php esc_html_e( 'from', 'bogika' ); ?>
								<time
									datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
							</div>
							<div class="cost"><?= $order->get_formatted_order_total() ?></div>
							<?php $count = count( $order->get_items() ) - 2; ?>
							<div class="goods">
								<?php
								$i = 0;
								foreach ( $order->get_items() as $item_id => $order_item ) :$i ++;
									/* @var WC_Product $product */
									$product = $order_item->get_product();
									?>
									<div class="wrap-img">
										<?= $product->get_image(); ?>
									</div>
									<?php if ( $i == 2 ) {
										break;
									} ?>
								<?php endforeach; ?>
								<?php if ( $count > 0 ) : ?>
									<div class="quantity">+<?= $count ?></div>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="accordion-slide">
						<ul class="basket-services">
							<li>
								<div class="basket-item-text"><?php esc_html_e( 'Products', 'bogika' ); ?></div>
								<div class="basket-item-cost"><?php esc_html_e( 'Price', 'bogika' ); ?></div>
								<div class="basket-item-quantity"><?php esc_html_e( 'Quantity', 'bogika' ); ?></div>
								<div class="basket-item-result"><?php esc_html_e( 'Total', 'bogika' ); ?></div>
							</li>
							<?php
							//							var_dump($order);
							foreach ( $order->get_items() as $item_id => $order_item ) :
								/* @var WC_Product $product */
								$product = $order_item->get_product();
								?>
								<li>
									<div class="basket-item-text">
										<div class="wrap-img">
											<?= $product->get_image(); ?>
										</div>
										<div class="text-box">
											<a href="<?= $product->get_permalink() ?>"
											   class="basket-item-title"><?= $order_item['name'] ?></a>
											<?php if ( $product->get_short_description() ) : ?>
												<p><?= $product->get_short_description(); ?></p>
											<?php endif; ?>
										</div>
									</div>
									<div class="basket-item-cost"><?= wc_price( $product->get_price() ); ?></div>
									<div class="basket-item-quantity"><?= $order_item['quantity'] ?></div>
									<div class="basket-item-result"><?= wc_price( $order_item->get_total() ) ?></div>
								</li>
							<?php endforeach; ?>
						</ul>
						<div class="goods-order-info flexbox">
							<div class="item">
								<?php foreach ( $order->get_shipping_methods() as $method ) : ?>
									<?php if ( $method['method_id'] == 'ukrposhta_shippping' ) : ?>
										<div class="address"><b><?= $order->get_shipping_method(); ?>
											:</b> <?= $order->get_shipping_address_1().', '.$order->get_shipping_postcode() ?>
									<?php else: ?>
											<div class="address"><b><?= $order->get_shipping_method(); ?>
                                                    <?php if ($order->get_billing_address_1()) echo ':'?></b> <?= $order->get_billing_address_1()?>
									<?php endif; ?>
									</div>
								<?php endforeach; ?>
								<div class="name"><?= $order->get_formatted_billing_full_name() ?></div>
								<ul class="contact flexbox">
									<li class="phone"><a
											href="tel:<?= $order->get_billing_phone(); ?>"><?= $order->get_billing_phone(); ?></a>
									</li>
									<li class="email"><a
											href="mailto:<?= $order->get_billing_email(); ?>"><?= $order->get_billing_email(); ?></a>
									</li>
								</ul>
							</div>
							<div class="item">
								<div class="delivery">
									<div class="text"><?php esc_html_e( 'Shipping', 'bogika' ); ?></div>
									<div
										class="text"><?php echo ( $order->get_shipping_total() > 0 ) ? $order->get_shipping_total() : __( 'By carrier rates', 'bogika' ) ?></div>
								</div>
								<div class="result">
									<div class="text"><?php esc_html_e( 'Total', 'bogika' ); ?></div>
									<div class="cost"><?= $order->get_formatted_order_total() ?></div>
								</div>
							</div>
						</div>
					</div>
				</li>
				<?php
			}
			?>
		</ul>
	</div>
	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button"
				   href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button"
				   href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div
		class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button btn"
		   href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse products', 'woocommerce' ); ?></a>
		<div><?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?></div>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
