<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Ticket {

	const MENU_SLUG = 'eer_admin_ticket';


	public static function print_content() {
		if (isset($_POST['eer_ticket_submit'])) {
			$worker_ticket = new EER_Worker_Ticket();
			$worker_ticket->process_ticket($_POST);
			if (isset($_GET['ticket_id']) && (intval($_GET['ticket_id']) === -1)) {
				unset($_GET['ticket_id']);
			}
		}

		$eer_edited_ticket_id = isset($_GET['ticket_id']) ? sanitize_text_field($_GET['ticket_id']) : null;

		$subblock_tickets_edit_form = new EER_Subblock_Ticket_Editor();
		$subblock_tickets_table     = new EER_Subblock_Ticket_Table();

		ob_start();
		?>
		<div class="wrap tabbable boxed parentTabs">
			<?php
			if ($eer_edited_ticket_id && (($eer_edited_ticket_id == -1) || EER()->ticket->eer_check_ticket_exists($eer_edited_ticket_id))) {
				$subblock_tickets_edit_form->print_block($eer_edited_ticket_id);
			} else {
				$subblock_tickets_table->print_block();
			}
			?>
		</div><!-- #tab_container-->
		<?php
		echo ob_get_clean();
	}

}
