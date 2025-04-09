<?php if ( get_field( 'shop_modal_on', 'option' ) ) : ?>
    <div class="login-call login-call-shop">
        <div class="login-call__content">
            <button class="login-call__close btn-close-modal"></button>
			<?= str_replace( '<p>', '<p class="login-call__text">', get_field( 'shop_modal_text', 'option' ) ); ?>
			<?php if ( get_field( 'shop_modal_btn_text', 'option' ) ) : ?>
                <a href="<?php the_field( 'shop_modal_btn_link', 'option' ) ?>"
                   class="login-call__link
               <?php if ( ! get_field( 'shop_modal_btn_link', 'option' ) ) {
					   echo 'btn-close-modal';
				   } ?>
               btn"><?php the_field( 'shop_modal_btn_text', 'option' ) ?></a>
			<?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if ( get_field( 'checkout_modal_on', 'option' ) ) : ?>
    <div class="login-call login-call-checkout">
        <div class="login-call__content">
            <button class="login-call__close btn-close-modal"></button>
			<?= str_replace( '<p>', '<p class="login-call__text">', get_field( 'checkout_modal_text', 'option' ) ); ?>
			<?php if ( get_field( 'checkout_modal_btn_text_kopiyuvaty', 'option' ) ) : ?>
                <a href="<?php the_field( 'checkout_modal_btn_link', 'option' ) ?>"
                   class="login-call__link
               <?php if ( ! get_field( 'checkout_modal_btn_link', 'option' ) ) {
					   echo 'btn-close-modal';
				   } ?>
               btn"><?php the_field( 'checkout_modal_btn_text_kopiyuvaty', 'option' ) ?></a>
			<?php endif; ?>
        </div>
    </div>
<?php endif; ?>
