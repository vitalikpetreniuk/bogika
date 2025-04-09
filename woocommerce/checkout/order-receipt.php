<?php
/**
 * Checkout Order Receipt Template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/order-receipt.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<script>
	<?php
	$seo = [];
	foreach ( $order->get_items() as $item ) {
		$product = $item->get_product();
		$id      = $product->get_id();
		$seo[]   = generateSeo( array(
			'quantity' => $item->get_quantity(),
			'item_list_name'=>'purchase',
            'product'=>$product
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

<ul class="order_details">
	<li class="order">
		<?php esc_html_e( 'Order number:', 'woocommerce' ); ?>
		<strong><?php echo esc_html( $order->get_order_number() ); ?></strong>
	</li>
	<li class="date">
		<?php esc_html_e( 'Date:', 'woocommerce' ); ?>
		<strong><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></strong>
	</li>
	<li class="total">
		<?php esc_html_e( 'Total:', 'woocommerce' ); ?>
		<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
	</li>
	<?php if ( $order->get_payment_method_title() ) : ?>
	<li class="method">
		<?php esc_html_e( 'Payment method:', 'woocommerce' ); ?>
		<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
	</li>
	<?php endif; ?>
</ul>

<?php do_action( 'woocommerce_receipt_' . $order->get_payment_method(), $order->get_id() ); ?>

<div class="clear"></div>
