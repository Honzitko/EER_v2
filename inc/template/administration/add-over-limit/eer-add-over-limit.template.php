<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Add_Over_Limit
{

	const MENU_SLUG = 'eer_admin_sub_page_add_over_limit';


	public static function print_content()
	{
		$selected_event = apply_filters('eer_all_events_select_get', []);
		$selected_ticket = apply_filters('eer_event_tickets_select_get', $selected_event);
		?>
		<div class="wrap eer-settings eer-add-over-limit eer-tickets-sale-wrapper">
		<?php
			do_action('eer_all_events_select_print', $selected_event);
			do_action('eer_event_tickets_select_print');
			do_action('eer_print_add_over_limit_form', $selected_event, $selected_ticket);
		?>

		</div><?php
	}
}
