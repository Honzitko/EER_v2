<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Worker_Payment
{

	public function eer_update_user_payment($order_id)
	{
		return apply_filters('eer_update_user_payment', $order_id);
	}

	public static function eer_update_payments_by_event($event_id)
	{
		foreach (EER()->order->eer_get_orders_by_event($event_id) as $key => $order) {
			apply_filters('eer_update_user_payment', $order->id);
		}

		return $event_id;
	}

	public static function eer_update_user_payment_filter($order_id)
	{
		$other_payments = self::eer_get_other_order_payments($order_id);
		self::eer_update_payment($order_id, $other_payments);
		self::eer_insert_payment($order_id, $other_payments);
		self::eer_delete_payment($order_id);

		return $order_id;
	}


	private static function eer_update_payment($order_id, $other_payments)
	{
		global $wpdb;

		$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_events_payments AS up 
  JOIN (SELECT eo.id AS order_id, SUM(t.price) + %d AS to_pay 
		  FROM {$wpdb->prefix}eer_events_orders AS eo
		  JOIN {$wpdb->prefix}eer_sold_tickets AS st ON eo.id = st.order_id
		  JOIN {$wpdb->prefix}eer_tickets AS t ON t.id = st.ticket_id
		 WHERE st.status = %d 
		   AND t.price > 0 
		   AND eo.id = %d
		 GROUP BY eo.id) AS price ON up.order_id = price.order_id
   SET up.to_pay = price.to_pay, up.discount_info = ''", [
			$other_payments,
			EER_Enum_Sold_Ticket_Status::CONFIRMED,
			$order_id,
		]));
	}


	private static function eer_insert_payment($order_id, $other_payments)
	{
		global $wpdb;

		$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}eer_events_payments(payment, order_id, to_pay) 
SELECT NULL, eo.id, SUM(t.price) + %d AS to_pay
  FROM {$wpdb->prefix}eer_events_orders AS eo
  JOIN {$wpdb->prefix}eer_sold_tickets AS st ON eo.id = st.order_id
  JOIN {$wpdb->prefix}eer_tickets AS t ON t.id = st.ticket_id
 WHERE st.status = %d 
   AND t.price > 0 
   AND eo.id = %d 
   AND NOT EXISTS (SELECT 1 FROM {$wpdb->prefix}eer_events_payments WHERE order_id = %d)
 GROUP BY eo.id", [
			$other_payments,
			EER_Enum_Sold_Ticket_Status::CONFIRMED,
			$order_id,
			$order_id,
		]));
	}


	private static function eer_delete_payment($order_id)
	{
		global $wpdb;
		$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}eer_events_payments WHERE order_id = %d AND NOT EXISTS (SELECT 1 FROM {$wpdb->prefix}eer_sold_tickets AS st WHERE st.order_id = %d AND st.status = %d)", [$order_id, $order_id, EER_Enum_Sold_Ticket_Status::CONFIRMED]));
	}

	private static function eer_get_other_order_payments($order_id)
	{
		$other_payments = 0;

		if ($order_id) {
			$order = EER()->order->eer_get_order($order_id);

			if ($order) {
				$order_info = json_decode($order->order_info);

				$event_data = EER()->event->get_event_data($order->event_id);

				if (isset($order_info->tshirt) && (!(($order_info->tshirt === '') || !isset($event_data->tshirt_options[$order_info->tshirt])))) {
					$other_payments += intval($event_data->tshirt_options[$order_info->tshirt]['price']);
				}
				if (isset($order_info->food) && (!(($order_info->food === '') || !isset($event_data->food_options[$order_info->food])))) {
					$other_payments += intval($event_data->food_options[$order_info->food]['price']);
				}
			}
		}

		return $other_payments;

	}

}

add_filter('eer_update_user_payment', ['EER_Worker_Payment', 'eer_update_user_payment_filter'], 10);
add_filter('eer_update_payments_by_event', ['EER_Worker_Payment', 'eer_update_payments_by_event'], 10);
