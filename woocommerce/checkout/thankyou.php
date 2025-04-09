<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

if ( isset( $_GET['wc_order_id'] ) ) {
	$order = wc_get_order( $_GET['wc_order_id'] );
}
?>

<div class="woocommerce-order">

	<?php
	if ( $order ) :
		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

        <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
            <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>"
               class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
			<?php if ( is_user_logged_in() ) : ?>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"
                   class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
			<?php endif; ?>
        </p>

	<?php else : ?>

        <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

		<p style="margin-bottom:3px">
			<?php esc_html_e( 'Have you already tried', 'woocommerce' );?> <strong><?php esc_html_e( 'BOGI CACAO natural chocolate?', 'woocommerce' );?></strong>
		</p>
		<p style="margin-bottom:3px">
			<?php esc_html_e( 'Add delicious BOGI CACAO sweets to your prayers and we will combine your orders, and you', 'woocommerce' );?><strong> <?php esc_html_e('will receive savings on delivery.', 'woocommerce' );?></strong>
		</p>
		<p style="margin-bottom:3px; margin-top:10px;">
			<?php esc_html_e( 'How to do it? ', 'woocommerce' );?>
		</p>
		<ol>
			<li>
				<?php esc_html_e( 'Go to the website?', 'woocommerce' );?> <a target="_blank" href="https://bogicacao.com.ua/"><?php esc_html_e( ' BOGI CACAO', 'woocommerce' );?></a>
			</li>
			<li>
				<?php esc_html_e( 'Choose sweets and proceed to Checkout.', 'woocommerce' );?>
			</li>
			<li>
			<?php esc_html_e('In the shopping cart, in the Additional Information (Notes to your order) field, enter the word', 'woocommerce');?> <strong><?php esc_html_e('«BOGIKA»','woocommerce')?></strong> <?php esc_html_e('or your', 'woocommerce');?><strong> <?php esc_html_e('email', 'woocommerce' );?></strong>
			</li>
		</ol>

		<a style="margin-bottom:40px" class="btn" target="_blank" href="https://bogicacao.com.ua/"><?php esc_html_e( 'Go to the BOGI CACAO website', 'woocommerce' );?></a>

        <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

            <li class="woocommerce-order-overview__order order">
				<?php esc_html_e( 'Order number:', 'woocommerce' ); ?>
                <strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
            </li>

            <li class="woocommerce-order-overview__date date">
				<?php esc_html_e( 'Date:', 'woocommerce' ); ?>
                <strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
            </li>

			<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
                <li class="woocommerce-order-overview__email email">
					<?php esc_html_e( 'Email:', 'woocommerce' ); ?>
                    <strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
                </li>
			<?php endif; ?>

            <li class="woocommerce-order-overview__total total">
				<?php esc_html_e( 'Total:', 'woocommerce' ); ?>
                <strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
            </li>

			<?php if ( $order->get_payment_method_title() ) : ?>
                <li class="woocommerce-order-overview__payment-method method">
					<?php esc_html_e( 'Payment method:', 'woocommerce' ); ?>
                    <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
                </li>
			<?php endif; ?>

        </ul>

	<?php endif; ?>
	<?php
	$coupons_count = count( $order->get_coupon_codes() );
	$coupons_list  = '';
	foreach ( $order->get_coupon_codes() as $coupon ) {
		$coupons_list .= $coupon;
		if ( $i < $coupons_count ) {
			$coupons_list .= ', ';
		}
		$i ++;
	}
	?>

	<?php if ( $order->get_payment_method() == 'cod' ) : ?>
        <script>
			<?php
			$seo = [];
			foreach ( $order->get_items() as $item ) {
				$product = $item->get_product();
				$id      = $product->get_id();
				$seo[]   = generateSeo( array(
					'quantity'       => $item->get_quantity(),
                    'product'=>$product,
					'item_list_name' => 'purchase'
				) );
			};?>

            dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
            dataLayer.push({
                event: "purchase",
                email: "<?= $order->get_billing_email() ?>", // email з замовлення
                phone: "<?= $order->get_billing_phone() ?>", //телефон  з замовлення
                ecommerce: {
                    transaction_id: "<?= $order->get_id() ?>",
                    value: "<?= $order->get_total() ?>",
					<?php if ($order->get_shipping_total() > 0) : ?>
                    shipping: "<?= $order->get_shipping_total() ?>", //если есть, то вместо "0" пишем сумму. Если нет, то не пишем переменную вовсе.
					<?php endif; ?>
                    currency: "UAH",
                    items: <?= jsonSeoData($seo) ?>
                }
            });
        </script>
	<?php endif; ?>
	<!-- <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?> -->
	<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

        <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>


	<a style="display: block;text-align: center;margin-top: 3rem;" href="/">
		<?php esc_html_e( 'Home page ', 'woocommerce' );?>
	</a>
</div>


