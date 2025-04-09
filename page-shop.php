<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package bogika
 */

get_header();
		if ( have_posts() ) :
			/* Start the Loop */
			while ( have_posts() ) :
				the_post(); ?>
                <div class="page-heading">
                    <h1><?php the_title()?></h1>
                    <p><??></p>
                </div>
                <?php the_content(); ?>
			<?php endwhile;
		else :

            echo "Нічого не знайдено";
		endif;
get_footer();
