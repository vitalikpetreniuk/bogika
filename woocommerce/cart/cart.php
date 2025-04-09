<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>
    <div class="basket-block shop_table shop_table_responsive cart woocommerce-cart-form__contents">
        <div class="basket-block-container">
            <ul class="basket-services">
				<?php do_action( 'woocommerce_before_cart_contents' ); ?>

				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
					$product    = new WC_Product( $product_id );
					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
                        <li>
                            <div
                                    class="basket-item-text woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                            <div class="wrap-img">
                                <?php
                                $_product = get_product( $product_id );
                                if(get_the_post_thumbnail_url($product_id, 'full')) echo $product->get_image( 'medium' );
                                elseif($_product->get_type() == 'variable' && $_product->get_available_variations() && $_product->get_available_variations()[0]['image_id'])
                                    echo wp_get_attachment_image($_product->get_available_variations()[0]['image_id'], 'medium');
                                ?>
                            </div>
                                <div class="text-box">
                                    <a href="<?= $product->get_permalink() ?>"
                                       class="basket-item-title"><?php echo $_product->get_name(); ?></a>
									<?php echo $product->get_short_description(); ?>
                                    <div class="number basket-item-quantity product-quantity">
										<?php
										if ( $_product->is_sold_individually() ) {
											$min_quantity = 1;
											$max_quantity = 1;
										} else {
											$min_quantity = 0;
											$max_quantity = $_product->get_max_purchase_quantity();
										}

										$product_quantity = woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $max_quantity,
												'min_value'    => $min_quantity,
												'product_name' => $_product->get_name(),
												'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array(
													'spinner',
													'qty'
												), $product ),
											),
											$_product,
											false
										);

										echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
										?>
                                        <div class="basket-item-price"><?php
                                            echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                            ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-remove">
								<?php
								$seo = generateSeo( [
									'quantity'       => $cart_item['quantity'],
									'item_list_name' => 'cart',
									'product'        => $_product
								] );
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove" data-seo=\'%s\' aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										stringAttribute( $seo ),
										esc_html__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								);
								?>
                            </div>
                        </li>
						<?php
					}
				}
				?>
            </ul>
            <div class="basket-services-result">
                <div class="text"><?php esc_html_e( 'Total', 'bogika' ) ?></div>
                <div class="price"><?php wc_cart_totals_order_total_html(); ?></div>
            </div>
			<?php do_action( 'woocommerce_cart_contents' ); ?>

            <button type="submit"
                    class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"
                    name="update_cart"
                    value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

			<?php do_action( 'woocommerce_cart_actions' ); ?>

			<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
        </div>

		<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

        <div class="cart-collaterals">
			<?php
			/**
			 * Cart collaterals hook.
			 *
			 * @hooked woocommerce_cross_sell_display
			 * @hooked woocommerce_cart_totals - 10
			 */
			do_action( 'woocommerce_cart_collaterals' );
			?>
        </div>

    </div>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>
<?php echo do_shortcode('[bfw-write-off-bonuses]'); ?>
<?php do_action( 'woocommerce_after_cart' ); ?>
