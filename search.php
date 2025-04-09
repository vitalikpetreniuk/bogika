<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package bogika
 */

get_header();
?>

<?php if ( have_posts() ) : ?>

    <h1 class="page-title">
		<?php
		/* translators: %s: search query. */
		printf( __( 'Search Results for: %s', 'bogika' ), '<span>' . get_search_query() . '</span>' );
		?>
    </h1>

    <script>
        dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        dataLayer.push({
            event: "search",
            ecommerce: {
                search_term: "<?= sanitize_text_field( $_GET['s'] ) ?>" // пошуковий запит
            }
        });
        // Виконуємо код після повного завантаження сторінки
        window.addEventListener('load', function () {
            // Отримуємо всі елементи з класом 'outofstock'
            const outOfStockElements = document.querySelectorAll('.outofstock');

            // Перевіряємо, чи є результати пошуку
            if (outOfStockElements.length > 0) {
                // Проходимося по кожному елементу з класом 'outofstock'
                outOfStockElements.forEach(element => {
                    // Додаємо CSS властивість 'order: 9' до батьківського елемента (li)
                    const parentLi = element.closest('li');
                    if (parentLi) {
                        parentLi.style.order = '9';
                    }
                });
            }
        });


    </script>

	<?php
	$seo = [];
	woocommerce_product_loop_start();
	/* Start the Loop */
	while ( have_posts() ) :
		the_post();
		global $product;

		$args = [
			'quantity' => 1,
            'product'=>$product
		];

		if ( is_shop() ) {
			$args['item_list_name'] = 'Shop';
		} elseif ( is_product_category() ) {
			$args['item_list_name'] = 'Search Results';
		}

		$seo[] = generateSeo( $args );

		wc_get_template_part( 'content', 'product' );

	endwhile;
	woocommerce_product_loop_end();
	the_posts_pagination( array(
		'next_text' => '→',
		'prev_text' => '←',
	) );

	$products = array_chunk( $seo, 6 );
	?>
    <script>
		<?php foreach ($products as $chunk) : ?>
        dataLayer.push({ecommerce: null});  // Clear the previous ecommerce object.
        dataLayer.push({
            event: 'view_item_list',
            ecommerce: {
                items: <?= jsonSeoData( $chunk ) ?>
            }
        });
		<?php endforeach; ?>
    </script><?php
else :?>
    <h1><?php esc_html_e( 'No posts found', 'bogika' ); ?></h1>
<?php

endif;
?>

<?php
get_footer();
