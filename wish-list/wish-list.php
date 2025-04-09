<?php
/**
 * Wish list view template
 * Lists wishlist items
 *
 * @author  WPFactory
 * @version 1.5.6
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly
?>

<?php
$the_query            = $params['the_query'];
$can_remove_items     = $params['can_remove_items'];
$show_stock           = $params['show_stock'];
$show_price           = $params['show_price'];
$show_add_to_cart_btn = $params['show_add_to_cart_btn'];
$is_email             = isset( $params['is_email'] ) ? $params['is_email'] : false;
$show_product_thumb   = true;
$email_table_params   = '';

if ( $is_email ) {
    $show_add_to_cart_btn = false;
    $can_remove_items     = false;
    $show_product_thumb   = false;
    $email_table_params   = 'cellspacing="0" cellpadding="6" style="width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;" border="1"';
}
?>

<style type="text/css" scoped>
    .added_to_cart.wc-forward {
        display: none;
    }
</style>

<?php if ( ! $is_email ) : ?>
    <div class="alg-wc-wl-empty-wishlist"
         style="<?php echo ( $the_query == null || ! $the_query->have_posts() ) ? 'display:block' : ''; ?>">
        <?php _e( 'The Wish list is empty', 'wish-list-for-woocommerce' ); ?>
    </div>
<?php endif; ?>

<?php if ( $the_query != null && $the_query->have_posts() ) : ?>

    <?php do_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_BEFORE, $the_query, $products_attributes, $params ); ?>
    <div class="heading flexbox">
        <h2><?php esc_html_e( 'Wish list', 'bogika' ); ?></h2>
        <?php if(!isset($_GET['alg_wc_wl_user'])) : ?>
            <div class="info-right">
                <a href="#letter-modal" class="btn default fancybox share-list-desc">ПОДІЛИТИСЬ ЛИСТОМ БАЖАНЬ</a>
            </div>
        <?php endif;?>
    </div>
    <div class="basket-block">
        <ul class="basket-services bogika-wishlist-table">
            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <?php $product = wc_get_product( get_the_ID() ); ?>
                <li>
                    <div class="basket-item-text">
                        <div class="wrap-img">
                            <?php
                            if($product->get_type()=='simple') echo $product->get_image();
                            elseif($product->get_type()=='variable') {
                                $variations = $product->get_available_variations();
                                echo wp_get_attachment_image($variations[0]['image_id'],'thumb');
                            }
                            ?>
                        </div>
                        <div class="text-box">
                            <a href="<?= $product->get_permalink() ?>" class="basket-item-title"><?php echo $product->get_title(); ?></a>
                            <p><?= $product->get_title(); ?></p>
                            <div class="basket-item-quantity">
                                <div class="basket-item-price"><?= $product->get_price_html(); ?></div>
                            </div>
                        </div>
                    </div>
                    <?php if ( $can_remove_items ) : ?>
                        <?php echo alg_wc_wl_locate_template( 'remove-button.php', $params['remove_btn_params'] ); ?>
                    <?php endif; ?>
                    <?php echo do_shortcode( '[add_to_cart show_price="false" style="" id="' . get_the_ID() . '"]' ); ?>
                </li>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </ul>
        <?php if(!isset($_GET['alg_wc_wl_user'])) : ?>
            <div class="info-right">
                <a href="#letter-modal" class="btn default fancybox share-list-mob">ПОДІЛИТИСЬ ЛИСТОМ БАЖАНЬ</a>
            </div>
        <?php endif;?>
    </div>
    <?php do_action( Alg_WC_Wish_List_Actions::WISH_LIST_TABLE_AFTER, $the_query, $products_attributes, $params ); ?>
    <!-- Modal -->
    <div id="letter-modal" class="modal">
        <div class="letter-block-modal">
            <h4>ПОДІЛИТИСЬ</h4>
            <ul class="social-networks flexbox">
                <li><a class="icon-viber" href="#" target="_blank">viber</a></li>
                <li><a class="icon-telegram" href="#" target="_blank">telegram</a></li>
            </ul>
            <div class="download-file flexbox">
                <a href="https://bogika.ninesquares.studio/wish-list/" class="download-file-box">https://bogika.ninesquares.studio/wish-list</a>
                <a href="<?php bloginfo('template_url'); ?>/assets/img/icon-file.svg"><img src="<?php bloginfo('template_url'); ?>/assets/img/icon-file.svg" alt="image description"></a>
            </div>
        </div>
    </div>
<?php endif; ?>
