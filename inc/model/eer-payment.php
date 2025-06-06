<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Payment
{

	public function eer_get_payments_by_event($event_id)
	{
		global $wpdb;
		$payments = [];

		$results = $wpdb->get_results($wpdb->prepare("SELECT *, ep.id AS payment_id FROM {$wpdb->prefix}eer_events_payments AS ep JOIN {$wpdb->prefix}eer_events_orders eo ON ep.order_id = eo.id WHERE event_id = %d", [$event_id]));

		foreach ($results as $result) {
			if (!isset($payments[$result->order_id])) {
				$payments[$result->order_id] = $result;
			}
		}

		return $payments;
	}


	public function eer_get_payment($payment_id)
	{
		global $wpdb;

		return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eer_events_payments WHERE id = %d", [$payment_id]), OBJECT);
	}


	public function eer_get_payment_by_order($order_id)
	{
		global $wpdb;

		return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eer_events_payments WHERE order_id = %d", [intval($order_id)]), OBJECT);
	}


	public function eer_get_not_payed_payments_by_event($event_id)
	{
		global $wpdb;
		$payments = [];

		$results = $wpdb->get_results($wpdb->prepare("SELECT eo.id AS order_id, ep.id AS payment_id, u.ID, u.display_name, u.user_email, ep.confirmation_email_sent_timestamp, eo.inserted_datetime AS order_time, (SELECT MAX(confirmation_datetime) FROM {$wpdb->prefix}eer_sold_tickets WHERE order_id = ep.order_id AND status = %d) AS last_confirmation FROM {$wpdb->prefix}eer_events_payments AS ep JOIN {$wpdb->prefix}eer_events_orders eo ON ep.order_id = eo.id JOIN {$wpdb->users} u ON u.ID = eo.user_id WHERE eo.event_id = %d AND ep.status = %d ORDER BY eo.inserted_datetime DESC", [EER_Enum_Sold_Ticket_Status::CONFIRMED , $event_id, EER_Enum_Payment::NOT_PAID]));

		foreach ($results as $result) {
			if (!isset($payments[$result->order_id])) {
				$payments[$result->order_id] = $result;
			}
		}

		return $payments;
	}

	public static function eer_get_payment_by_order_callback($order_id)
	{
		global $wpdb;

		return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eer_events_payments WHERE order_id = %d", [intval($order_id)]));
	}
}

add_filter('eer_get_order_payment', ['EER_Payment', 'eer_get_payment_by_order_callback']);