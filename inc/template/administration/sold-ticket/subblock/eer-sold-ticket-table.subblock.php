<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Subblock_Sold_Ticket_Table {

	public function print_block($event_id) {
		$sold_tickets = EER()->sold_ticket->eer_get_sold_tickets_by_event($event_id);
		$tickets      = EER()->ticket->get_tickets_by_event($event_id);

		$orders     = EER()->order->eer_get_orders_by_event($event_id);
		$event_data = EER()->event->get_event_data($event_id);

		$partner_name_enabled = intval(EER()->event->eer_get_event_option($event_data, 'partner_name_enabled', -1)) === 1;

		$hosting_enabled         = intval(EER()->event->eer_get_event_option($event_data, 'hosting_enabled', -1)) === 1;
		$tshirts_enabled         = intval(EER()->event->eer_get_event_option($event_data, 'tshirts_enabled', -1)) === 1;
		$food_enabled            = intval(EER()->event->eer_get_event_option($event_data, 'food_enabled', -1)) === 1;
		$offer_hosting_enabled   = intval(EER()->event->eer_get_event_option($event_data, 'offer_hosting_enabled', -1)) === 1;
		$custom_checkbox_enabled = intval(EER()->event->eer_get_event_option($event_data, 'custom_checkbox_enabled', -1)) === 1;

		?>
		<table id="datatable" class="table table-default table-bordered eer-datatable eer-sold-tickets eer-add-email-export eer-copy-table eer-excel-export">
			<colgroup>
				<col width="100">
			</colgroup>
			<thead>
			<tr>
				<th class="filter-disabled"><?php _e('Order Timestamp', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort eer-hide-print"
				    data-key="eer_actions"><?php _e('Actions', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Status', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Ticket', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Name', 'easy-event-registration'); ?></th>
				<th class="no-sort eer-student-email"><?php _e('Email', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort"><?php _e('Ticket Code', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Level', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Dancing Role', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort"><?php _e('Registered Partner', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort"><?php _e('Paired Partner', 'easy-event-registration'); ?></th>
				<?php if (intval(EER()->event->eer_get_event_option($event_data, 'phone_enabled', -1)) === 1) { ?>
					<th class="filter-disabled"><?php _e('Phone', 'easy-event-registration'); ?></th>
				<?php } ?>
				<th class="filter-disabled"><?php _e('C.P. position', 'easy-event-registration'); ?></th>
				<?php if ($hosting_enabled) {
					?>
					<th class="no-sort"><?php _e('Hosting Request', 'easy-event-registration'); ?></th><?php
				} ?>
				<?php if ($tshirts_enabled) {
					?>
					<th class="no-sort"><?php _e('T-Shirt', 'easy-event-registration'); ?></th><?php
				} ?>
				<?php if ($food_enabled) {
					?>
					<th class="no-sort"><?php _e($event_data->food_table_title, 'easy-event-registration'); ?></th><?php
				} ?>
				<?php if ($offer_hosting_enabled) {
					?>
					<th class="no-sort"><?php _e('Hosting Offered', 'easy-event-registration'); ?></th><?php
				} ?>
				<?php if ($custom_checkbox_enabled) {
					?>
					<th class="no-sort"><?php _e($event_data->custom_checkbox_table_title, 'easy-event-registration'); ?></th><?php
				} ?>
			</tr>
			</thead>
			<tbody class="list">
			<?php
			$users_data = get_users(['fields' => ['ID', 'display_name', 'user_email']]);
			$users      = [];
			foreach ($users_data as $u) {
				$users[$u->ID] = $u;
			}
			foreach ($sold_tickets as $sold_ticket) {
				$order      = isset($orders[$sold_ticket->order_id]) ? $orders[$sold_ticket->order_id] : null;
				$order_info = json_decode($order->order_info);

				$ticket_id = $sold_ticket->ticket_id;
				$levels    = isset($tickets[$ticket_id]->levels) ? $tickets[$ticket_id]->levels : [];
				?>
				<tr class="eer-row eer-status-<?php echo $sold_ticket->status; ?>"
				    data-id="<?php echo $sold_ticket->id; ?>"
				    data-dancing_as="<?php echo $sold_ticket->dancing_as; ?>"
				    data-ticket_id="<?php echo $sold_ticket->ticket_id; ?>"
				    data-level_id="<?php echo $sold_ticket->level_id; ?>"
				    data-dancing_with="<?php echo $sold_ticket->dancing_with; ?>"
				    data-dancing_with_name="<?php echo $sold_ticket->dancing_with_name; ?>"
				    data-partner_email="<?php echo (($sold_ticket->partner_id !== null) && isset($users[$sold_ticket->partner_id]) ? $users[$sold_ticket->partner_id]->user_email : ''); ?>">
					<td><?php echo $sold_ticket->inserted_datetime; ?></td>
					<td class="actions eer-sold-tickets">
						<div class="eer-relative">
							<button class="page-title-action" type="button" data-toggle="dropdown" aria-expanded="false"><?php _e('Actions', 'easy-event-registration'); ?></button>
							<?php $this->print_action_box($sold_ticket->id); ?>
						</div>
					</td>
					<td><?php echo EER()->sold_ticket_status->get_title($sold_ticket->status); ?></td>
					<td><?php echo isset($tickets[$ticket_id]) ? $tickets[$ticket_id]->title : ''; ?></td>
					<td><?php echo isset($users[$order->user_id]) ? $users[$order->user_id]->display_name : ''; ?></td>
					<td><?php echo isset($users[$order->user_id]) ? $users[$order->user_id]->user_email : ''; ?></td>
					<td><?php echo $sold_ticket->unique_key; ?></td>
					<td><?php echo ($levels && $sold_ticket->level_id !== null) ? $levels[$sold_ticket->level_id]['name'] : ''; ?></td>
					<td><?php echo EER()->dancing_as->eer_get_title($sold_ticket->dancing_as); ?></td>
					<td><?php
						echo $sold_ticket->dancing_with;
						if ($partner_name_enabled && $sold_ticket->dancing_with_name) {
							echo ' (' . $sold_ticket->dancing_with_name . ')';
						}
						?></td>
					<td>
						<?php if ($sold_ticket->partner_id) {
							echo isset($users[$sold_ticket->partner_id]) ? $users[$sold_ticket->partner_id]->display_name : '';
						}; ?>
					</td>
					<?php if (intval(EER()->event->eer_get_event_option($event_data, 'phone_enabled', -1)) === 1) { ?>
						<td><?php echo (isset($order_info->phone) ? $order_info->phone : ''); ?></td>
					<?php } ?>
					<td><?php echo(isset($sold_ticket->cp_position) ? $sold_ticket->cp_position : ''); ?></td>
					<?php if ($hosting_enabled) {
						?>
						<td><?php echo $order_info->hosting ? __('Yes', 'easy-event-registration') : __('No', 'easy-event-registration'); ?></td><?php
					} ?>
					<?php if ($tshirts_enabled) {
						?>
						<td><?php echo ($order_info->tshirt === '') || !isset($event_data->tshirt_options[$order_info->tshirt]) ? __('No', 'easy-event-registration') : $event_data->tshirt_options[$order_info->tshirt]['name']; ?></td><?php
					} ?>
					<?php if ($food_enabled) {
						?>
						<td><?php echo (!isset($order_info->food) || ($order_info->food === '') || !isset($event_data->food_options[$order_info->food])) ? __('No', 'easy-event-registration') : $event_data->food_options[$order_info->food]['option']; ?></td><?php
					} ?>
					<?php if ($offer_hosting_enabled) {
						?>
						<td><?php echo (isset($order_info->offer_hosting) && $order_info->offer_hosting) ? __('Yes', 'easy-event-registration') : __('No', 'easy-event-registration'); ?></td><?php
					} ?>
					<?php if ($custom_checkbox_enabled) {
						?>
						<td><?php echo (isset($order_info->custom_checkbox) && $order_info->custom_checkbox) ? __('Yes', 'easy-event-registration') : __('No', 'easy-event-registration'); ?></td><?php
					} ?>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php
	}


	private function print_action_box($id) {
		?>
		<ul class="eer-actions-box dropdown-menu" data-id="<?php echo $id; ?>">
			<li class="eer-action edit">
				<a href="javascript:;">
					<span class="dashicons dashicons-edit"></span>
					<span><?php _e('Edit', 'easy-event-registration'); ?></span>
				</a>
			</li>
			<li class="eer-action confirm">
				<a href="javascript:;">
					<span class="dashicons dashicons-yes"></span>
					<span><?php _e('Confirm', 'easy-event-registration'); ?></span>
				</a>
			</li>
			<li class="eer-action remove">
				<a href="javascript:;">
					<span class="dashicons dashicons-no-alt"></span>
					<span><?php _e('Cancel', 'easy-event-registration'); ?></span>
				</a>
			</li>
			<li class="eer-action remove-forever">
				<a href="javascript:;">
					<span class="dashicons dashicons-trash"></span>
					<span><?php _e('Remove Forever', 'easy-event-registration'); ?></span>
				</a>
			</li>
		</ul>
		<?php
	}
}
