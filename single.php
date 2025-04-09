<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package bogika
 */

get_header(); ?>
    <section class="blog-post-section">
        <div class="wrap-img">
            <img src="<?=get_the_post_thumbnail_url(get_the_id(),'full')?>" alt="<?=get_the_title()?>">
            <img class="mobile" src="<?=get_the_post_thumbnail_url(get_the_id(),'full')?>" alt="<?=get_the_title()?>">
        </div>
        <div class="holder">
            <div class="text">
                <div class="heading">
                    <?php $term = (wp_get_post_terms(get_the_id(),'category'))[0]; ?>
                    <div class="mark"><?php echo $term->name; ?></div>
                    <time datetime="<?=get_the_date('Y-d-m')?>"><?=get_the_date('F Y')?></time>
					<div style="padding:7px"><?php $author = get_the_author(); echo " Автор: ". $author;?> </div>
                </div>
                <h1 class="title"><?php the_title(); ?></h1>
                <p><?php the_excerpt(); ?></p>
            </div>
        </div>
    </section>
    <div id="content" class="content-page">
        <div class="holder">
            <div class="blog-post-content">
                <?php the_content(); ?>
            </div>
            <?php get_template_part('template-parts/content','post-blog-posts',['without'=>get_the_id()]); ?>
        </div>
    </div>

<?php get_footer();
