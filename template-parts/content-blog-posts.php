<?php
$wrapper_id        = 'blog-list'; // id обгортки для постів
$button_wrapper_id = 'true_loadmore_blog'; // id обгортки для кнопки завантаження
$paged             = (int) (isset( $_POST['current_page'] ) ? $_POST['current_page'] : 0); // Поточна сторінка
?>
<?php
if ( $paged+1 === 1 ) : ?>
    <h1 class="h2 blog_h2">Блог</h1>
<?php endif; ?>
<?php
if ( $paged == 0 ) {
	echo '<div id="' . $wrapper_id . '" class="' . $wrapper_id . '">';
} // Перший виклик обертаєм в обгортку
// аргументи виборки
$post_args = [
	'post_type'      => 'post',
	'posts_per_page' => $args['count'],
	'order'          => 'DESC',
	'orderby'        => 'id',
	'paged'          => $paged + 1
];
$query     = new WP_Query( $post_args );
$max_pages = $query->max_num_pages;
// Якщо остання сторінка, видаляємо кнопку дозавантаження
if ( $paged + 1 == $max_pages ) {
	echo "<script> $('#" . $button_wrapper_id . "').remove(); </script>";
}
if ( $query->have_posts() ) {
	while ( $query->have_posts() ) { // Вивід постів
		$query->the_post();
		?>
        <article class="blog-post">
            <div class="text">
                <div class="heading">
					<?php $term = ( wp_get_post_terms( get_the_id(), 'category' ) )[0]; ?>
                    <div class="mark"><?= $term->name ?></div>
                    <time datetime="<?= get_the_date( 'Y-d-m' ) ?>"><?= get_the_date( 'F Y' ) ?></time>
                </div>
                <h2 class="title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt(); ?>
            </div>
            <a href="<?php the_permalink(); ?>" class="wrap-img">
                <img src="<?= get_the_post_thumbnail_url( get_the_id(), 'full' ) ?>" alt="<?= get_the_title() ?>">
            </a>
        </article>
	<?php }
}
wp_reset_query();
if ( $paged == 0 ) {
	echo '</div>'; ?>
<?php }
// Якщо перший виклик темплейта та кількість сторінок більша 1
if ( $paged == 0 && $max_pages > 1 ) { ?>
    <div id="<?= $button_wrapper_id ?>" class="button">
        <a class="btn default">БІЛЬШЕ СТАТЕЙ</a>
    </div>
    <!-- Скрипт інізіалізації -->
    <script>
        var ajaxurl = '<?=site_url()?>/wp-admin/admin-ajax.php';
        var query = '<?=serialize( $query->query_vars )?>';
        var current_page = '<?=isset( $_POST['paged'] ) ? $_POST['paged'] : 0?>';
        var max_pages = '<?=$max_pages?>';
        var main_tag = jQuery('#<?=$wrapper_id?>');
        var button = jQuery('#<?=$button_wrapper_id?>');
    </script>
<?php }
