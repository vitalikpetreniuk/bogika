<?php
add_filter( 'comment_form_default_fields', 'bogika_comment_defaults' );

add_filter( 'woocommerce_product_review_comment_form_args', 'bogika_comment_defaults' );
function bogika_comment_defaults( $args ) {
//	$fields = [];
//	foreach ( $args as $key => $field ) {
//		$fields[ $key ] = str_replace( '<p class="', '<p class="wrap-input ', $field );
//	}

	$commenter     = wp_get_current_commenter();

	$args['fields'] = array(
		'author' => '<div class="comment-form-author wrap-input"><input id="author" placeholder="'.__( 'Name' ).'*" name="author" type="text" value="'.esc_attr( $commenter['comment_author'] ).'" size="30" maxlength="245" required /></div>',
		'email' => '<div class="comment-form-email wrap-input"><input id="email" aria-describedby="email-notes" placeholder="'.__( 'Email' ).'*" name="email" type="email" value="'.esc_attr( $commenter['comment_author'] ).'" size="30" maxlength="100" required /></div>',
	);

	$args['submit_field']         = '<div class="form-submit">%1$s %2$s</div>';
	$args['comment_field']        = sprintf(
		'<div class="comment-form-comment wrap-input"><textarea id="comment" name="comment" placeholder="'._x( 'Comment', 'noun' ).'*" cols="45" rows="1" maxlength="65525" required></textarea></div>'
	);
	$args['comment_field']        .= '</div>';
	$args['comment_notes_before'] = '';

	return $args;
}

add_filter( 'comment_form_submit_button', 'bogika_comment_form_submit', 10, 2 );
function bogika_comment_form_submit( $submit_field, $args ) {
	return '<div class="wrap-input button"><input name="submit" type="submit" id="submit" class="submit btn" value="' . __( 'Publish review', 'bogika' ) . '"></div>';
}

add_action( 'comment_form_top', function () {
	if ( wc_review_ratings_enabled() ) {
		echo '<div class="comment-form-rating rating-stars flexbox">';
		echo '<label for="rating" class="rating-text">' . esc_html__( 'Your rating', 'woocommerce' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label>';
		if ( ! is_product() ) {
			echo '<p class="stars">
				<span>
					<a class="star-1" href="#">1</a>
					<a class="star-2" href="#">2</a>
					<a class="star-3" href="#">3</a>
					<a class="star-4" href="#">4</a>
					<a class="star-5" href="#">5</a>
				</span>
			</p>';
		}
		echo '<select name="rating" id="rating" required style="display: none">
						<option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
						<option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
						<option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
						<option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
						<option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
						<option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
					</select>';
		echo '</div>';
	}
	echo '<div class="container-form flexbox">';
} );

add_filter( 'woocommerce_product_review_comment_form_args', function ( $args ) {
	$args['title_reply'] = '';

	return $args;
} );
add_filter( 'comment_form_fields', function ( $fields ) {
	$comment = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment;

	return $fields;
} );

function bootstrap_comment_callback( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	/* @var WP_Comment $comment */
	$author = $comment->comment_author;
	?>
<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>

	<div class="item">
		<div class="author"><?= $author ?></div>
		<time
			datetime="<?= date( "Y-m-d", strtotime( $comment->comment_date ) ) ?>"><?= date( "d.m.Y", strtotime( $comment->comment_date ) ) ?></time>
	</div>
	<?php $rating = get_comment_meta( $comment->comment_ID, 'rating' );
	?>
	<div class="item">
		<?php if ( $depth < 2 && ! empty( $rating ) ) { ?>
			<div class="stars stars2">
				<?php for ( $i = 1; $i <= $rating[0]; $i ++ ) { ?>
					<span class="star-full">star</span>
				<?php } ?>
				<?php if ( $rating[0] < 5 ) { ?>
					<?php for ( $i = $rating[0] + 1; $i <= 5; $i ++ ) { ?>
						<span class="star-empty">star</span>
					<?php } ?>
				<?php } ?>
			</div>
		<?php } ?>
		<div>
			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation label label-info"><?php _e( 'Your comment is awaiting moderation.' ); ?></p>
			<?php endif; ?>
			<?php comment_text(); ?>
		</div>

		<div class="list-inline">
			<?php edit_comment_link( __( 'Edit' ), '<div class="edit-link">', '</div>' ); ?>

			<?php
			if ( current_user_can( 'administrator' ) ) {
				echo get_comment_reply_link( array_merge( $args, array(
					'add_below' => 'div-comment',
					'depth'     => $depth,
					'max_depth' => $args['max_depth'],
					'before'    => '<div class="reply-link">',
					'after'     => '</div>'
				) ), $comment );
			}
			?>
		</div>
	</div>

	<?php
}

