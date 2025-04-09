<?php
/**
 * Sharing template.
 *
 * Share wish listed items on social networks.
 *
 * @author  WPFactory
 * @version 1.8.5
 * @since   1.0.0
 */
?>

<?php
// Email params
$default_subject         = isset( $params['email']['default_subject'] ) ? $params['email']['default_subject'] : '';
$display_subject         = isset( $params['email']['subject'] ) ? $params['email']['subject'] : false;
$email_active            = isset( $params['email']['active'] ) ? $params['email']['active'] : false;
$email_values            = isset( $params['email']['emails'] ) ? $params['email']['emails'] : '';
$email_message           = isset( $params['email']['message'] ) ? $params['email']['message'] : '';
$email_to_admin          = isset( $params['email']['send_to_admin'] ) ? $params['email']['send_to_admin'] : true;
$need_admin_opt          = isset( $params['email']['need_admin_opt'] ) ? $params['email']['need_admin_opt'] : false;
$from_name               = isset( $params['email']['fromname'] ) ? $params['email']['fromname'] : '';
$from_email              = isset( $params['email']['fromemail'] ) ? $params['email']['fromemail'] : '';
$share_txt               = isset( $params['share_txt'] ) ? $params['share_txt'] : '';
$share_email_friends_txt = isset( $params['email']['share_email_friends_txt'] ) ? $params['email']['share_email_friends_txt'] : '';
$share_email_admin_txt   = isset( $params['email']['share_email_admin_txt'] ) ? $params['email']['share_email_admin_txt'] : '';
?>

<?php
$text = __('My wish list on', 'bogika').' '.$_SERVER['HTTP_HOST'];
$viber_share_url = 'viber://forward?text=' . urlencode($params['copy']['url']);
$tg_share_url = 'https://t.me/share/url?url='.rawurlencode($params['copy']['url']).'&text='.rawurlencode($text);
?>
<!-- Modal -->
<div id="letter-modal" class="modal">
	<div class="letter-block-modal">
		<h4><?php esc_html_e( 'Share', 'bogika' ); ?></h4>
		<ul class="social-networks flexbox">
			<li><a class="icon-viber" href="<?= $viber_share_url ?>" target="_blank">viber</a></li>
			<li><a class="icon-telegram" href="<?= $tg_share_url ?>" target="_blank">telegram</a></li>
		</ul>
		<?php if ( $params['copy']['active'] ): ?>
			<div class="download-file flexbox">
				<input class="download-file-box" type="text" value="<?= $params['copy']['url'] ?>" id="download-file">
				<a href="#" data-copy="<?= $params['copy']['url'] ?>">
					<img src="<?php bloginfo( 'template_url' ); ?>/assets/img/icon-file.svg" alt="image description">
				</a>
				<!-- <button onclick="initDownloadFile()"><img src="<?php bloginfo( 'template_url' ); ?>/assets/img/icon-file.svg" alt="image description"></button> -->
			</div>
		<?php endif; ?>
	</div>
</div>
