<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_All_Events_Select {

	public static function eer_print_select($selected_event = null) {
		$events = EER()->event->load_events_without_data();

		if (!$selected_event) {
			$selected_event = apply_filters('eer_all_events_select_get', $events);
		}

		?>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="eer-select-event">
			<span><?php echo __('Select event', 'easy-event-registration') . ': '; ?></span>
			<select name="eer_event">
				<?php
				foreach ($events as $key => $event) {
					?>
					<option
					value="<?php echo $event->id ?>" <?php if ($event->id == $selected_event) { ?>selected="selected"<?php } ?>><?php echo $event->title; ?></option><?php
				}
				?>
			</select>
			<input type="submit" name="eer_choose_event_submit" class="page-title-action" value="<?php _e('Select', 'easy-event-registration'); ?>">
		</form>
		<?php
	}


	/**
	 * @param array $events
	 *
	 * @return int
	 */
	public static function eer_get_selected_event($events = []) {
		$user_saved_event = get_user_meta(get_current_user_id(), 'eer_user_event_id');
		if (isset($_POST['eer_choose_event_submit']) && isset($_POST['eer_event'])) {
			update_user_meta(get_current_user_id(), 'eer_user_event_id', $_POST['eer_event']);
			return $_POST['eer_event'];
		} else if (count($user_saved_event) > 0) {
			return $user_saved_event[0];
		} else if ($events) {
			return reset($events);
		} else {
			$events = EER()->event->load_events_without_data();
			$event  = reset($events);
			if ($event) {
				return $event->id;
			}
		}

		return null;
	}
}

add_filter('eer_all_events_select_get', ['EER_Template_All_Events_Select', 'eer_get_selected_event']);

add_action('eer_all_events_select_print', ['EER_Template_All_Events_Select', 'eer_print_select']);