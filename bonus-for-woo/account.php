<?php
/**
 * Шаблон страницы бонусов в аккаунте клиента
 *
 * @version      4.6.0
 */

defined( 'ABSPATH' ) || exit;
$userid = get_current_user_id();
/*Реферальный код пользователя*/
$get_referral = get_user_meta( $userid, 'bfw_points_referral', true );
?>

<div class="bfw_user_acount_content">
    <div class="heading flexbox">
        <h2><?php esc_html_e( 'Bonuses', 'bogika' ); ?></h2>
		<?php if ( $get_referral ) : ?>
            <div class="info-right" style="display: none;">
                <a data-copy="<?= site_url() . '?bfwkey=' . $get_referral ?>"
                   href="<?= site_url() . '?bfwkey=' . $get_referral ?>"
                   class="bonus-number"><?= $get_referral ?></a>
            </div>
		<?php endif; ?>
    </div>
    <div class="bonus-block">
        <div class="bonus-text flexbox">
            <div class="bonus-box">
                <strong class="title"><?php esc_html_e( 'Bonuses on your account', 'bogika' ); ?>:</strong>
				<?php
				$computy_point = ( new BfwPoints )->getPoints( $userid );
				$computy_point = $computy_point ?? 0.00;
				?>
                <div class="sum"><?= $computy_point ?> грн</div>
            </div>
			<div style="margin-left:40px;" class="bonuses-info">
                <div class="bonus_computy_account"><span class="title_bca"><?php esc_html_e('Status','bogika'); ?>: </span> <span class="value_bca"><?php echo do_shortcode('[bfw_status]');?></span></div>
                <div class="bonus_computy_account"><span class="title_bca"><?php esc_html_e('Number of points','bogika'); ?>: </span> <span class="value_bca"><?php echo do_shortcode('[bfw_points]');?></span></div>
                <div class="bonus_computy_account"><span class="value_bca"><?php echo do_shortcode('[bfw_ref]');?></span></div>
                <?php  if(do_shortcode('[bfw_status]') == 'Член клубу')
                echo do_shortcode('[bfw_account_referral]'); ?>
                <?php echo do_shortcode('[link_on_rulles]');?>
            </div>
            <div class="text" style="display: none;">
                <h3><?php esc_html_e( 'Share your referral link and get bonuses', 'bogika' ); ?></h3>
                <p><?php the_field( 'account_bonuses_offer_text1', 'option' ) ?></p>
            </div>
        </div>
		<?php
		$val   = get_option( 'bonus_option_name' );
		$total = ( new BfwPoints )->getSumUserOrders( $userid ); /*сумма всех покупок округленная до целого*/
		//узнать сколько рублей осталось до следующего статуса
		$nextrole = ( new BfwRoles )->getNextRole( $userid );
		?>
        <div class="bonus-rating flexbox active">
            <div class="text" style="<?php if ( ( new BfwRoles() )->getRole( $userid )['slug'] === 'chlen-clubu' )
				echo 'width: 100%' ?>">
                <h2><?php esc_html_e( 'Club Bogika', 'bogika' ); ?></h2>
                <p><?= strtr( get_field( 'klub_bogika', 'option' ), array(
						'{sum}' => bogika_sum_to_become_a_member()
					) );
					?></p>
            </div>
			<?php if ( ! ( ( new BfwRoles() )->getRole( $userid )['slug'] === 'chlen-clubu' ) ) : ?>
                <div class="rating-box">
                    <div class="bonus-active w-40">
                        <div class="rating" width="40"><span
                                    class="quantity"><?= $total ?: 0 ?></span>/<?= bogika_sum_to_become_a_member() ?>
                        </div>
                    </div>
					<?php if ( isset( $nextrole['sum'] ) ) : ?>
                        <p><?= sprintf( __( 'Not enough <b>%d</b> points to become a member of the club', 'bogika' ), bogika_sum_to_become_a_member() - $total ) ?></p>
					<?php endif; ?>
                </div>
			<?php endif; ?>
        </div>
        <div class="services-benefits">
            <h2><?php esc_html_e( 'Club Bogika gives such advantages', 'bogika' ); ?>:</h2>
			<?= str_replace( '<ul>', '<ul class="mark-list">', get_field( 'account_advantage_club_bogika_text', 'option' ) ) ?>
        </div>
        <div class="services-benefits">
            <h2><?php echo get_field( 'zagolovok_drugoyi_propozycziyi', 'option' ) ?>:</h2>
            <?= str_replace( '<ul>', '<ul class="mark-list">', get_field( 'account_bonuses_offer_text2', 'option' ) ) ?>
        </div>
        <div class="services-benefits">
            <h2><?php echo get_field( 'zagolovok_pershoyi_propozycziyi', 'option' ) ?>:</h2>
            <?= str_replace( '<ul>', '<ul class="mark-list">', get_field( 'account_bonuses_offer_text1', 'option' ) ) ?>
        </div>

    </div>
</div>
