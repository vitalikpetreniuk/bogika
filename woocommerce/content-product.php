<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
$product_published = $product->get_date_created();
$args              = [
	'quantity' => 1,
	'product'  => $product
];
if ( is_shop() ) {
	$args['item_list_name'] = 'Shop';
} elseif ( is_product_category() ) {
	$args['item_list_name'] = 'Category - ' . get_queried_object()->name;
}
if (function_exists('generateSeo')) {
$seo = generateSeo( $args );
}else {
    $seo = [];
}
?>
<li>
    <div class="goods-box <?= $product->get_stock_status(); ?>" data-seo='<?php if (function_exists('stringAttribute')) echo stringAttribute( $seo ) ?>'
         data-href="<?php the_permalink(); ?>">
        <div class="promotion-wrapper">
			<?php
			$terms = get_the_terms( $product->get_id(), 'product_tag' );
			if($terms) foreach ( $terms as $term ) {
				echo '<div class="promotion-text ' . $term->slug . '">' . $term->name . '</div>';
			}
			if ( $product->is_on_sale() ) {
				echo '<div class="promotion-text">Акція</div>';
			} elseif ( $product->is_featured() ) {
				echo '<div class="promotion-text">ТОП</div>';
			}
            $billets = [];
            for ( $i = 0; $i <= 9; $i ++ ) {
                $label = get_post_meta( $product->get_id(), 'billet_' . $i . '_label', true );
                $bg    = get_post_meta( $product->get_id(), 'billet_' . $i . '_bg', true );
                $color = get_post_meta( $product->get_id(), 'billet_' . $i . '_color', true );
                if ( $label || $bg || $color ) {
                    $billets[] = [ 'label' => $label, 'bg' => $bg, 'color' => $color ];
                }
            }

            foreach ( $billets as $billet ) {
                echo '<div style="background:' . $billet['bg'] . '; color:' . $billet['color'] . ';" class="promotion-text billet">' . $billet['label'] . '</div>';
            };
			?>

		</div>
		<?php do_action( 'bogika_loop_wishlist' ); ?>
        <a href="<?= $product->get_permalink() ?>">
            <div class="wrap-img">
                <?php if(get_post_thumbnail_id( $product->get_id() )) { ?>
                    <img title = "<?=$product->get_title()?>" alt = "<?php esc_html_e('Buy','bogika'); ?> <?=$product->get_title()?>" src="<?php echo wp_get_attachment_url( $product->get_image_id(), 'full' ); ?>" />
                <?php }
                elseif($product->is_type('variable')) {
                    $variations = $product->get_available_variations(); ?>
                    <img title = "<?=$product->get_title()?>" alt = "<?php esc_html_e('Buy','bogika'); ?> <?=$product->get_title()?>" src='<?=$variations[0]['image']['full_src']?>'>
                    <?php
                }
                else { ?>
                    <img title = "<?=$product->get_title()?>" alt = "<?php esc_html_e('Buy','bogika'); ?> <?=$product->get_title()?>" src="<?php echo wp_get_attachment_url( $product->get_image_id(), 'full' ); ?>" />
                <?php } ?>
            </div>
        </a>
		<a class="title" href="<?= $product->get_permalink() ?>">
			<?= $product->get_title() ?>
		</a>
		<?php echo wpautop( $product->get_short_description() ); ?>
		<?php woocommerce_template_loop_price(); ?>
		<?php woocommerce_template_loop_add_to_cart(); ?>
	</div>
</li>
