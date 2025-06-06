<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Payment_Email
{
	private $worker_payment_email;

	public function __construct()
	{
		$this->worker_payment_email = new EER_Worker_Payment_Email();
	}


	public function send_email($payment_id, $event_data)
	{
		$payment = EER()->payment->eer_get_payment($payment_id);
		$order = EER()->order->eer_get_order($payment->order_id);
		$order_info = json_decode($order->order_info);

		$user = get_user_by('ID', $order->user_id);

		$subject = stripcslashes(EER()->event->eer_get_event_option($event_data, 'payment_reminder_email_subject', ''));
		$body = stripcslashes(EER()->event->eer_get_event_option($event_data, 'payment_reminder_email_body', null));

		if (!empty($body)) {
			$tags = EER()->tags->get_tags('payment_reminder_email');

			foreach ($tags as $key => $tag) {
				$parameter = null;
				if (isset($tag['parameter'])) {
					switch ($tag['parameter']) {
						case 'event_title':
							{
								$parameter = $event_data->title;
								break;
							}
						case 'order_info':
							{
								$parameter = $order_info;
								break;
							}
						case 'tickets_list' :
							{
								$parameter = EER()->sold_ticket->eer_get_confirmed_sold_tickets_by_order($order->id);
								break;
							}
						case 'to_pay' :
							{
								$parameter = ['event' => $event_data, 'price' => $payment->to_pay];
								break;
							}
						case 'order_code' :
							{
								$parameter = $order->unique_key;
								break;
							}
						case 'hosting_option':
							{
								$parameter = property_exists($order_info, 'hosting') && $order_info->hosting ? __('Yes', 'easy-event-registration') : __('No', 'easy-event-registration');
								break;
							}
						case 'hosting_offer_option':
							{
								$parameter = property_exists($order_info, 'offer_hosting') && $order_info->offer_hosting ? __('Yes', 'easy-event-registration') : __('No', 'easy-event-registration');
								break;
							}
						case 'tshirt_option':
							{
								$parameter = !property_exists($order_info, 'tshirt') || ($order_info->tshirt === '') || !isset($event_data->tshirt_options[$order_info->tshirt]) ? __('No', 'easy-event-registration') : $event_data->tshirt_options[$order_info->tshirt]['name'];
								break;
							}
						case 'food_option':
							{
								$parameter = !property_exists($order_info, 'food') || ($order_info->food === '') || !isset($event_data->food_options[$order_info->food]) ? __('No', 'easy-event-registration') : $event_data->food_options[$order_info->food]['option'];
								break;
							}
					}

					$body = call_user_func(['EER_Template_Settings_Tag', $tag['function']], $tag, $body, $parameter);
				}
			}

			$status = apply_filters('eer_send_email', $user->user_email, $subject, $body, $event_data);

			if ($status) {
				$this->worker_payment_email->eer_update_payment_email_timestamp($payment_id);
			}

			return $status;
		}

		return false;
	}

}
