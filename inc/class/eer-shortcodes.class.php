<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Shortcodes {

	// [eer_event_sale]
	public static function eer_event_sale_shortcode($attr) {
		ob_start();
		// check if event id is set and if it is and digit
		if (isset($attr['event']) && ctype_digit($attr['event'])) {
			$templater_event_sale = new EER_Template_Event_Sale();
			$templater_event_sale->print_content($attr);
		}

		return ob_get_clean();
	}

}

add_shortcode('eer_event_sale', ['EER_Shortcodes', 'eer_event_sale_shortcode']);