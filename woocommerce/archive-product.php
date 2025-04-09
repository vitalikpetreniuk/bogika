<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
//do_action( 'woocommerce_before_main_content' );
$cate       = get_queried_object();
$cateID     = $cate->term_id;
$parentcats = get_ancestors( $cateID, 'product_cat' );
if ( function_exists( 'get_product_category_min_max' ) ) :
$min_max    = get_product_category_min_max();
$min        = $min_max['min'];
$max        = $min_max['max']; ?>
<span itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer">
        <meta content="<?= $cate->count ?>" itemprop="offerCount">
        <meta content="<?= $min ?>" itemprop="lowPrice">
        <meta content="<?= $max ?>" itemprop="highPrice">
        <meta content="UAH" itemprop="priceCurrency">
    </span>
<?php endif; ?>
<div class="page-heading">
    <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
        <h1><?php woocommerce_page_title(); ?>
            <?php $page = get_query_var('paged'); ?>
            <?php if(!empty($page)&&(int)$page!=1) echo " - ".__('Page','woocommerce').' '.$page; ?>
        </h1>
    <?php endif;
    if(!is_premmerce_filter_page()) do_action( 'woocommerce_archive_description' ); ?>
</div>
<?php
/**
 * Hook: woocommerce_archive_description.
 *
 * @hooked woocommerce_taxonomy_archive_description - 10
 * @hooked woocommerce_product_archive_description - 10
 */
?>
<div class="catalog-section flexbox">
    <div class="catalog-section__filter widget_premmerce_filter_filter_widget">
		<?php

		do_action( 'woocommerce_sidebar' ); ?>
    </div>
	<div class="catalog-section__content">
		<?php
		if ( woocommerce_product_loop() ) {
			do_action( 'woocommerce_before_shop_loop' ); ?>
			<!--		<h2>Найкраще для вас</h2>-->
			<?php
	//	echo do_shortcode( '[featured_products]' ); ?>
			<?php
			woocommerce_product_loop_start();
			$seo             = [];
			$structured_data = [];
			if ( wc_get_loop_prop( 'total' ) ) {
				while ( have_posts() ) {
					the_post();
					global $product;
					wc()->structured_data->generate_product_data( $product );
					//$structured_data[] = WC()->structured_data->get_data()[1];
					$structured_data[] = get_the_permalink(get_the_id());
					$args = [
						'quantity' => 1,
						'product'=>$product,
					];

					if (is_shop()) {
						$args['item_list_name'] = 'Shop';
					}elseif (is_product_category()) {
						$args['item_list_name'] = 'Category - '.$cate->name;
					}

					if ( function_exists( 'generateSeo' ) ) {
					$seo[] = generateSeo($args);
					}

					do_action( 'woocommerce_shop_loop' );

					wc_get_template_part( 'content', 'product' );
				}
			}

			woocommerce_product_loop_end();
	//		global $wp_query;
	//		$products = wp_list_pluck( $wp_query->posts, 'ID' );
			$products = array_chunk( $seo, 6 );
			?>
			<script>
				<?php foreach ($products as $chunk) : ?>
				dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
				dataLayer.push({
					'event': 'view_item_list',
					'items': <?= jsonSeoData($chunk) ?>
				});
				<?php endforeach; ?>
			</script>
			<?php

			/**
			 * Hook: woocommerce_after_shop_loop.
			 *
			 * @hooked woocommerce_pagination - 10
			 */
			add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
			do_action( 'woocommerce_after_shop_loop' );
		} else {
			/**
			 * Hook: woocommerce_no_products_found.
			 *
			 * @hooked wc_no_products_found - 10
			 */
			do_action( 'woocommerce_no_products_found' );
		} ?>
	</div>
</div>
<div class="seo-text">
    <?php
        if(!is_premmerce_filter_page()) echo get_field('seo_text',$cate);
        else echo get_seo_description();
    ?>
</div>
<?php $faq = get_term_meta_repeater($cateID, 'list_items'); ?>
<!-- FAQ-Блок -->
<?php if(!get_field('hide_faq',$cate)) { ?>
    <?php if($faq) { ?>
        <div class="faq-block">
            <h3 class="title"><?php esc_html_e('Часті питання про','bogika'); ?> <?php echo $cate->name; ?></h3>
            <?php foreach($faq as $faq_item) { ?>
                <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <div class="faq-question" itemprop="name">
                        <?php echo $faq_item['question']?>
                    </div>
                    <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text">
                            <?php echo $faq_item['answer']; ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "FAQPage",
                "mainEntity": [
            <?php $faq_arr = [];
            foreach ($faq as $faq_item)
                $faq_arr[] = '{
            "@type": "Question",
            "name": "'.$faq_item['question'].'",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "'.$faq_item['answer'].'"
            }
        }
       '; echo implode(',', $faq_arr); ?>

            ]}
        </script>

    <?php } ?>
<?php } ?>




<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
//do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */


get_footer( 'shop' );


?>



