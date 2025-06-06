<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Subblock_Ticket_Table
{

	public function print_block()
	{
		$tickets = EER()->ticket->load_tickets();
		?>
		<h1 class="wp-heading-inline"><?php _e('Tickets', 'easy-event-registration'); ?></h1>
		<a href="<?php echo esc_url(add_query_arg('ticket_id', -1)) ?>" class="eer-add-new page-title-action"><?php _e('Add New Ticket', 'easy-event-registration'); ?></a>
		<table id="datatable" class="table table-default table-bordered eer-datatable">
			<colgroup>
				<col width="100">
				<col width="180">
				<col width="500">
			</colgroup>
			<thead>
			<tr>
				<th class="filter-disabled no-sort" data-key="eer_actions"><?php _e('Actions', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Ticket Title', 'easy-event-registration'); ?></th>
				<th class="no-sort" data-key="eer_title"><?php _e('Event', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Price', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Number Of Tickets', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Max Per Order', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Sold Separately', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Position', 'easy-event-registration'); ?></th>
			</tr>
			</thead>
			<tbody class="list">
			<?php foreach ($tickets as $ticket) {
				?>
				<tr class="<?php echo apply_filters('eer_get_ticket_row_classes', $ticket); ?>"
				    data-id="<?php echo $ticket->id ?>">
					<td class="actions eer-tickets">
						<div class="eer-relative">
							<button class="page-title-action"><?php esc_html_e('Actions', 'easy-event-registration') ?></button>
							<?php $this->print_action_box($ticket->id); ?>
						</div>
					</td>
					<td><?php echo $ticket->title; ?></td>
					<td><?php echo EER()->event->get_event_title($ticket->event_id); ?></td>
					<td><?php echo EER()->currency->eer_prepare_price($ticket->event_id, $ticket->price); ?></td>
					<td><?php
						if ($ticket->has_levels) {
							foreach ($ticket->levels as $level_key => $level) {
								echo '<strong>' . $level['name'] . '</strong></br>';
								if ($ticket->is_solo) {
									echo $level['tickets'] . '</br>';
								} else {
									echo __('Leaders', 'easy-event-registration') . ': ' . $level['leaders'] . '</br>';
									echo __('Followers', 'easy-event-registration') . ': ' . $level['followers'] . '</br>';
								}
							}
						} elseif ($ticket->is_solo) {
							echo $ticket->max_tickets;
						} else {
							echo __('Leaders', 'easy-event-registration') . ': ' . $ticket->max_leaders; ?><br/><?php echo __('Followers', 'easy-event-registration') . ': ' . $ticket->max_followers;
						}
						?></td>
					<td><?php echo $ticket->max_per_order; ?></td>
					<td><?php echo $ticket->sold_separately; ?></td>
					<td><?php echo $ticket->position; ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php
	}


	private function print_action_box($id)
	{
		?>
		<ul class="eer-actions-box dropdown-menu" data-id="<?php echo $id; ?>">
			<li class="eer-action edit">
				<a href="<?php echo esc_url(add_query_arg('ticket_id', $id)) ?>">
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
		</ul>
		<?php
	}


	public static function get_row_classes($ticket)
	{
		$classes = [
			'eer-row',
			'eer-ticket'
		];

		if (intval($ticket->to_remove) === 1) {
			$classes[] = 'eer-to-remove';
		}

		return implode(' ', $classes);
	}

}

add_filter('eer_get_ticket_row_classes', ['EER_Subblock_Ticket_Table', 'get_row_classes']);