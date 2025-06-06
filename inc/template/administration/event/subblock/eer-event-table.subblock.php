<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EER_Subblock_Event_Table {

	public function print_block() {
		$events = EER()->event->load_events();
		?>
		<h1 class="wp-heading-inline"><?php _e( 'Events', 'easy-event-registration' ); ?></h1>
		<?php if ( current_user_can( 'eer_event_add' ) ) { ?>
			<a href="<?php echo esc_url( add_query_arg( 'event_id', - 1 ) ) ?>" class="eer-add-new page-title-action"><?php _e( 'Add New Event', 'easy-event-registration' ); ?></a>
		<?php } ?>
		<table id="datatable" class="table table-default table-bordered eer-datatable">
			<colgroup>
				<col width="10">
				<col width="100">
				<col width="180">
				<col width="500">
			</colgroup>
			<thead>
			<tr>
				<th class="filter-disabled no-sort" data-key="eer_actions"><?php _e( 'ID', 'easy-event-registration' ); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_actions"><?php _e( 'Actions', 'easy-event-registration' ); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e( 'Event Title', 'easy-event-registration' ); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e( 'Ticket Sale Start', 'easy-event-registration' ); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e( 'Ticket Sale Ends', 'easy-event-registration' ); ?></th>
			</tr>
			</thead>
			<tbody class="list">
			<?php foreach ( $events as $event ) {

				?>
				<tr class="<?php echo apply_filters( 'eer_get_event_row_classes', $event ); ?>"
				    data-id="<?php echo $event->id ?>">
					<td><?php echo $event->id; ?></td>
					<td class="actions eer-events">
						<div class="eer-relative">
							<button class="page-title-action"><?php esc_html_e( 'Actions', 'easy-event-registration' ) ?></button>
							<?php $this->print_action_box( $event->id ); ?>
						</div>
					</td>
					<td><?php echo $event->title; ?></td>
					<td><?php echo $event->sale_start; ?></td>
					<td><?php echo $event->sale_end; ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php
	}


	private function print_action_box( $id ) {
		?>
		<ul class="eer-actions-box dropdown-menu" data-id="<?php echo $id; ?>">
			<li class="eer-action edit">
				<a href="<?php echo esc_url( add_query_arg( 'event_id', $id ) ) ?>">
					<span class="dashicons dashicons-edit"></span>
					<span><?php _e( 'Edit', 'easy-event-registration' ); ?></span>
				</a>
			</li>
			<?php if ( current_user_can( 'eer_event_add' ) ) { ?>
				<li class="eer-action duplicate">
					<a href="javascript:;">
						<span class="dashicons dashicons-admin-page"></span>
						<span><?php _e( 'Duplicate', 'easy-event-registration' ); ?></span>
					</a>
				</li>
			<?php } ?>
		</ul>
		<?php
	}


	public static function get_columns() {
		echo implode( ';', array_keys( (array) EER()->event->get_fields() ) );
	}


	public static function get_row_classes( $event ) {
		$classes = [
			'eer-row',
			'eer-event'
		];

		if ( $event->is_passed ) {
			$classes[] = 'passed';
		}

		return implode( ' ', $classes );
	}

}

add_filter( 'eer_get_event_row_classes', [ 'EER_Subblock_Event_Table', 'get_row_classes' ] );