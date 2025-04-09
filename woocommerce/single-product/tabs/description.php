<?php
/**
 * Description tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

global $post;

$heading = apply_filters( 'woocommerce_product_description_heading', __( 'Description', 'woocommerce' ) );

?>

<?php
$content = wpautop(get_the_content());
echo $content;
?>
<?php if(get_field('hidden_text')) { ?>
	<div class="hide-text">
		<div class="expanded">
			<?=get_field('hidden_text');?>
		</div>
		<div class="expanded-opener"><?php esc_html_e('Expand', 'bogika'); ?></div>
	</div>
<?php	} ?>
