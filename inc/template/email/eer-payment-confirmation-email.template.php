<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Payment_Confirmation_Email {

	private $worker_email;


	public function __construct() {
		$this->worker_email = new EER_Worker_Email();
	}


	public function send_email($order_id) {
		$order      = EER()->order->eer_get_order($order_id);
		$event_data = EER()->event->get_event_data($order->event_id);
		$user       = get_user_by('ID', $order->user_id);
		$order_info = json_decode($order->order_info);

		$subject = stripcslashes(EER()->event->eer_get_event_option($event_data, 'payment_confirmation_email_subject', ''));
		$body    = stripcslashes(EER()->event->eer_get_event_option($event_data, 'payment_confirmation_email_body', null));

		$order->user = $user;
		if (!empty($body)) {
			$tags = EER()->tags->get_tags('payment_confirmation_email');

			foreach ($tags as $key => $tag) {
				$parameter = null;
				$template  = 'EER_Template_Settings_Tag';

				if (isset($tag['template'])) {
					$template = $tag['template'];
				}

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
						case 'order':
						{
							$parameter = $order;
							break;
						}
					}

					$body = call_user_func([$template, $tag['function']], $tag, $body, $parameter);
				}
			}

			return apply_filters('eer_send_email', $user->user_email, $subject, $body, $event_data);

		}

		return false;
	}

}
