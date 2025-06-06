<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Worker_Sold_Ticket {

	public function update_sold_ticket($sold_ticket_data) {
		if ($sold_ticket_data && isset($sold_ticket_data['sold_ticket_id'])) {
			$sold_ticket = EER()->sold_ticket->eer_get_sold_tickets_data($sold_ticket_data['sold_ticket_id']);
			if ($sold_ticket) {
				global $wpdb;

				$new_ticket_id = $sold_ticket_data['ticket_id'];
				$update_data    = [
					'dancing_with'      => $sold_ticket_data['dancing_with'],
					'dancing_with_name' => $sold_ticket_data['dancing_with_name'],
					'level_id' => null,
					'dancing_as' => EER_Enum_Dancing_As::SOLO,
				];

				if (isset($sold_ticket_data['dancing_as_' . $new_ticket_id])) {
					$update_data['dancing_as'] = intval($sold_ticket_data['dancing_as_' . $new_ticket_id]);
				}

				if (isset($sold_ticket_data['level_id_' . $new_ticket_id])) {
					$update_data['level_id'] = intval($sold_ticket_data['level_id_' . $new_ticket_id]);
				}

				if (isset($sold_ticket_data['partner_email']) && ($sold_ticket_data['partner_email'] !== '')) {
					$attendee = get_user_by('email', $sold_ticket_data['partner_email']);
					$update_data['partner_id'] = $attendee->ID;
				} else {
					$update_data['partner_id'] = null;
				}

				$update_data['ticket_id'] = intval($new_ticket_id);

				$wpdb->update($wpdb->prefix . 'eer_sold_tickets', $update_data, [
					'id' => $sold_ticket_data['sold_ticket_id']
				]);

				apply_filters('eer_update_user_payment', $sold_ticket->order_id);

				$old_ticket_id = intval($sold_ticket->ticket_id);

				if (($new_ticket_id !== null) && ($new_ticket_id !== $old_ticket_id)) {
					do_action('eer_recount_ticket_statistics', $new_ticket_id);
				}

				do_action('eer_recount_ticket_statistics', $old_ticket_id);
			}
		}
	}

}