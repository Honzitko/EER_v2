<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Worker_Tickets_In_Numbers {

	public static function eer_recount_event_statistics_callback($event_id) {
		foreach (EER()->ticket_summary->eer_get_ticket_by_event($event_id) as $id => $ticket_summary) {
			self::eer_recount_statistics($ticket_summary);
		}
	}


	public static function eer_recount_ticket_statistics_callback($ticket_id) {
		foreach (EER()->ticket_summary->eer_get_ticket_recount_data(intval($ticket_id)) as $id => $ticket_summary) {
			self::eer_recount_statistics($ticket_summary);
		}
	}


	private static function eer_recount_statistics($ticket_summary) {
		global $wpdb;
		if ($ticket_summary->has_levels) {
			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_ticket_summary AS ets
JOIN (SELECT COUNT(id) AS new_count FROM {$wpdb->prefix}eer_sold_tickets WHERE ticket_id = %d AND level_id = %d AND dancing_as = %d AND status = %d) AS summary
SET ets.registered_leaders = summary.new_count
WHERE ticket_id = %d AND level_id = %d", [$ticket_summary->ticket_id, $ticket_summary->level_id, EER_Enum_Dancing_As::LEADER, EER_Enum_Sold_Ticket_Status::CONFIRMED, $ticket_summary->ticket_id, $ticket_summary->level_id]));

			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_ticket_summary AS ets
JOIN (SELECT COUNT(id) AS new_count FROM {$wpdb->prefix}eer_sold_tickets WHERE ticket_id = %d AND level_id = %d AND dancing_as = %d AND status = %d) AS summary
SET ets.registered_followers = summary.new_count
WHERE ticket_id = %d AND level_id = %d", [$ticket_summary->ticket_id, $ticket_summary->level_id, EER_Enum_Dancing_As::FOLLOWER, EER_Enum_Sold_Ticket_Status::CONFIRMED, $ticket_summary->ticket_id, $ticket_summary->level_id]));

			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_ticket_summary AS ets
JOIN (SELECT COUNT(id) AS new_count FROM {$wpdb->prefix}eer_sold_tickets WHERE ticket_id = %d AND level_id = %d AND dancing_as = %d AND status = %d) AS summary
SET ets.registered_tickets = summary.new_count
WHERE ticket_id = %d AND level_id = %d", [$ticket_summary->ticket_id, $ticket_summary->level_id, EER_Enum_Dancing_As::SOLO, EER_Enum_Sold_Ticket_Status::CONFIRMED, $ticket_summary->ticket_id, $ticket_summary->level_id]));

			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_ticket_summary AS ets
JOIN (SELECT COUNT(id) AS new_count FROM {$wpdb->prefix}eer_sold_tickets WHERE ticket_id = %d AND level_id = %d AND dancing_as = %d AND status = %d) AS summary
SET ets.waiting_leaders = summary.new_count
WHERE ticket_id = %d AND level_id = %d", [$ticket_summary->ticket_id, $ticket_summary->level_id, EER_Enum_Dancing_As::LEADER, EER_Enum_Sold_Ticket_Status::WAITING, $ticket_summary->ticket_id, $ticket_summary->level_id]));

			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_ticket_summary AS ets
JOIN (SELECT COUNT(id) AS new_count FROM {$wpdb->prefix}eer_sold_tickets WHERE ticket_id = %d AND level_id = %d AND dancing_as = %d AND status = %d) AS summary
SET ets.waiting_followers = summary.new_count
WHERE ticket_id = %d AND level_id = %d", [$ticket_summary->ticket_id, $ticket_summary->level_id, EER_Enum_Dancing_As::FOLLOWER, EER_Enum_Sold_Ticket_Status::WAITING, $ticket_summary->ticket_id, $ticket_summary->level_id]));

			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_ticket_summary AS ets
JOIN (SELECT COUNT(id) AS new_count FROM {$wpdb->prefix}eer_sold_tickets WHERE ticket_id = %d AND level_id = %d AND dancing_as = %d AND status = %d) AS summary
SET ets.waiting_tickets = summary.new_count
WHERE ticket_id = %d AND level_id = %d", [$ticket_summary->ticket_id, $ticket_summary->level_id, EER_Enum_Dancing_As::SOLO, EER_Enum_Sold_Ticket_Status::WAITING, $ticket_summary->ticket_id, $ticket_summary->level_id]));
		} else {
			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_ticket_summary AS ets
JOIN (SELECT COUNT(id) AS new_count FROM {$wpdb->prefix}eer_sold_tickets WHERE ticket_id = %d AND dancing_as = %d AND status = %d) AS summary
SET ets.registered_leaders = summary.new_count
WHERE ticket_id = %d", [$ticket_summary->ticket_id, EER_Enum_Dancing_As::LEADER, EER_Enum_Sold_Ticket_Status::CONFIRMED, $ticket_summary->ticket_id]));

			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_ticket_summary AS ets
JOIN (SELECT COUNT(id) AS new_count FROM {$wpdb->prefix}eer_sold_tickets WHERE ticket_id = %d AND dancing_as = %d AND status = %d) AS summary
SET ets.registered_followers = summary.new_count
WHERE ticket_id = %d", [$ticket_summary->ticket_id, EER_Enum_Dancing_As::FOLLOWER, EER_Enum_Sold_Ticket_Status::CONFIRMED, $ticket_summary->ticket_id]));

			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_ticket_summary AS ets
JOIN (SELECT COUNT(id) AS new_count FROM {$wpdb->prefix}eer_sold_tickets WHERE ticket_id = %d AND dancing_as = %d AND status = %d) AS summary
SET ets.registered_tickets = summary.new_count
WHERE ticket_id = %d", [$ticket_summary->ticket_id, EER_Enum_Dancing_As::SOLO, EER_Enum_Sold_Ticket_Status::CONFIRMED, $ticket_summary->ticket_id]));

			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_ticket_summary AS ets
JOIN (SELECT COUNT(id) AS new_count FROM {$wpdb->prefix}eer_sold_tickets WHERE ticket_id = %d AND dancing_as = %d AND status = %d) AS summary
SET ets.waiting_leaders = summary.new_count
WHERE ticket_id = %d", [$ticket_summary->ticket_id, EER_Enum_Dancing_As::LEADER, EER_Enum_Sold_Ticket_Status::WAITING, $ticket_summary->ticket_id]));

			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_ticket_summary AS ets
JOIN (SELECT COUNT(id) AS new_count FROM {$wpdb->prefix}eer_sold_tickets WHERE ticket_id = %d AND dancing_as = %d AND status = %d) AS summary
SET ets.waiting_followers = summary.new_count
WHERE ticket_id = %d", [$ticket_summary->ticket_id, EER_Enum_Dancing_As::FOLLOWER, EER_Enum_Sold_Ticket_Status::WAITING, $ticket_summary->ticket_id]));

			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_ticket_summary AS ets
JOIN (SELECT COUNT(id) AS new_count FROM {$wpdb->prefix}eer_sold_tickets WHERE ticket_id = %d AND dancing_as = %d AND status = %d) AS summary
SET ets.waiting_tickets = summary.new_count
WHERE ticket_id = %d", [$ticket_summary->ticket_id, EER_Enum_Dancing_As::SOLO, EER_Enum_Sold_Ticket_Status::WAITING, $ticket_summary->ticket_id]));
		}
	}

}

add_action('eer_recount_event_statistics', ['EER_Worker_Tickets_In_Numbers', 'eer_recount_event_statistics_callback']);
add_action('eer_recount_ticket_statistics', ['EER_Worker_Tickets_In_Numbers', 'eer_recount_ticket_statistics_callback']);