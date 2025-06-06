<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Payment_Emails
{

	const MENU_SLUG = 'eer_admin_sub_page_payment_emails';


	public static function print_content()
	{
		$data = $_POST;

		$selected_event = apply_filters('eer_all_events_select_get', []);

		if (isset($data['eer_send_payment_email_submit'])) {
			$event_data = EER()->event->get_event_data($selected_event);
			foreach ($data['eer_choosed_payments'] as $payment_id) {
				EER()->email->eer_send_payment_email($payment_id, $event_data);
			}
		}

		?>
		<div class="wrap">

		<?php
		do_action('eer_all_events_select_print', $selected_event);
		?>

		<h2>Not Paid</h2>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<table id="datatable" class="table table-default table-bordered eer-datatable eer-orders">
				<thead>
				<tr>
					<th class="filter-disabled no-sort"><input type="checkbox" name="eer-select-all"/><label><?php _e('select all', 'easy-event-registration'); ?></label></th>
					<th class="filter-disabled no-sort"><?php _e('Name', 'easy-event-registration'); ?></th>
					<th class="filter-disabled no-sort"><?php _e('Email', 'easy-event-registration'); ?></th>
					<th><?php _e('Order time', 'easy-event-registration'); ?></th>
					<th><?php _e('Last confirmation', 'easy-event-registration'); ?></th>
					<th><?php _e('Last email sent', 'easy-event-registration'); ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach (EER()->payment->eer_get_not_payed_payments_by_event($selected_event) as $payment) {
					?>
					<tr>
						<td><input type="checkbox" name="eer_choosed_payments[]" value="<?php echo $payment->payment_id; ?>">
						</td>
						<td><?php echo $payment->display_name; ?></td>
						<td><?php echo $payment->user_email; ?></td>
						<td><?php echo $payment->order_time; ?></td>
						<td><?php echo $payment->last_confirmation; ?></td>
						<td><?php echo ($payment->confirmation_email_sent_timestamp != null ? $payment->confirmation_email_sent_timestamp : __('Not send', 'easy-event-registration')); ?></td>
					</tr>
					<?php
				}

				?>
				</tbody>
			</table>
			<input type="hidden" name="eer_event" value="<?php echo $selected_event; ?>">
			<input type="submit" name="eer_send_payment_email_submit">
		</form>
		</div><?php
	}
}
