<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Event_Sale
{

	public function print_content($attr)
	{
		do_action('eer_load_theme_scripts');
		$event_id = (int) $attr['event'];
		ob_start();

		// check event sale started
		echo '<div class="eer-tickets-sale-wrapper eer-event-' . $event_id . '" ' . apply_filters('eer_event_sale_discount_data', $event_id) . '>';
		if (EER()->event->is_event_sale_active($event_id) || (isset($attr['test']) && (intval($attr['test']) === 1))) {
			$templater_tickets = new EER_Template_Event_Sale_Tickets();
			$templater_user_form = new EER_Template_Event_Sale_User_Form();

			$templater_tickets->print_content($event_id);
			$templater_user_form->print_content($event_id);
		} else {
			$templater_not_opened = new EER_Template_Event_Sale_Not_Opened();
			$templater_not_opened->print_content($event_id);
		}
		echo '<div class="eer-spinner-bg" style="background: #000;opacity: 0.5;position: absolute;bottom: -10px;left: -10px;right: -10px;top: -10px;z-index: 5000;display: none;"></div></div>';

		echo ob_get_clean();
	}
}

