<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Event_Sale_Not_Opened
{

	public function print_content($event_id)
	{
		$event = EER()->event->get_event_data($event_id);

		if (EER()->event->is_event_sale_not_opened_yet($event_id)) {
			$to_return = EER()->model_settings->eer_find_option($event, 'sale_not_opened', 'Not opened yet');

			if (strpos($to_return, '[sale_start]') !== false) {
				$data      = EER()->event->get_event_data($event_id);
				$date      = new DateTime($data->sale_start);
				$to_return = str_replace("[sale_start]", $date->format(get_option('date_format') . ' ' . get_option('time_format')), $to_return);
			}

			echo '<div class="eer-not-opened-info">' . $to_return . '</div>';

			if (isset($event->show_tickets) && (intval($event->show_tickets) !== -1)) {
				$templater_tickets = new EER_Template_Event_Sale_Tickets();
				$templater_tickets->print_content($event_id, false);
			}
		} elseif (EER()->event->is_event_sale_closed($event_id)) {
			echo '<div class="eer-closed-info">' . EER()->model_settings->eer_find_option($event, 'sale_closed', 'Closed') . '</div>';
		}
	}
}

