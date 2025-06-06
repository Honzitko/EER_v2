<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Order_Confirmation_Email {

	public static function eer_send_order_confirmation_email_callback($event_id, $order_data) {
		$sold_ticket    = EER()->sold_ticket->eer_get_sold_tickets_data($order_data['sold_ticket_id']);
		$order          = EER()->order->eer_get_order($sold_ticket->order_id);
		$order_info     = json_decode($order->order_info);
		$event_data     = EER()->event->get_event_data($event_id);
		$floating_price = null;

		$subject = stripcslashes(EER()->event->eer_get_event_option($event_data, 'order_confirmation_email_subject', ''));

		$body = stripcslashes(EER()->event->eer_get_event_option($event_data, 'order_confirmation_email_body', null));

		$ticket = EER()->ticket->get_ticket_data($sold_ticket->ticket_id);
		$user   = get_user_by('ID', $order->user_id);

		$payment = EER()->payment->eer_get_payment_by_order($sold_ticket->order_id);

		if (intval(EER()->event->eer_get_event_option($event_data, 'floating_price_enabled', -1)) !== -1) {
			$previous_price = isset($order_data['previous_price']) ? $order_data['previous_price'] : 0;
			$actual_price   = isset($order_data['actual_price']) ? $order_data['actual_price'] : apply_filters('eer_get_order_payment', $sold_ticket->order_id)->to_pay;;

			$floating_price = floatval($actual_price) - floatval($previous_price);
			$floating_price = $floating_price > 0 ? $floating_price : 0;
		}

		if (!empty($body)) {
			$tags = EER()->tags->get_tags('order_confirmation_email', $event_data);

			foreach ($tags as $tag_key => $tag) {
				$parameter = null;
				if (isset($tag['parameter'])) {
					switch ($tag['parameter']) {
						case 'event_title':
						{
							$parameter = $event_data->title;
							break;
						}
						case 'ticket_title' :
						{
							$parameter = $ticket->title;
							break;
						}
						case 'order_code' :
						{
							$parameter = $order->unique_key;
							break;
						}
						case 'ticket_code' :
						{
							$parameter = $sold_ticket->unique_key;
							break;
						}
						case 'order_info' :
						{
							$parameter = $order_info;
							break;
						}
						case 'dancing_as' :
						{
							$parameter = $sold_ticket->dancing_as;
							break;
						}
						case 'price' :
						{
							$parameter = ['price' => $ticket->price, 'event' => $event_data];
							break;
						}
						case 'floating_price':
						{
							$parameter = ['price' => $floating_price, 'event' => $event_data];
							break;
						}
						case 'to_pay' :
						{
							$parameter = ['price' => $payment->to_pay, 'event' => $event_data];
							break;
						}
						case 'custom_checkbox':
						{
							$parameter = isset($order_info->custom_checkbox) && $order_info->custom_checkbox ? __('Yes', 'easy-event-registration') : __('No', 'easy-event-registration');
							break;
						}
						case 'payment':
						{
							$parameter = $payment;
							break;
						}
						case 'different_currency_price':
						{
							$parameter = isset($ticket->different_price) ? $ticket->different_price : null;
							break;
						}
						case 'has_level':
						{
							$parameter = isset($sold_ticket->level_id) && ($sold_ticket->level_id !== null) && ($sold_ticket->level_id !== '');
							break;
						}
						case 'level':
						{
							$levels    = isset($ticket->levels) ? $ticket->levels : [];
							$parameter = (($levels) ? $levels[$sold_ticket->level_id]['name'] : '');
							break;
						}
					}

					$call_class = isset($tag['call_class']) ? $tag['call_class'] : 'EER_Template_Settings_Tag';
					$body       = call_user_func([$call_class, $tag['function']], $tag, $body, $parameter);
				}
			}

			return apply_filters('eer_send_email', $user->user_email, $subject, $body, $event_data);
		}

		return false;
	}
}

add_action('eer_send_order_confirmation_email', ['EER_Template_Order_Confirmation_Email', 'eer_send_order_confirmation_email_callback'], 10, 2);