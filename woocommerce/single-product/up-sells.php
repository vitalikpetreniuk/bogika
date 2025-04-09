<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $upsells ) : ?>

	<section class="up-sells upsells slider-block-inner products">
		<div class="top-text-slider">
			<h2 class="h2"><?php esc_html_e( 'With this people buy', 'bogika' ); ?></h2>
			<p><?= get_field( 'rps_text_1' ) ?><span class="no-mobile"><?= get_field( 'rps_text_2' ) ?></span></p>
		</div>
		<ul class="goods-list goods-list-slider">
			<?php foreach ( $upsells as $upsell ) : ?>

				<?php
				$post_object = get_post( $upsell->get_id() );

				setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

				wc_get_template_part( 'content', 'product' );
				?>

			<?php endforeach; ?>
		</ul>

		<?php
		$seo   = [];
		$ids   = [];
		$value = 0;
		foreach ( $upsells as $upsell ) {
			$product = wc_get_product( $upsell->get_id() );
			$seo[] = generateSeo([
				'quantity' => 1,
                'product'=>$product,
			]);
			$value .= $product->get_price();
			$ids[] = $product->get_id();
		}
		?>
		<script>
            dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
            dataLayer.push({
                event: 'view_item_list',
                ecommerce: {
                    items: <?= jsonSeoData( $seo ) ?>
                }
            })
		</script>
	</section>

<?php
endif;

wp_reset_postdata();
