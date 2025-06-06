<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Event {

	const MENU_SLUG = 'eer_admin_event';


	public static function print_content() {
		if (isset($_POST['eer_event_submit'])) {
			do_action('eer_process_event', $_POST);
			if (isset($_GET['event_id']) && (intval($_GET['event_id']) === -1)) {
				unset($_GET['event_id']);
			}
		}

		$eer_edited_event_id = isset($_GET['event_id']) ? sanitize_text_field($_GET['event_id']) : null;

		$subblock_events_edit_form = new EER_Subblock_Event_Editor();
		$subblock_events_table     = new EER_Subblock_Event_Table();

		ob_start();
		?>
		<div class="wrap tabbable boxed parentTabs">
			<?php
			$event = EER()->event->get_event_data($eer_edited_event_id);
			if ($eer_edited_event_id && !empty($event)) {
				$subblock_events_edit_form->print_block($eer_edited_event_id);
			} else {
				$subblock_events_table->print_block();
			}
			?>
		</div><!-- #tab_container-->
		<?php
		echo ob_get_clean();
	}
}

