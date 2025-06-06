<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Sold_Ticket {

	const MENU_SLUG = 'eer_admin_sold_ticket';


	public static function print_content() {
		$template_sold_ticket_form   = new EER_Template_Sold_Ticket_Edit_Form();
		$subblock_sold_tickets_table = new EER_Subblock_Sold_Ticket_Table();

		$selected_event = apply_filters('eer_all_events_select_get', []);

		$user_can_edit = current_user_can('eer_sold_ticket_edit');

		if ($user_can_edit && isset($_POST['eer_sold_ticket_edit_submit'])) {
			$worker_sold_ticket = new EER_Worker_Sold_Ticket();
			$worker_sold_ticket->update_sold_ticket($_POST);
		}

		ob_start();
		?>
		<div class="wrap dataTable boxed parentTabs">
			<h1 class="wp-heading-inline"><?php _e('Sold Tickets', 'easy-event-registration'); ?></h1>
			<?php

			do_action('eer_all_events_select_print', $selected_event);

			if ($user_can_edit) {
				$template_sold_ticket_form->print_form($selected_event);
			}

			$subblock_sold_tickets_table->print_block($selected_event);
			?>
		</div><!-- #tab_container-->
		<?php
		echo ob_get_clean();
	}

}
