<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<form class="woocommerce-ordering" method="get">
	<select name="orderby" class="orderby" aria-label="<?php esc_attr_e( 'Shop order', 'woocommerce' ); ?>">
		<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
			<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
		<?php endforeach; ?>
	</select>
	<input type="hidden" name="paged" value="1" />
	<?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
</form>

<div class="goods-select-block">
	<div class="goods-select-sort-button flexbox">
		<a id="order-price"
		   class="btn <?= ( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'price' ) ? 'active' : '' ?>">За
			підвищенням ціни</a>
		<a id="order-price-desc"
		   class="btn <?php if(isset( $_GET['orderby'] ) && $_GET['orderby'] == 'price-desc' ) echo 'active' ?>">За
			зниженням ціни</a>
	</div>
	<script>
        jQuery('body').on('click', '#order-price:not(.active)', function () {
            jQuery("body select.orderby").val("price").change();
        });
        jQuery('body').on('click', '#order-price-desc:not(.active)', function () {
            jQuery("body select.orderby").val("price-desc").change();
        });
        jQuery('body').on('click', '#order-price-mob:not(.active)', function () {
            jQuery("body select.orderby").val("price").change();
        });
        jQuery('body').on('click', '#order-price-desc-mob:not(.active)', function () {
            jQuery("body select.orderby").val("price-desc").change();
        });


			$('body').on('click', '#order-price-mob:not(.active)', function () {
				$("body select.orderby").val("price").change();
			});

			$('body').on('click', '#order-price-desc-mob:not(.active)', function () {
				$("body select.orderby").val("price-desc").change();
			});

	</script>
	<div class="custom-select-block-mobile">
		<!--<div class="goods-num">12 шт</div>-->
		<div class="custom-select multiple">
			<?php dynamic_sidebar( 'filter' ); ?>
		</div>
       
		<!-- <div class="goods-select-sort-button mobile">
			<div class="goods-select__mobile">
				<select id="sortby-catalog">
					<option value=""></option>
					<option value="price-desc" <?php if (isset($_GET['orderby']) && $_GET['orderby'] === 'price-desc') echo 'selected'?> data-badge=""><?php esc_html_e('Price (high-low)', 'bogika'); ?></option>
					<option value="price" <?php if (isset($_GET['orderby']) && $_GET['orderby'] === 'price') echo 'selected'?> data-badge=""><?php esc_html_e('Price (low-high)', 'bogika'); ?></option>
				</select>
			</div>
		</div> -->


			<div class="goods-select__mobile">
				<button id="order-price-mob" class="btn-filtr <?= ( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'price' ) ? 'active' : '' ?>">
					<svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M15 10.5A3.502 3.502 0 0 0 18.355 8H21a1 1 0 1 0 0-2h-2.645a3.502 3.502 0 0 0-6.71 0H3a1 1 0 0 0 0 2h8.645A3.502 3.502 0 0 0 15 10.5zM3 16a1 1 0 1 0 0 2h2.145a3.502 3.502 0 0 0 6.71 0H21a1 1 0 1 0 0-2h-9.145a3.502 3.502 0 0 0-6.71 0H3z" fill="#643633"/>
					</svg>
				</button>
				<button id="order-price-desc-mob" class="btn-filtr <?= ( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'price-desc' ) ? 'active' : '' ?>">
					<svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M18 3.99997C18 3.44769 17.5523 2.99998 17 2.99998C16.4477 2.99999 16 3.44771 16 4L16.0002 17.586L13.7071 15.2929C13.3166 14.9024 12.6834 14.9024 12.2929 15.2929C11.9024 15.6834 11.9024 16.3166 12.2929 16.7071L16.2929 20.7071C16.4163 20.8305 16.5639 20.9149 16.7204 20.9603C16.7777 20.977 16.837 20.9886 16.898 20.9948C16.9316 20.9982 16.9657 21 17.0002 21C17.018 21 17.0357 20.9995 17.0533 20.9986C17.3143 20.985 17.5488 20.8712 17.7192 20.695L21.7071 16.7071C22.0976 16.3166 22.0976 15.6834 21.7071 15.2929C21.3166 14.9024 20.6834 14.9024 20.2929 15.2929L18.0002 17.5856L18 3.99997Z" fill="#643633"/>
						<path d="M8 20L8 6.41421L10.2929 8.7071C10.6834 9.09763 11.3166 9.09763 11.7071 8.7071C12.0976 8.31658 12.0976 7.68341 11.7071 7.29289L7.70711 3.29289C7.31658 2.90237 6.68342 2.90237 6.29289 3.29289L2.29289 7.29289C1.90237 7.68341 1.90237 8.31658 2.29289 8.7071C2.68342 9.09763 3.31658 9.09763 3.70711 8.7071L6 6.41421L6 20C6 20.5523 6.44772 21 7 21C7.55229 21 8 20.5523 8 20Z" fill="#643633"/>
					</svg>
				</button>
			</div>


	</div>
</div>
