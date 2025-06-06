<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Tickets_In_Numbers {

	const MENU_SLUG = 'eer_admin_sub_page_tickets_in_numbers';


	public static function print_content() {

		$selected_event = apply_filters('eer_all_events_select_get', []);

		if (isset($_POST['eer_recount_event']) && isset($_POST['eer_recount_event_id'])) {
			do_action('eer_recount_event_statistics', intval($_POST['eer_recount_event_id']));
		}

		?>
		<div class="wrap eer-settings">
			<?php do_action('eer_all_events_select_print', $selected_event); ?>
			<h1 class="wp-heading-inline"><?php _e('Tickets In Numbers', 'easy-event-registration'); ?></h1>

			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="eer-recount-button">
				<input type="hidden" name="eer_recount_event_id" value="<?php echo $selected_event; ?>">
				<input type="submit" name="eer_recount_event" class="page-title-action" value="<?php _e('Recount Statistics', 'easy-event-registration') ?>">
			</form>
			<table id="datatable" class="eer-datatable table table-default table-bordered eer-tickets-in-numbers-table">
				<thead>
				<tr>
					<th class="no-sort"><?php _e('Ticket', 'easy-event-registration') ?></th>
					<th class="filter-disabled no-sort"><?php _e('Level', 'easy-event-registration') ?></th>
					<th class="filter-disabled no-sort"><?php _e('Leaders', 'easy-event-registration') ?></th>
					<th class="filter-disabled no-sort"><?php _e('Followers', 'easy-event-registration') ?></th>
					<th class="filter-disabled no-sort"><?php _e('Tickets', 'easy-event-registration') ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach (EER()->ticket_summary->eer_get_ticket_by_event($selected_event) as $id => $ticket_summary) {

					if (((intval($ticket_summary->has_levels) === 1) && ($ticket_summary->level_id !== null)) || ((intval($ticket_summary->has_levels) !== 1) && ($ticket_summary->level_id === null))) {
						?>
						<tr>
							<td><?php echo $ticket_summary->title; ?></td>
							<td><?php if ($ticket_summary->has_levels) {
									echo json_decode($ticket_summary->ticket_settings)->levels->{$ticket_summary->level_id}->name;
								} ?></td>
							<td><?php echo !$ticket_summary->is_solo ? ($ticket_summary->registered_leaders . '/' . $ticket_summary->max_leaders . ' (' . $ticket_summary->waiting_leaders . ')') : ''; ?></td>
							<td><?php echo !$ticket_summary->is_solo ? ($ticket_summary->registered_followers . '/' . $ticket_summary->max_followers . ' (' . $ticket_summary->waiting_followers . ')') : ''; ?></td>
							<td><?php echo $ticket_summary->is_solo ? ($ticket_summary->registered_tickets . '/' . $ticket_summary->max_tickets . ' (' . $ticket_summary->waiting_tickets . ')') : ''; ?></td>
						</tr>
						<?php
					}
				}
				?>
				</tbody>
			</table>
		</div>

		<?php
	}

}
