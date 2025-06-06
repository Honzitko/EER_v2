<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EER_Event_Manager {

	public function eer_get_all_event_managers() {
		return get_users( [
			'role' => 'eer_event_manager',
		] );
	}

	public function eer_get_event_manager($event_id, $user_id) {
		global $wpdb;
		return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eer_event_managers WHERE event_id = %d AND user_id = %d", intval($event_id), intval($user_id)));
	}

	public function eer_get_event_managers($event_id) {
		global $wpdb;
		$user_ids = [];

		foreach ($wpdb->get_results($wpdb->prepare("SELECT user_id FROM {$wpdb->prefix}eer_event_managers WHERE event_id = %d", intval($event_id))) as $key => $user_id) {
			$user_ids[] = $user_id->user_id;
		}
		return $user_ids;
	}

}
