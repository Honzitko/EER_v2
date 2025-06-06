<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Event_Tickets_Filter_Template {

	public static function eer_print_select() {
		$tickets = EER()->ticket->get_tickets_by_event(apply_filters('eer_all_events_select_get', []));
		$selected_ticket = apply_filters('eer_event_tickets_select_get', null);

		?>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="eer-select-event">
			<span><?php echo __('Select ticket', 'easy-event-registration') . ': '; ?></span>
			<select name="eer_ticket">
				<?php
				foreach ($tickets as $key => $ticket) {
					?>
					<option
					value="<?php echo $ticket->id ?>" <?php if ($ticket->id == $selected_ticket) { ?>selected="selected"<?php } ?>><?php echo $ticket->title; ?></option><?php
				}
				?>
			</select>
			<input type="submit" name="eer_choose_ticket_submit" class="page-title-action" value="<?php _e('Select', 'easy-event-registration'); ?>">
		</form>
		<?php
	}


	public static function eer_get_selected_ticket($event_id) {
		$user_saved_ticket = get_user_meta(get_current_user_id(), 'eer_user_ticket_id');
		$ticket_data = null;
		$selected_event = ($event_id !== null) ? intval($event_id) : intval(apply_filters('eer_all_events_select_get', []));

		if (count($user_saved_ticket) > 0) {
			$ticket_data = EER()->ticket->get_ticket_data($user_saved_ticket[0]);
		}

		if (isset($_POST['eer_choose_ticket_submit']) && isset($_POST['eer_ticket'])) {
			update_user_meta(get_current_user_id(), 'eer_user_ticket_id', $_POST['eer_ticket']);
			return $_POST['eer_ticket'];
		} else if ((count($user_saved_ticket) > 0) && $ticket_data && (intval($ticket_data->event_id) === $selected_event)) {
			return $user_saved_ticket[0];
		} else {
			$tickets = EER()->ticket->get_tickets_by_event(($selected_event !== null) ? $selected_event : apply_filters('eer_all_events_select_get', []));
			$ticket = reset($tickets);
			if ($ticket) {
				update_user_meta(get_current_user_id(), 'eer_user_ticket_id', $ticket->id);
				return $ticket->id;
			}
		}

		return null;
	}
}

add_filter('eer_event_tickets_select_get', ['EER_Event_Tickets_Filter_Template', 'eer_get_selected_ticket']);

add_action('eer_event_tickets_select_print', ['EER_Event_Tickets_Filter_Template', 'eer_print_select']);