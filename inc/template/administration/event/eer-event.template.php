<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Event {

	const MENU_SLUG = 'eer_admin_event';


        public static function print_content() {
                if (isset($_POST['eer_event_submit'])) {
                        do_action('eer_process_event', $_POST);

                        $last_event_id = EER_Worker_Event::get_last_event_id();
                        if (!empty($last_event_id)) {
                                add_settings_error(
                                        'eer_events',
                                        'eer_event_saved',
                                        sprintf(
                                                /* translators: %d: event ID */
                                                __('Event saved successfully. The Event ID is %d.', 'easy-event-registration'),
                                                $last_event_id
                                        ),
                                        'updated'
                                );

                                add_settings_error(
                                        'eer_events',
                                        'eer_event_shortcode',
                                        sprintf(
                                                /* translators: %d: event ID */
                                                __('Use the Easy Event Registration block or the shortcode [eer_event_sale event="%1$d"] to embed this event (ID %1$d) on your site.', 'easy-event-registration'),
                                                $last_event_id
                                        ),
                                        'notice-info'
                                );
                        }
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
                        <?php settings_errors('eer_events'); ?>
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

