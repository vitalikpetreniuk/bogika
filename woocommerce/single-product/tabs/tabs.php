<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>

	<div class="product-info-description">
		<div class="tabset">
			<ul class="tab-control flexbox" role="tablist">
				<?php
				$i = 0;
				foreach ( $product_tabs as $key => $product_tab ) :$i ++ ?>
					<li class="<?php echo esc_attr( $key ); ?>_tab <?php if ( $i === 1 )
						echo 'active' ?>" id="tab-title-<?php echo esc_attr( $key ); ?>"
					    role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
						<a href="#">
							<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="tab-body">
				<?php
				$i = 0;
				foreach ( $product_tabs as $key => $product_tab ) :$i ++; ?>
					<div
						class="tab <?php if ( $i === 1 )
							echo 'active' ?>"
						id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel"
						aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
						<div class="accordion-mobile-opener"><?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?></div>
						<div class="accordion-mobile-slide">
							<?php
							if ( isset( $product_tab['callback'] ) ) {
								call_user_func( $product_tab['callback'], $key, $product_tab );
							}
							?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<?php do_action( 'woocommerce_product_after_tabs' ); ?>
		</div>
	</div>

<?php endif; ?>
<?php global $product;
if ( get_field( 'product_technologies', $product->get_id() ) ) : ?>
    <ul class="product-technology-list flexbox mobile">
        <?php foreach ( get_field( 'product_technologies', $product->get_id() ) as $technology ) { ?>
            <li>
                <div class="wrap-img">
                    <img src="<?= $technology['image']['url'] ?>" alt="<?= $technology['image']['url'] ?>">
                </div><?= $technology['title'] ?>
            </li>
        <?php } ?>
    </ul>
<?php endif; ?>
