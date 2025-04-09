<?php
function custom_remove_action() {
	remove_action( 'edit_user_profile', array( 'BfwAdmin', 'bfwoo_add_bonus_in_user_profile' ) );
	remove_action( 'show_user_profile', array( 'BfwAdmin', 'bfwoo_add_bonus_in_user_profile' ) );
	add_action( 'edit_user_profile', 'bfwoo_add_bonus_in_user_profile' );
	add_action( 'show_user_profile', 'bfwoo_add_bonus_in_user_profile' );

	/* Сохранение изменений в профиле клиента*/
	remove_action( 'personal_options_update', array( 'BfwAdmin', 'bfwoo_computy_input_points_add' ) );
	remove_action( 'edit_user_profile_update', array( 'BfwAdmin', 'bfwoo_computy_input_points_add' ) );
	add_action( 'edit_user_profile_update', 'bfwoo_computy_input_points_add' );
	add_action( 'edit_user_profile_update', 'bfwoo_computy_input_points_add' );
}

add_action( 'admin_init', 'custom_remove_action' );
require_once get_template_directory() . '/bonus-for-woo/classes/BfwBogikaHistory.php';

/**
 * Кастомна таблиця для відображення змін балів
 * Взято з плагіну Bonus For Woo
 *
 * @param WP_User $user
 *
 * @return void html
 */
function bfwoo_add_bonus_in_user_profile( $user ): void {

	?>
    <hr>
    <div class="user_profile_bfw">
        <h2><?php echo __( 'User bonus points', 'bonus-for-woo' ); ?></h2>

		<?php
		$roles = ( new BfwRoles )->getRole( $user->ID );
		echo '<h2>' . __( 'Status', 'bonus-for-woo' ) . ': ' . $roles['name'] . '</h2>';
		if ($roles['slug'] === 'client') {
?>
            <button class="button button-secondary changeclubstatus" data-user_id="<?= $user->ID ?>" type="button">Зробити членом клубу</button>
            <?php
		}else {?>
            <button class="button button-secondary changeclubstatus" data-user_id="<?= $user->ID ?>" type="button">Забрати членство у клубі</button>
            <?php
		}

		/*Обработчик удаления записи истории начисления баллов*/
		if ( isset( $_POST['bfw_delete_post_history_points'] ) ) {
			( new BfwBogikaHistory )->deleteHistoryId( sanitize_text_field( $_POST['bfw_delete_post_history_points'] ) );
			echo '<div id="message" class="notice notice-warning is-dismissible">
	<p>' . __( 'deleted', 'bonus-for-woo' ) . '.</p></div>';
		}
		/*Обработчик удаления записи истории начисления баллов*/

		/*Обработчик удаления всей истории начисления баллов*/
		if ( isset( $_GET['bfw_delete_all_post_history_points'] ) ) {
			$delete_history_points = sanitize_text_field( $_GET['bfw_delete_all_post_history_points'] );
			( new BfwBogikaHistory )->clearAllHistoryUser( $delete_history_points );


			echo '<div id="message" class="notice notice-warning is-dismissible">
	<p>' . __( 'Cleared', 'bonus-for-woo' ) . '.</p>
</div>';
		}
		/*Обработчик удаления всей истории начисления баллов*/


		if ( ( new BfwRoles )->is_pro() ) { ?>
            <p>
                <label for="dob"><b><?php esc_html_e( 'Date of birth', 'bonus-for-woo' ); ?></b> </label>
                <input type="date" class="woocommerce-Input woocommerce-Input--text input-text" name="dob" id="dob"
                       value="<?php echo esc_attr( $user->dob ); ?>"/>

				<?php if ( isset( $user->this_year ) and $user->this_year == date( 'Y' ) ) {
					echo __( 'The client received points this year', 'bonus-for-woo' );
				} else {
					echo __( 'The client did not receive points this year', 'bonus-for-woo' );
				} ?>
            </p>

            <i style="color: #005ac9"><?php echo __( 'You can change the number of bonus points.',
					'bonus-for-woo' ); ?>
            </i>
            <p><b><?php echo __( 'Total bonus points', 'bonus-for-woo' ); ?>:</b> <?php
				$balluser = ( new BfwPoints )->getPoints( $user->ID );
				echo $balluser;
				?></p>

            <p><label> <?php echo __( 'Сhange bonus points', 'bonus-for-woo' ); ?>
                    <input type="number" name="computy_input_points" value=""
                           class="regular-text"/></label></p>
            <p><label><textarea style="width: 100%;height: 100px;" name="prichinaizmeneniya"
                                placeholder="<?php echo __( 'The reason for the change in points. It will be displayed in the client\'s accrual history.',
				                    'bonus-for-woo' ); ?>"></textarea></label></p>
            <p><input type="submit" name="submit" id="submit1" class="button button-primary"
                      value="<?php echo __( 'Сhange', 'bonus-for-woo' ); ?>"></p>

		<?php } else { ?>
            <i style="color: #005ac9"><?php echo __( 'You cannot remove bonus points, but you can add them. Just enter the number of points you want to add.',
					'bonus-for-woo' ); ?>
            </i>
            <p><b><?php echo __( 'Total bonus points', 'bonus-for-woo' ); ?>:</b> <?php
				$balluser = ( new BfwPoints )->getPoints( $user->ID );
				echo esc_attr( $balluser );
				?></p>

            <p><label><?php echo __( 'Add bonus points', 'bonus-for-woo' ); ?>
                    <input type="number" name="computy_input_points" value="0" class="regular-text"/></label>
            <p><label><textarea style="width: 100%;height: 100px;" name="prichinaizmeneniya"
                                placeholder="<?php echo __( 'The reason for the change in points. It will be displayed in the client\'s accrual history.',
				                    'bonus-for-woo' ); ?>"></textarea></label></p>
            <p><input type="submit" name="submit" id="submit2" class="button button-primary"
                      value="<?php echo __( 'Add', 'bonus-for-woo' ); ?>"></p>
		<?php } ?>



		<?php
		/*история начислений баллов клиента*/
		( new BfwBogikaHistory )->getHistory( $user->ID );


		$val         = get_option( 'bonus_option_name' );
		$referalwork = isset( $val['referal-system'] ) ? intval( $val['referal-system'] ) : 0;

		/*если включена реферальная система*/
		if ( ( new BfwRoles )->is_pro() and $referalwork == 1 ) { ?>
            <h3><?php echo __( 'Referral system', 'bonus-for-woo' ); ?></h3>
			<?php
			$get_referral        = get_user_meta( $user->ID, 'bfw_points_referral', true );
			$get_referral_invite = get_user_meta( $user->ID, 'bfw_points_referral_invite', true );
			/*Сколько людей пригласил*/
			$argsa['meta_query'] = array(
				array(
					'key'     => 'bfw_points_referral_invite',
					'value'   => trim( $user->ID ),
					'compare' => '==',
				),
			);
			$refere_data         = get_users( $argsa );
			foreach ( $refere_data as $ref_data_one ) {
				$referral_one_user_name[] = $ref_data_one->user_nicename;
				$referral_one_id[]        = $ref_data_one->ID;
				// $referral_one_first_name[]->first_name;
			}


			echo __( 'Referral link',
					'bonus-for-woo' ) . ': <code>' . esc_url( site_url() . '?bfwkey=' . $get_referral ) . '</code><br>';
			if ( $get_referral_invite == 0 or $get_referral_invite == '' ) {
				echo '';
			} else {

				$user_info = get_userdata( $get_referral_invite );

				echo __( 'Invited by user',
						'bonus-for-woo' ) . ': <a href="/wp-admin/user-edit.php?user_id=' . $get_referral_invite . '" >' . $user_info->user_login . '(' . $user_info->first_name . ' ' . $user_info->last_name . ')</a><br>';
			}

			echo __( 'Invited', 'bonus-for-woo' ) . ' ' . count( $refere_data ) . ' ' . __( 'people', 'bonus-for-woo' );


			echo ': ';
			for ( $i = 0; $i <= count( $refere_data ) - 1; $i ++ ) {
				/*Выводим список приглашенных первого уровня*/
				echo ' <a href="/wp-admin/user-edit.php?user_id=' . $referral_one_id[ $i ] . '">' . $referral_one_user_name[ $i ] . '</a>, ';
			}


			if ( ! empty( $val['level-two-referral'] ) ) {/*Считаем второй уровень*/

				if ( ! empty( $val['level-two-referral'] ) ) {
					/*Считаем второй уровень*/
					$refere_data_two_two = 0;
					foreach ( $refere_data as $refere_data_two ) {
						$argsatwo['meta_query'] = array(
							array(
								'key'     => 'bfw_points_referral_invite',
								'value'   => trim( $refere_data_two->ID ),
								'compare' => '==',
							),
						);

						$refere_data_two_two += count( get_users( $argsatwo ) );
						$ref_data_two        = get_users( $argsatwo );
						foreach ( $ref_data_two as $ref_data_twos ) {
							$referral_two_user_name[] = $ref_data_twos->user_nicename;
							$referral_two_id[]        = $ref_data_twos->ID;
						}


						/*Считаем второй уровень*/
					}
				}
				echo '<br>' . __( 'Invited friends', 'bonus-for-woo' ) . ' ' . $refere_data_two_two . ' ' . __( 'people', 'bonus-for-woo' );
				echo ': ';/*Выводим список приглашенных второго уровня*/
				for ( $i = 0; $i <= $refere_data_two_two - 1; $i ++ ) {
					echo ' <a href="/wp-admin/user-edit.php?user_id=' . $referral_two_id[ $i ] . '">' . $referral_two_user_name[ $i ] . '</a>, ';
				}
			}
		} ?>


    </div>
    <hr>
	<?php
}

/**
 * Додавання записів про зміну балів з автором змін
 * Взято з плагіну Bonus For Woo
 *
 * @param int $user_id
 *
 * @return void html
 */
function bfwoo_computy_input_points_add( $user_id ): void {

	if ( ( new BfwRoles )->is_pro() ) {
		/*Сохранения дня рождения*/
		if ( isset( $_POST['dob'] ) ) {
			update_user_meta( $user_id, 'dob', sanitize_text_field( $_POST['dob'] ) );
		}

		if ( isset( $_POST['computy_input_points'] ) ) {
			/*При редактировании баллов клиента*/
			$addball  = (int) sanitize_text_field( $_POST['computy_input_points'] );
			$prichina = sanitize_text_field( $_POST['prichinaizmeneniya'] );
			if ( $prichina == '' ) {
				$prichina = __( 'Not specified.', 'bonus-for-woo' );
			}
			$oldpoint = ( new BfwPoints )->getPoints( $user_id );
            $newpoints = $oldpoint + $addball;

			if ( $addball > 0 ) {
				/*Записываем в историю*/
				( new BfwBogikaHistory )->add_history( $user_id, '+', absint($addball), '0', $prichina );
				/*Записываем в историю*/
			} else {
				/*Записываем в историю*/
				( new BfwBogikaHistory )->add_history( $user_id, '-', absint($addball), '0', $prichina );
				/*Записываем в историю*/
			}

			( new BfwPoints )->updatePoints( $user_id,  $newpoints );
		}

	} else {
		$balluser = ( new BfwPoints )->getPoints( $user_id );
		if ( $_POST['computy_input_points'] > 0 ) {

			$addball_nopro = sanitize_text_field( $_POST['computy_input_points'] );
			$addball       = $addball_nopro + $balluser;
			$prichina      = sanitize_text_field( $_POST['prichinaizmeneniya'] );
			/*Записываем в историю*/
			( new BfwBogikaHistory )->add_history( $user_id, '+', $addball_nopro, '0', $prichina );
			/*Записываем в историю*/

			/*Отправляем email клиенту*/
			$title_email = __( 'Bonus points have been added to you!', 'bonus-for-woo' );
			$info_email  = sprintf( __( '%s bonus points have been added to you.', 'bonus-for-woo' ),
				sanitize_text_field( $_POST['computy_input_points'] ) );

			$message_email = '<p>' . $info_email . '</p>';
			$message_email .= '<p>' . __( 'Cause', 'bonus-for-woo' ) . ': ' . $prichina . '</p>';
			$message_email .= '<p>' . __( 'The sum of your bonus points is now',
					'bonus-for-woo' ) . ': <b>' . $addball . ' ' . __( 'points', 'bonus-for-woo' ) . '</b></p>';
			$val           = get_option( 'bonus_option_name' );
			if ( ! empty( $val['email-change-admin'] ) ) {
				( new BfwEmail )->getMail( $user_id, '', $title_email, $message_email );
			}
			/*Отправляем email клиенту*/


			( new BfwPoints )->updatePoints( $user_id, $addball );
		}

	}
}
