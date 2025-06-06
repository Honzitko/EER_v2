<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Sold_Ticket_Edit_Form
{

	public function __construct()
	{
		add_action('eer_sold_ticket_form_input', [get_called_class(), 'input_ticket']);
		add_action('eer_sold_ticket_form_input', [get_called_class(), 'input_dancing_with']);
		add_action('eer_sold_ticket_form_input', [get_called_class(), 'input_dancing_with_name']);
		add_action('eer_sold_ticket_form_input', [get_called_class(), 'input_partner_email']);
		add_action('eer_sold_ticket_form_submit', [get_called_class(), 'input_submit']);
	}


	public function print_form($event_id)
	{
		?>
		<div class="eer-edit-box-wrapper"></div>
		<div id="eer-edit-box" class="eer-edit-box">
			<span class="close"><span class="dashicons dashicons-no-alt"></span></span>
			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
				<table>
					<?php
					do_action('eer_sold_ticket_form_input', $event_id);
					$this->print_ticket_edit_data($event_id);
					do_action('eer_sold_ticket_form_submit', $event_id);
					?>
				</table>
			</form>
		</div>
		<?php
	}

	private function print_ticket_edit_data($event_id) {
		$tickets = EER()->ticket->get_tickets_by_event($event_id);
		foreach ($tickets as $key => $ticket) {
			?>
				<tbody class="eer-ticket-data eer-ticket-<?php echo $ticket->id; ?>">
					<?php if (!$ticket->is_solo) { ?>
						<tr>
							<th><?php _e('Dancing as', 'easy-event-registration'); ?></th>
							<td>
								<select id="dancing_as" class="eer-form-control eer-input" name="dancing_as_<?php echo $ticket->id; ?>">
									<?php
									foreach (EER()->dancing_as->eer_get_couple_items() as $id => $item) {
										?>
										<option value="<?php echo $id; ?>"><?php echo $item['title']; ?></option><?php
									}
									?>
								</select>
							</td>
						</tr>
					<?php } ?>
					<?php if ($ticket->has_levels) { ?>
						<tr>
							<th><?php _e('Level', 'easy-event-registration'); ?></th>
							<td>
								<select id="level_id" class="eer-form-control eer-input" name="level_id_<?php echo $ticket->id; ?>">
									<?php
									foreach ($ticket->levels as $id => $item) {
										?>
										<option value="<?php echo $id; ?>"><?php echo $item['name']; ?></option><?php
									}
									?>
								</select>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			<?php
		}
	}


	public static function input_ticket($event_id) {
		?>
		<tr>
			<th><?php _e('Ticket', 'easy-event-registration'); ?></th>
			<td>
				<select class="eer-input" name="ticket_id" id="ticket_id">
					<option value=""><?php _e('- select -', 'easy-event-registration'); ?></option>
					<?php
					foreach (EER()->ticket->get_tickets_by_event($event_id) as $id => $ticket) {
						?>
						<option value="<?php echo $ticket->id; ?>"><?php echo stripslashes($ticket->title); ?></option><?php
					}
					?>
				</select>
			</td>
		</tr>
		<?php
	}


	public static function input_dancing_with()
	{
		?>
		<tr>
			<th><?php _e('Dancing with', 'easy-event-registration'); ?></th>
			<td><input id="dancing_with" class="eer-form-control eer-input" type="email" name="dancing_with"></td>
		</tr>
		<?php
	}


	public static function input_dancing_with_name()
	{
		?>
		<tr>
			<th><?php _e('Dancing with name', 'easy-event-registration'); ?></th>
			<td><input id="dancing_with_name" class="eer-form-control eer-input" type="text" name="dancing_with_name"></td>
		</tr>
		<?php
	}


	public static function input_partner_email()
	{
		?>
		<tr>
			<th><?php _e('Partner email', 'easy-event-registration'); ?></th>
			<td><input id="partner_email" class="eer-form-control eer-input" type="email" name="partner_email"></td>
		</tr>
		<?php
	}


	public static function input_submit()
	{
		?>
		<tr>
			<th></th>
			<td>
				<input type="hidden" name="sold_ticket_id">
				<input type="submit" name="eer_sold_ticket_edit_submit" value="<?php _e('Save', 'easy-event-registration'); ?>">
			</td>
		</tr>
		<?php
	}
}
