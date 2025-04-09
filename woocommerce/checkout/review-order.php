<?php
/**
 * Таблиця огляду замовлення
 *
 * Цей шаблон можна перезаписати, скопіювавши його в yourtheme/woocommerce/checkout/review-order.php.
 *
 * ОДНАК, з часом WooCommerce може змінювати файли шаблонів і вам (розробнику теми) потрібно буде скопіювати нові файли в вашу тему, щоб забезпечити сумісність. Ми намагаємося робити це якомога рідше, але це трапляється. Коли це відбувається, версія файлу шаблону буде збільшена, а readme перерахує всі важливі зміни.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
//pr(get_class_methods('WC_BOGOF_Cart'));
?>

<?php //bogo_calculate_checkout_discount() ?>

<div class="shop_table woocommerce-checkout-review-order-table">
    <div class="basket-block-container">
        <ul class="basket-services mCustomScrollbar" data-mcs-theme="dark">
            <?php
            do_action( 'woocommerce_review_order_before_cart_contents' );

            // Ініціалізуємо змінну $cart_item_key перед циклом
            $cart_item_key = '';

            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                    ?>
                    <li>
                        <div class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> basket-item-text">
                            <div class="wrap-img">
                                <?= $_product->get_image( 'medium' ); ?>
                            </div>
                            <div class="text-box">
                                <a href="<?= $_product->get_permalink() ?>" class="basket-item-title"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?> x <?= $cart_item['quantity'] ?></a>
                                <p><?php echo $_product->get_short_description(); ?></p>
                                <div class="basket-item-quantity">
                                    <div class="basket-item-price"><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php
                }
            }

            do_action( 'woocommerce_review_order_after_cart_contents' );
            ?>
        </ul>

        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
            <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                    <div class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                        <div><?php echo esc_html( $tax->label ); ?></div>
                        <div><?php echo wp_kses_post( $tax->formatted_amount ); ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="tax-total">
                    <div><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></div>
                    <div><?php wc_cart_totals_taxes_total_html(); ?></div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
        <?php woocommerce_checkout_coupon_form(); ?>
        <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

        <div class="bonuses-wrap">
            <?php
            if ( function_exists( 'bogika_spisaniebonusov_in_checkout' ) ) {
                bogika_spisaniebonusov_in_checkout();
            }
            ?>
        </div>
        <?php

        // Обчислимо загальну знижку BOGO
        $raw_discount_total = 0;


        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            /*
            if ( WC_BOGOF_Cart::is_valid_discount( $cart_item ) ) {
                $discount_per_item = $cart_item['data']->_bogof_discount->get_discount() / $cart_item['quantity'];
                $total_discount_for_item = $cart_item['quantity'] * $discount_per_item;
                $raw_discount_total += $total_discount_for_item;
               }
                */
        }
        ?>

        <div class="basket-services-result">
            <div class="text"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></div>
            <?php
            $subtotal_price = 0;

            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $product = $cart_item['data'];
                $quantity = $cart_item['quantity'];
                // Отримати ціну товару, враховуючи знижки
                $item_price = $product->get_sale_price() ? $product->get_sale_price() : $product->get_regular_price();

                // Перевірити, чи товар підпадає під акцію BOGO
               /* if ( WC_BOGOF_Cart::is_valid_discount( $cart_item ) ) {
                    if ( WC_BOGOF_Cart::is_valid_free_item( $cart_item ) ) {
                        // Якщо товар безкоштовний, його ціна = 0
                        $item_price = 0;

                    } else {
                        // Якщо товар підпадає під знижку BOGO, використовуємо регулярну ціну
                        $item_price = $product->get_regular_price();
                    }
                }*/

                // Обчислити підсумкову ціну
                $subtotal_price += $item_price * $quantity;
            }
            ?>
            <div class="price"><?php echo wc_price( $subtotal_price ); ?></div>
        </div>

        <?php if ( WC()->cart->get_coupons() ) : ?>
            <div class="coupon-wrap">
                <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                    <div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                        <div><?php wc_cart_totals_coupon_label( $coupon ); ?></div>
                        <div><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="fee basket-services-result">
                <div class="text"><?php echo esc_html( $fee->name ); ?></div>
                <div class="price"><?php wc_cart_totals_fee_html( $fee ); ?></div>
            </div>
        <?php endforeach; ?>

        <?php if ( $raw_discount_total > 0 ) : ?>
            <div class="fee basket-services-result">
                <div class="text"><?php esc_html_e( 'Discount', 'bogika' ); ?> <?php esc_html_e( 'Gift', 'bogika' ); ?></div>
                <div class="price"><?php echo wc_price( $raw_discount_total ); ?></div>
            </div>
        <?php endif; ?>

        <?php
        // Обчислити загальну знижку від купонів
        $coupon_discount_total = 0;
        if ( WC()->cart->get_coupons() ) {
            foreach ( WC()->cart->get_coupons() as $code => $coupon ) {
                $discount_amount = WC()->cart->get_coupon_discount_amount( $code );
                $coupon_discount_total += $discount_amount;
                $debug_log[] = "Coupon code: $code. Discount amount: $discount_amount. Updated coupon_discount_total: $coupon_discount_total";
            }
        }

        // Загальний підсумок замовлення з урахуванням знижок
        $subtotal_price_no_discounts = WC()->cart->get_subtotal(); // Підсумкова сума без знижок

        //$total_discount = $raw_discount_total + $coupon_discount_total;
        $total_discount = $coupon_discount_total;


        $total_price = wc_price( $subtotal_price_no_discounts - $total_discount );

        ?>

        <div class="basket-services-result">
            <div class="text"><?php esc_html_e( 'Total', 'woocommerce' ); ?></div>
            <div class="price"><?php wc_cart_totals_order_total_html();//mzi//echo $total_price; ?></div>
        </div>

    </div>

</div>
