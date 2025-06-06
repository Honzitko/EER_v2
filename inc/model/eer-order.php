<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Order
{

	public function generate_unique_order_key($event_id)
	{
		global $wpdb;

		do {
			$token = mt_rand(10000000, 99999999);
			$result = $wpdb->get_var($wpdb->prepare("SELECT 1 FROM {$wpdb->prefix}eer_events_orders WHERE event_id = %d AND unique_key = %s", [intval($event_id), $token]));
		} while (intval($result) > 0);

		return $token;
	}


	public function eer_get_orders_by_event($event_id)
	{
		global $wpdb;

		return $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eer_events_orders WHERE event_id = %d", [intval($event_id)]), OBJECT_K);
	}


	public function eer_get_order($order_id)
	{
		global $wpdb;

		return $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eer_events_orders WHERE id = %d", [intval($order_id)]), OBJECT);
	}

}
