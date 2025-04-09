<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package bogika
 */
get_header(); ?>
<?php if ( ! get_field( 'hide_title' ) ) { ?>
	<h1><?php the_title(); ?></h1>
<?php } ?>
<?php
the_content();
get_footer();
