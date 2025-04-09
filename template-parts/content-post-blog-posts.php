<section class="blog-section">
	<h2><?= get_field( 'relation_posts_title', 'option' ) ?></h2>
	<div class="blog-list">

		<?php
		$without = $args['without'];

		// необязательно, но в некоторых случаях без этого не обойтись
		global $post;

		// тут можно указать post_tag (подборка постов по схожим меткам) или даже массив array('category', 'post_tag') - подборка и по меткам и по категориям
		$related_tax = 'category';

		// получаем ID всех элементов (категорий, меток или таксономий), к которым принадлежит текущий пост
		$cats_tags_or_taxes = wp_get_object_terms( $post->ID, $related_tax, array( 'fields' => 'ids' ) );

		// массив параметров для WP_Query
		$args        = array(
			'posts_per_page' => 4, // сколько похожих постов нужно вывести,
			'no_found_rows'  => true,
			'tax_query'      => array(
				array(
					'taxonomy'         => $related_tax,
					'field'            => 'id',
					'include_children' => false,
					// нужно ли включать посты дочерних рубрик
					'terms'            => $cats_tags_or_taxes,
					'operator'         => 'IN'
					// если пост принадлежит хотя бы одной рубрике текущего поста, он будет отображаться в похожих записях, укажите значение AND и тогда похожие посты будут только те, которые принадлежат каждой рубрике текущего поста
				)
			)
		);
		$misha_query = new WP_Query( $args );
		// если посты, удовлетворяющие нашим условиям, найдены
		if ( $misha_query->have_posts() ) :
			$count = 0;
// запускаем цикл
			while ( $misha_query->have_posts() ) : $misha_query->the_post();
				if ( $count > 2 || get_the_id() == $without ) {
					continue;
				}
				$count ++;
				?>
				<article class="blog-post">
					<div class="text">
						<div class="heading">
							<?php $term = ( wp_get_post_terms( get_the_id(), 'category' ) )[0]; ?>
							<div class="mark"><?= $term->name ?></div>
							<time datetime="<?= get_the_date( 'Y-d-m' ) ?>"><?= get_the_date( 'F Y' ) ?></time>
						</div>
						<strong class="title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></strong>
						<?php the_excerpt(); ?>
					</div>
					<a href="<?php the_permalink(); ?>" class="wrap-img">
						<img src="<?= get_the_post_thumbnail_url( get_the_id(), 'full' ) ?>"
						     alt="<?= get_the_title() ?>">
					</a>
				</article>

			<?php
			endwhile;
		endif;

		// не забудьте про эту функцию, её отсутствие может повлиять на другие циклы на странице
		wp_reset_postdata(); ?>

	</div>
</section>

