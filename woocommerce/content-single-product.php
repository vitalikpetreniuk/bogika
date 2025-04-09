<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form(); // WPCS: XSS ok.

    return;
}
?>
<?php
$seo = generateSeo(
    [
        'quantity' => 1,
        'product'  => $product
    ]
);
?>
<script>
    dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
    dataLayer.push({
        event: "view_item",
        ecommerce: {
            items: <?= jsonSeoData([$seo]) ?>
        }
    });
</script>
<?php
$args = array(
    'number'      => 10,
    'status'      => 'approve',
    'post_status' => 'publish',
    'post_type'   => 'product',
    'post_id'     => $product->get_id(),
);

$comments = get_comments( $args );
if($product->get_type() == 'variable') $prod_image = $product->get_available_variations()[0]['image']['url'];
else $prod_image = get_the_post_thumbnail_url( $product->get_id(), 'full' );
?>



<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'product-info-section', $product ); ?>>
    <div class="product-info-block flexbox">

        <div class="product-info-gallery">
            <h1 class="class long-mobile mobile"><?= $product->get_title() ?></h1>
            <div class="gallery-slider 1">
                <?php
                if($product->get_image_id())
                $idsimgs = [ $product->get_id() => $product->get_image_id(), ...$product->get_gallery_image_ids() ];
                else $idsimgs = $product->get_gallery_image_ids();
                if ( $product->is_type( 'variable' ) ) {
                    foreach ( $product->get_available_variations() as $item ) {
                        $idsimgs[ $item['variation_id'] ] = $item['image_id'];
                    }
                }
                $newidsimg = [];
                foreach ( $idsimgs as $key => $val ) {
                    $values = array_count_values( $newidsimg );
                    if (  ! isset( $values[ $val ] ) )  {
                        $newidsimg[ $key ] = $val;
                    }
                }
                if ( $product->is_type( 'variable' ) && isset($newidsimg[$product->get_id()]))
                  unset($newidsimg[$product->get_id()]);
                ?>
                <ul class="slider-for">
                    <?php foreach ( $newidsimg as $key => $img ) : ?>
                        <li>
                            <a data-id="<?= $key ?>" class="product-view"
                               href="<?= wp_get_attachment_image_url( $img, 'full' ); ?>"
                               data-lightbox="roadtrip">
                                <?= wp_get_attachment_image( $img, 'full' ); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <?php
                    $video_url = get_field('posylannya_na_video');
                    if($video_url) : ?>
                        <li>
                            <?php

                            preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video_url, $matches);
                            $youtube_video_id = $matches[1];
                            ?>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $youtube_video_id; ?>" frameborder="0" allowfullscreen></iframe>
                        </li>
                    <?php endif;?>
                </ul>
                <?php if ( count( $newidsimg ) > 1 || $video_url) : ?>
                    <ul class="product-view-list slider-nav 111">
                        <?php foreach ( $newidsimg as $key => $img ) : ?>
                            <li>
                                <div class="wrap-img">
                                    <?= wp_get_attachment_image( $img, 'full' ) ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                        <?php
                        $video_url = get_field('posylannya_na_video');
                        if($video_url) : ?>
                            <li>

                                <div class="player">
                                    <?php echo wp_get_attachment_image(get_field('kartynka_video')) ?>
                                    <div class="play-button"></div>
                                </div>

                            </li>
                        <?php endif;?>
                    </ul>
                <?php endif; ?>
                <?php if ( ! empty( $attachment_ids ) ) { ?>
                    <ul class="product-view-list slider-nav">
                        <li>
                            <div class="wrap-img">
                                <img src="<?= get_the_post_thumbnail_url( $product_id, 'full' ) ?>"
                                     alt="<?= $product->get_title() ?>">
                            </div>
                        </li>
                        <?php
                        $attachment_ids = $product->get_gallery_image_ids();
                        foreach ( $attachment_ids as $key => $attachment_id ) {
                            $full_src = wp_get_attachment_image_src( $attachment_id, 'full' ); ?>
                            <li>
                                <div class="wrap-img">
                                    <img src="<?= $full_src[0] ?>" alt="<?= $product->get_title() ?>">
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        </div>
        <div class="product-info-box">
            <?php
            /**
             * Hook: woocommerce_single_product_summary.
             *
             * @hooked woocommerce_template_single_title - 5
             * @hooked woocommerce_template_single_rating - 10
             * @hooked woocommerce_template_single_price - 10
             * @hooked woocommerce_template_single_excerpt - 20
             * @hooked woocommerce_template_single_add_to_cart - 30
             * @hooked woocommerce_template_single_meta - 40
             * @hooked woocommerce_template_single_sharing - 50
             * @hooked WC_Structured_Data::generate_product_data() - 60
             */
            do_action( 'woocommerce_single_product_summary' );
            ?>

            <div class="goods-box" data-seo="<?= stringAttribute( $seo ) ?>">
                <?php do_action( 'bogika_loop_wishlist' ); ?>
                <span></span>
            </div>
        </div>
        <script>
            jQuery(document).ready(function($) {
                // При клике на видео в слайдере навигации
                $('.slider-nav .product-view').on('click', function(e) {
                    e.preventDefault(); // Предотвращаем переход по ссылке

                    // Получаем индекс слайда в основном слайдере
                    var slideIndex = $(this).data('slide-index');

                    // Переключаем основной слайдер на указанный слайд
                    $('.slider-for').slick('slickGoTo', slideIndex);
                });
            });
        </script>
        <style>
            .slider-for {
                visibility: hidden; /* Початково приховуємо слайдер */
                height: 472px;
            }
        </style>
    </div>
    <?php if ( get_field( 'product_technologies' ) ) : ?>
        <ul class="product-technology-list flexbox">
            <?php foreach ( get_field( 'product_technologies' ) as $technology ) { ?>
                <li>
                    <div class="wrap-img">
                        <img src="<?= $technology['image']['url'] ?>" alt="<?= $technology['image']['url'] ?>">
                    </div><?= $technology['title'] ?>
                </li>
            <?php } ?>
        </ul>
    <?php endif; ?>
</div>

<?php
/**
 * Hook: woocommerce_after_single_product_summary.
 *
 * @hooked woocommerce_output_product_data_tabs - 10
 * @hooked woocommerce_upsell_display - 15
 * @hooked woocommerce_output_related_products - 20
 */
do_action( 'woocommerce_after_single_product_summary' );
?>

<?php do_action( 'woocommerce_after_single_product' ); ?>
