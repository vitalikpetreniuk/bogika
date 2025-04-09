<?php
add_action('wp_ajax_bogika_admin_change_user_status', function () {
	$user_id= absint($_POST['user_id']);
	if (!$user_id) wp_send_json_error();

	$status = get_user_meta($user_id, 'bfw_status', true);

	if ((int) $status === 1) {
		delete_user_meta($user_id, 'bfw_status');
	}else {
		update_user_meta($user_id, 'bfw_status', true);
	}

	wp_send_json_success();
});


