<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Subblock_Order_Table {

	public function print_block($event_id) {
		$orders     = EER()->order->eer_get_orders_by_event($event_id);
		$event_data = EER()->event->get_event_data($event_id);

		$users_data = [];

		$hosting_enabled       = intval(EER()->event->eer_get_event_option($event_data, 'hosting_enabled', -1)) === 1;
		$tshirts_enabled       = intval(EER()->event->eer_get_event_option($event_data, 'tshirts_enabled', -1)) === 1;
		$food_enabled          = intval(EER()->event->eer_get_event_option($event_data, 'food_enabled', -1)) === 1;
		$offer_hosting_enabled = intval(EER()->event->eer_get_event_option($event_data, 'offer_hosting_enabled', -1)) === 1;
		$custom_checkbox_enabled = intval(EER()->event->eer_get_event_option($event_data, 'custom_checkbox_enabled', -1)) === 1;
		$custom_area_enabled = intval(EER()->event->eer_get_event_option($event_data, 'custom_area_enabled', -1)) === 1;

		?>
		<?php do_action('eer_order_table_before_table', $event_id) ?>
		<table id="datatable" class="table table-default table-bordered eer-datatable eer-orders eer-add-email-export eer-copy-table eer-excel-export">
			<colgroup>
				<col width="150">
				<col width="100">
				<col width="50">
			</colgroup>
			<thead>
			<tr>
				<th class="filter-disabled"><?php _e('Order Timestamp', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort eer-hide-print" data-key="eer_actions"><?php _e('Actions', 'easy-event-registration'); ?></th>
                <?php if (intval(EER()->event->eer_get_event_option($event_data, 'note_enabled', 1)) === 1) { ?>
					<th class="filter-disabled eer-header-note"><?php _e('Note', 'easy-event-registration'); ?>
						<span class="dashicons dashicons-admin-comments eer-show-all-notes"></span>
						<span class="dashicons dashicons-welcome-comments eer-hide-all-notes"></span>
					</th>
                <?php } ?>
				<th class="filter-disabled"><?php _e('Order Code', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Name', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Surname', 'easy-event-registration'); ?></th>
				<th class="no-sort eer-student-email"><?php _e('Email', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Phone', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Country', 'easy-event-registration'); ?></th>
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
				<?php if ($custom_area_enabled) {
					?>
					<th class="no-sort"><?php _e($event_data->custom_area_table_title, 'easy-event-registration'); ?></th><?php
				} ?>
				<?php do_action('eer_print_after_order_table_header', $event_data); ?>
			</tr>
			</thead>
			<tbody class="list">
			<?php foreach ($orders as $order) {
				if (!isset($users_data[$order->user_id])) {
					$users_data[$order->user_id] = get_userdata($order->user_id);
				}

				$user_data_exists = isset($users_data[$order->user_id]) && $users_data[$order->user_id];
				$order_info       = json_decode($order->order_info);

				?>
				<tr class="<?php echo apply_filters('eer_get_order_row_classes', ['order' => $order, 'class' => ''])['class']; ?>"
				    data-id="<?php echo $order->id; ?>"
				    data-phone="<?php echo(isset($order_info->phone) ? $order_info->phone : ''); ?>"
				    data-country="<?php echo(isset($order_info->country) ? $order_info->country : ''); ?>"
				    data-hosting="<?php echo(isset($order_info->hosting) ? $order_info->hosting : ''); ?>"
				    data-tshirt="<?php echo(isset($order_info->tshirt) ? $order_info->tshirt : ''); ?>"
				    data-food="<?php echo(isset($order_info->food) ? $order_info->food : ''); ?>"
				    data-offer_hosting="<?php echo(isset($order_info->offer_hosting) ? $order_info->offer_hosting : ''); ?>"
				    data-custom_checkbox="<?php echo(isset($order_info->custom_checkbox) ? $order_info->custom_checkbox : ''); ?>"
				    data-custom_area="<?php echo(isset($order_info->custom_area) ? htmlspecialchars($order_info->custom_area) : ''); ?>">
					<td><?php echo $order->inserted_datetime; ?></td>
					<td class="actions eer-orders">
						<div class="eer-relative">
							<button class="page-title-action"><?php _e('Actions', 'easy-event-registration') ?></button>
						</div>
					</td>
                    <?php if (intval(EER()->event->eer_get_event_option($event_data, 'note_enabled', 1)) === 1) { ?>
						<td class="eer-note">
							<?php if (isset($order_info->note) && ($order_info->note !== null) && ($order_info->note !== "")) { ?>
								<span class="dashicons dashicons-admin-comments eer-show-note" title="<?php echo htmlspecialchars($order_info->note); ?>"></span>
								<span class="dashicons dashicons-welcome-comments eer-hide-note"></span>
								<span class="eer-note-message"><?php echo htmlspecialchars($order_info->note); ?></span>
							<?php } ?>
						</td>
                    <?php } ?>
					<td><?php echo $order->unique_key; ?></td>
					<td><?php echo($user_data_exists ? $users_data[$order->user_id]->first_name : ''); ?></td>
					<td><?php echo($user_data_exists ? $users_data[$order->user_id]->last_name : ''); ?></td>
					<td><?php echo($user_data_exists ? $users_data[$order->user_id]->user_email : ''); ?></td>
					<td><?php echo (isset($order_info->phone)) ? $order_info->phone : '' ?></td>
					<td><?php echo (isset($order_info->country)) ? $order_info->country : '' ?></td>
					<?php if ($hosting_enabled) {
						?>
						<td><?php echo $order_info->hosting ? __('Yes', 'easy-event-registration') : __('No', 'easy-event-registration'); ?></td><?php
					} ?>
					<?php if ($tshirts_enabled) {
						?>
						<td><?php echo (!isset($order_info->tshirt) || ($order_info->tshirt === '') || !isset($event_data->tshirt_options[$order_info->tshirt])) ? __('No', 'easy-event-registration') : $event_data->tshirt_options[$order_info->tshirt]['name']; ?></td><?php
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
					<?php if ($custom_area_enabled) {
						?>
						<td><?php echo isset($order_info->custom_area) ? $order_info->custom_area : ''; ?></td><?php
					} ?>
					<?php do_action('eer_print_after_order_table_content', $event_data, $order_info); ?>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php $this->print_action_box($event_id);
	}


	private function print_action_box($event_id) {
		?>
		<ul class="eer-actions-box eer-orders-box dropdown-menu" data-id="<?php echo $event_id; ?>">
			<li class="eer-action edit">
				<a href="javascript:;">
					<span class="dashicons dashicons-edit"></span>
					<span><?php _e('Edit', 'easy-event-registration'); ?></span>
				</a>
			</li>
			<li class="eer-action remove">
				<a href="javascript:;">
					<span class="dashicons dashicons-no-alt"></span>
					<span><?php _e('Remove', 'easy-event-registration'); ?></span>
				</a>
			</li>
			<li class="eer-action remove-forever">
				<a href="javascript:;">
					<span class="dashicons dashicons-trash"></span>
					<span><?php _e('Remove Forever', 'easy-event-registration'); ?></span>
				</a>
			</li>
			<?php do_action('eer_order_table_action_box_item', $event_id) ?>
		</ul>
		<?php
	}


	public static function eer_get_row_classes($data) {
		$classes = [
			'eer-row',
			'eer-order',
			'eer-status-' . $data['order']->status
		];

		$data['class'] .= ' ' . implode(' ', $classes);

		return $data;
	}
}

add_filter('eer_get_order_row_classes', ['EER_Subblock_Order_Table', 'eer_get_row_classes']);
