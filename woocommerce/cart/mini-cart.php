<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;

global $woocommerce;
$cross_sells = $woocommerce->cart->get_cross_sells();
do_action( 'woocommerce_before_mini_cart' ); ?>
<div id="bogika-mini-cart"  >
	<?php if ( ! WC()->cart->is_empty() ) :
	$seo = [];
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

		if ( function_exists( 'generateSeo' ) ) {
		$seo[] = generateSeo( array(
			'quantity'       => $cart_item['quantity'],
			'price'          => WC()->cart->get_product_price( $_product ),
			'item_list_name' => 'mini_cart',
            'product'=>$_product
		) );
	}
	}
	?>

    <form class="minicart-form" action="" method="post"
          data-seo="<?php if ( function_exists( 'stringAttribute' ) ) {
		      echo stringAttribute( $seo );
	      } ?>"
    >
	<!-- mCustomScrollbar -->
        <div class="mini-cart__wrap  <?php if ( $cross_sells ) {
			echo 'cross-sale-active';
		} ?>">
			<?php
			$product_ids_in_cart = [];
			?>
            <ul class="basket-services  woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?>"
                data-mcs-theme="dark">
				<?php
				do_action( 'woocommerce_before_mini_cart_contents' );
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $variation_id = $cart_item['variation_id'];
                    $_product              = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id            = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
					$product_ids_in_cart[] = $product_id;
					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                        if($variation_id) {
                            $variation = new WC_Product_Variation($variation_id);
                            $thumbnail = $variation->get_image();
                        }
						$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						$quantity          = $cart_item['quantity'];
						$_product          = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id        = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
						$product           = new WC_Product( $product_id );

						?>
                        <li class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
                            <div class="basket-item-text woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                                <div class="wrap-img">
									<?php
                                        echo $thumbnail;
                                    ?>
                                </div>
                                <div class="text-box">
                                    <a href="<?= $product->get_permalink() ?>"
                                       class="basket-item-title"><?php echo $_product->get_name(); ?></a>
									<?php echo $product->get_short_description(); ?>
                                    <div class="number basket-item-quantity product-quantity">
                                        <script>
                                            // initSpinner();
                                        </script>
										<span class="minus"></span>
										<?php
										if ( $_product->is_sold_individually() ) {
											$min_quantity = 1;
											$max_quantity = 1;
										} else {
											$min_quantity = 1;
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
												//	'spinner',
													'qty'
												), $product ),
											),
											$_product,
											false
										);

										echo $product_quantity;
										?>
										<span class="plus"></span>
                                        <div class="basket-item-price">
											<?php
											echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
											?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-remove">
								<?php
								/* @var WC_Product_Variable $_product */
								$seo = generateSeo( [
									'quantity' => $cart_item['quantity'],
									'item_list_name' => 'mini_cart',
                                    'product'=>$product
								] );
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove remove_from_cart_button" aria-label="%s"
 data-seo=\'%s\'
 data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_attr__( 'Remove this item', 'woocommerce' ),
										function_exists( 'stringAttribute' ) ? stringAttribute( $seo ) : '',
										esc_attr( $product_id ),
										esc_attr( $cart_item_key ),
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
				do_action( 'woocommerce_mini_cart_contents' );
				?>
            </ul>

            <div class="mini-cart__cross-sells">

				<?php
				$cross_sell_ids = array();
				foreach ( $product_ids_in_cart as $product_id ) {
					$cross_sell_ids = array_merge( $cross_sell_ids, wc_get_product( $product_id )->get_cross_sell_ids() );
				}
				$cross_sell_ids = array_unique( $cross_sell_ids );
				$cross_sell_ids = array_diff( $cross_sell_ids, $product_ids_in_cart );

				if ( ! empty( $cross_sell_ids ) ) {
					echo '<h3>' . __( 'Вам може сподобатись', 'woocommerce' ) . '</h3>';
					echo '<ul class="cross-sells-products">';
					foreach ( $cross_sell_ids as $cross_sell_id ) {
						$cross_sell_product = wc_get_product( $cross_sell_id );
						if ( $cross_sell_product ) { ?>
                            <li>
                                <a href="<?= $cross_sell_product->get_permalink( $cross_sell_id ) ?>">
                                    <div class="wrap-img">
                                        <?php
                                        $_product = get_product( $cross_sell_id );
                                        if(get_the_post_thumbnail_url($cross_sell_id, 'full')) echo $cross_sell_product->get_image( 'full' );
                                        elseif($_product->get_type() == 'variable' && $_product->get_available_variations() && $_product->get_available_variations()[0]['image_id'])
                                            echo wp_get_attachment_image($_product->get_available_variations()[0]['image_id'], 'medium');
                                        ?>
                                    </div>
                                </a>
                                <a class="title" href="<?= $cross_sell_product->get_permalink( $cross_sell_id ) ?>">
									<?= $cross_sell_product->get_title( $cross_sell_id ) ?>
                                </a>
                                <div class="price">
									<?php echo $cross_sell_product->get_price_html() ?>
                                </div>

                                <a href="<?= $cross_sell_product->get_permalink( $cross_sell_id ) ?>"
                                   class="button btn">
									<?php echo esc_html( 'До продукту', 'woocommerce' ) ?>
                                </a>
                            </li>
						<?php }
					}
					echo '</ul>';
				}
				?>
            </div>

        </div>

		<?php
		/**
		 * Hook: woocommerce_widget_shopping_cart_total.
		 *
		 * @hooked woocommerce_widget_shopping_cart_subtotal - 10
		 */
		do_action( 'woocommerce_widget_shopping_cart_total' );
		?>

        <div class="basket-services-result">
            <div class="text">ЗАГАЛОМ</div>
            <div class="price"><?= WC()->cart->get_total(); ?></div>
            <a href="/checkout/" class="btn">ОФОРМИТИ ЗАМОВЛЕННЯ</a>
        </div>

		<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

        <!--<p class="woocommerce-mini-cart__buttons buttons"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>-->

		<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>

		<?php else : ?>

            <p class="woocommerce-mini-cart__empty-message"><?php esc_html_e( 'No products in the cart.', 'woocommerce' ); ?></p>

		<?php endif; ?>
        <input type="hidden" name="_wp_http_referer" value="<?php echo wc_get_cart_url(); ?>">
        <input type="hidden" name="update_cart" value="Update Cart">
    </form>

	<?php do_action( 'woocommerce_after_mini_cart' ); ?>


</div>

<script>




	jQuery(function ($) {
        $('body').on('keydown', '.qty', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                $('body #bogika-mini-cart input.qty').trigger('change');
            }
        });
			// Обробка зміни кількості в кошику
			$(document).off('change', '#bogika-mini-cart input.qty').on('change', '#bogika-mini-cart input.qty', function () {
				var $this = $(this);
				var $container = $('#bogika-mini-cart');
				var item_hash = $this.attr('name') ? $this.attr('name').replace(/cart\[([\w]+)\]\[qty\]/g, "$1") : null;
				var item_quantity = $this.val();
				var currentVal = parseFloat(item_quantity);

				if (currentVal > 0 && !$this.prop('disabled')) {
					$container.addClass('loading');
					$this.prop('disabled', true);

						// Перший AJAX запит
						$.ajax({
							type: 'POST',
							url: "<?php echo site_url() . '/wp-admin/admin-ajax.php'; ?>",
							data: {
								action: 'my_cart_qty',
								hash: item_hash,
								quantity: currentVal
							},
							success: function (response) {
								document.getElementById('bogika-mini-cart').outerHTML = response;

								var newTotal = response.cart_total;
								$('.cart-total').text(newTotal);
								initializeSlick()
								updateDisabledButtons();
								
							},
							error: function (error) {
								console.error('Помилка першого AJAX:', error);
							},
							complete: function () {
								$this.prop('disabled', false);
								$container.removeClass('loading');
								
							}
						});

						// Другий AJAX запит із затримкою
                        setTimeout(function() {
						$.ajax({
							type: 'POST',
							url: "<?php echo site_url() . '/wp-admin/admin-ajax.php'; ?>",
							data: {
								action: 'my_cart_num_qty',
								hash: item_hash,
								quantity: currentVal
							},
							success: function (response) {
								$('.basket-num').html(response);
								
							},
							error: function (error) {
								console.error('Помилка другого AJAX:', error);
							},
							complete: function () {
								$this.prop('disabled', false);
								$container.removeClass('loading');
							}
						}); }, 3000);
				} else {
					console.warn('Умова не пройдена: currentVal <= 0 або поле заблоковано');
				}
			});

			// Делегування подій для кнопок + та -

			$(document).off('click', '.plus').on('click', '.plus', function () {
				var $input = $(this).siblings('.quantity').find('.qty');
				var currentValue = parseInt($input.val());
				var maxValue = parseInt($input.attr('max')) || Infinity; // Якщо max не заданий, то Infinity
				var $minusButton = $(this).siblings('.minus');

				if (currentValue < maxValue) {
					$input.val(currentValue + 1).trigger('change'); // Тригеримо input для оновлення
				}

				// Оновлення стану кнопок
				if (currentValue + 1 >= maxValue) {
					$(this).addClass('disabled');
				}
				if (currentValue + 1 > 1) {
					$minusButton.removeClass('disabled');
				}
			});

			$(document).off('click', '.minus').on('click', '.minus', function () {
				var $input = $(this).siblings('.quantity').find('.qty');
				var currentValue = parseInt($input.val());
				var minValue = parseInt($input.attr('min')) || 0; // Якщо min не заданий, то 0
				var $plusButton = $(this).siblings('.plus');

				if (currentValue > minValue) {
					$input.val(currentValue - 1).trigger('change'); // Тригеримо input для оновлення
				}
				console.log($input.val())
				//Оновлення стану кнопок
				if (currentValue - 1 <= minValue) {
					$(this).addClass('disabled');
					
				}
				
				if (currentValue - 1 < parseInt($input.attr('max')) || Infinity) {
					$plusButton.removeClass('disabled');
				}

				
			});

			$(document).off('input', '.qty').on('input', '.qty', function () {
				var $this = $(this);
				var currentValue = parseInt($this.val());
				var minValue = parseInt($this.attr('min')) || 1;
				var maxValue = parseInt($this.attr('max')) || Infinity;
				var $minusButton = $this.closest('.number').find('.minus');
				var $plusButton = $this.closest('.number').find('.plus');

				// Виправляємо значення, якщо воно виходить за межі
				if (currentValue < minValue) {
					$this.val(minValue);
					currentValue = minValue;
				} else if (currentValue > maxValue) {
					$this.val(maxValue);
					currentValue = maxValue;
				}

				// Оновлення стану кнопок
				if (currentValue <= minValue) {
					$minusButton.addClass('disabled');
				} else {
					$minusButton.removeClass('disabled');
				}

				if (currentValue >= maxValue) {
					$plusButton.addClass('disabled');
				} else {
					$plusButton.removeClass('disabled');
				}
			});

			$(document).ready(function () {
				$('.qty').each(function () {
					var $this = $(this);
					var currentValue = parseInt($this.val());
					var minValue =  1;
					var maxValue = parseInt($this.attr('max')) || Infinity;
					var $minusButton = $this.closest('.number').find('.minus');
					var $plusButton = $this.closest('.number').find('.plus');

					// Ініціалізація стану кнопок
					if (currentValue <= minValue) {
						$minusButton.addClass('disabled');
					} else {
						$minusButton.removeClass('disabled');
					}

					if (currentValue >= maxValue) {
						$plusButton.addClass('disabled');
					} else {
						$plusButton.removeClass('disabled');
					}
				});
			});


			function initializeSlick() {
				var $crossSells = $(".cross-sells-products");
				
				if ($crossSells.length > 0) {
					
					if ($crossSells.hasClass('slick-initialized')) {
						$crossSells.slick('unslick'); 
					}

					$crossSells.slick({
						slidesToShow: 2,
						slidesToScroll: 1,
						arrows: false,
						responsive: [{
							breakpoint: 768,
							settings: {
								slidesToShow: 1,
								slidesToScroll: 1
							}
						}]
					});
				} 
			}




			$(document).ajaxComplete(function () {
				initializeSlick(); 
			});



			$(document).ajaxComplete(function() {
			updateDisabledButtons();
			});

			function updateDisabledButtons() {
			$('.number').each(function() {
				var $this = $(this);
				var $input = $this.find('.qty');
				var currentValue = parseInt($input.val(), 10);
				var minValue = parseInt($input.attr('min'), 10) || 1;
				var maxValue = parseInt($input.attr('max'), 10) || Infinity;
				var $minusButton = $this.find('.minus');
				var $plusButton = $this.find('.plus');

				if (currentValue <= minValue) {
				$minusButton.addClass('disabled');
				} else {
				$minusButton.removeClass('disabled');
				}

				if (currentValue >= maxValue) {
				$plusButton.addClass('disabled');
				} else {
				$plusButton.removeClass('disabled');
				}
			});
			}

	

			
	});

		

</script>
