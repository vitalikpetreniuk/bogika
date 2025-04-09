<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

<div class="woocommerce-form-login-toggle">
    <!-- <h2><?php esc_html_e( 'Create an order', 'bogika' ); ?></h2> -->
	<?php if ( ! is_user_logged_in() ) : ?>
		<?php wc_print_notice( ' <a href="#" class="showlogin order-link">' . esc_html__( 'Have an account', 'bogika' ) . '</a>', 'notice' ); ?>
	<?php endif; ?>
</div>
<?php

woocommerce_login_form(
	array(
		'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woocommerce' ),
		'redirect' => wc_get_checkout_url(),
		'hidden'   => true,
	)
);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );

	return;
}

?>
<div class="order-section flexbox">
    <div class="order-block">
        <div class="user-form">
			<?php
			$seo = [];
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				/* @var WC_Product_Simple $_product */
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					?>
					<?php
					$seo[] = generateSeo(
						[
							'quantity'       => $cart_item['quantity'],
							'item_list_name' => 'purchase',
							'product'        => $_product
						]
					);
				}
			}
			?>
            <script>
                dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
                dataLayer.push({
                    event: "begin_checkout",
                    ecommerce: {
                        items: <?= jsonSeoData( $seo ) ?>
                    }
                });
            </script>
            <form name="checkout" method="post" class="checkout woocommerce-checkout"
                  action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data"
                  data-seo="<?= stringAttribute( $seo ) ?>"
            >

				<?php if ( $checkout->get_checkout_fields() ) : ?>

					<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                    <div id="customer_details">
						<?php do_action( 'woocommerce_checkout_billing' );//доставка теж ?>
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
                    </div>
                    <h2><?php esc_html_e( 'Payment', 'bogika' ); ?></h2>
					<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

				<?php endif; ?>
            </form>
        </div>
    </div>
    <div class="basket-block">
		<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

        <h3 id="order_review_heading"><?php esc_html_e( 'Your order', 'woocommerce' ); ?></h3>

		<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

        <div id="order_review" class="woocommerce-checkout-review-order">
			<?php do_action( 'woocommerce_checkout_order_review' ); ?>
        </div>

		<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
    </div>
</div>
<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
