<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Ticket_Summary {

	/**
	 * @param int $ticket_id
	 * @param int $level_id
	 * @param array $data
	 */
	public function eer_update_ticket_summary($ticket_id, $level_id, $data) {
		global $wpdb;
		$wpdb->update($wpdb->prefix . 'eer_ticket_summary', $data, ['ticket_id' => $ticket_id, 'level_id' => $level_id]);
	}


	/**
	 * @param int $ticket_id
	 * @param int $level_id
	 *
	 * @return object
	 */
	public function eer_get_ticket_summary($ticket_id, $level_id = null) {
		global $wpdb;
		if ($level_id !== null) {
			return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eer_ticket_summary WHERE ticket_id = %d AND level_id =  %d", [intval($ticket_id), $level_id]));
		} else {
			return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eer_ticket_summary WHERE ticket_id = %d", [intval($ticket_id)]));
		}
	}


	public function eer_get_ticket_summaries($ticket_id) {
		global $wpdb;

		return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eer_ticket_summary WHERE ticket_id = %d", [intval($ticket_id)]));
	}


	public function eer_get_ticket_by_event($event_id) {
		global $wpdb;

		return $wpdb->get_results($wpdb->prepare("SELECT ets.*, et.title, et.is_solo, et.has_levels, et.ticket_settings FROM {$wpdb->prefix}eer_ticket_summary AS ets LEFT JOIN {$wpdb->prefix}eer_tickets AS et ON ets.ticket_id = et.id WHERE et.event_id = %d ORDER BY ticket_id", [intval($event_id)]), OBJECT_K);
	}


	public function eer_get_ticket_recount_data($ticket_id) {
		global $wpdb;

		return $wpdb->get_results($wpdb->prepare("SELECT ets.*, et.title, et.is_solo, et.has_levels, et.ticket_settings FROM {$wpdb->prefix}eer_ticket_summary AS ets LEFT JOIN {$wpdb->prefix}eer_tickets AS et ON ets.ticket_id = et.id WHERE et.id = %d", [intval($ticket_id)]));
	}


	/**
	 * @param int $ticket_id
	 * @param int $level_id
	 *
	 * @return object
	 */
	public function eer_ticket_summary_exists($ticket_id, $level_id) {
		global $wpdb;

		return $wpdb->get_row($wpdb->prepare("SELECT 1 FROM {$wpdb->prefix}eer_ticket_summary WHERE ticket_id = %d AND level_id =  %d", [intval($ticket_id), $level_id]));
	}


	/**
	 * @param int $ticket_id
	 *
	 * @return object
	 */
	public function eer_get_ticket_availability_by_levels($ticket_id) {
		global $wpdb;

		return $wpdb->get_results($wpdb->prepare("SELECT ts.level_id, ts.max_leaders > ts.registered_leaders AS leaders, ts.max_followers > ts.registered_followers AS followers, ts.max_tickets > ts.registered_tickets AS tickets FROM {$wpdb->prefix}eer_ticket_summary AS ts JOIN {$wpdb->prefix}eer_tickets AS t ON t.id = ts.ticket_id WHERE ts.ticket_id = %d AND ts.level_id IS NOT NULL AND ((t.is_solo AND (ts.max_tickets > ts.registered_tickets)) OR (NOT t.is_solo AND ((ts.max_leaders > ts.registered_leaders) OR (ts.max_followers > ts.registered_followers))))", [intval($ticket_id)]), OBJECT_K);
	}


}