<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Order_Email {

	public function send_email($order_id, $registration_data) {
		$order      = EER()->order->eer_get_order($order_id);
		$order_info = json_decode($order->order_info);

		$event_data = EER()->event->get_event_data($order->event_id);

		$user = get_user_by('ID', $order->user_id);

                $subject = stripcslashes(EER()->event->eer_get_event_option($event_data, 'order_email_subject', ''));
                if (empty($subject)) {
                        $subject = stripcslashes(EER()->settings->eer_get_option('default_order_email_subject', ''));
                }
                $body    = stripcslashes(EER()->event->eer_get_event_option($event_data, 'order_email_body', null));
                if ($body === null || $body === '') {
                        $body = stripcslashes(EER()->settings->eer_get_option('default_order_email_body', ''));
                }

		if (!empty($body)) {
			$tags = EER()->tags->get_tags('order_email');

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
						case 'hosting_option':
							{
								$parameter = $order_info->hosting ? __('Yes', 'easy-event-registration') : __('No', 'easy-event-registration');
								break;
							}
						case 'hosting_offer_option':
							{
								$parameter = $order_info->offer_hosting ? __('Yes', 'easy-event-registration') : __('No', 'easy-event-registration');
								break;
							}
						case 'tshirt_option':
							{
								$parameter = ($order_info->tshirt === '') || !isset($event_data->tshirt_options[$order_info->tshirt]) ? __('No', 'easy-event-registration') : $event_data->tshirt_options[$order_info->tshirt]['name'];
								break;
							}
						case 'food_option':
							{
								$parameter = ($order_info->food === '') || !isset($event_data->food_options[$order_info->food]) ? __('No', 'easy-event-registration') : $event_data->food_options[$order_info->food]['option'];
								break;
							}
						case 'custom_checkbox':
							{
								$parameter = $order_info->custom_checkbox ? __('Yes', 'easy-event-registration') : __('No', 'easy-event-registration');
								break;
							}
						case 'list_registered':
							{
								$parameter = $order_id;
								break;
							}
					}

					$body = call_user_func(['EER_Template_Settings_Tag', $tag['function']], $tag, $body, $parameter);
				}
			}

			return apply_filters('eer_send_email', $user->user_email, $subject, $body, $event_data);
		}

		return false;
	}

}
