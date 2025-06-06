<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Event_Sale_Couple_Worker {

	public function process_registration($order_id, $user_id, $ticket_id, $ticket_data, $order_data, $event_id, $limit_validation = true) {
		global $wpdb;
		$worker_payment = new EER_Worker_Payment();

		$return_tickets    = [];
		$email             = get_user_by('ID', $user_id)->user_email;
		$dancing_as        = (isset($order_data->dancing_as) && ($order_data->dancing_as !== '')) ? intval($order_data->dancing_as) : null;
		$dancing_with_name = (isset($order_data->dancing_with_name) && ($order_data->dancing_with_name !== '')) ? trim($order_data->dancing_with_name) : null;
		$dancing_with      = (isset($order_data->dancing_with) && ($order_data->dancing_with !== '')) ? filter_var(strtolower(trim($order_data->dancing_with)), FILTER_SANITIZE_EMAIL) : null;
		$level_id          = (isset($order_data->level_id) && ($order_data->level_id !== '')) ? intval($order_data->level_id) : null;
		$summary           = EER()->ticket_summary->eer_get_ticket_summary($ticket_id, $level_id);
		$event_data = EER()->event->get_event_data($event_id);

		$leaders_enabled   = EER()->dancing_as->eer_is_leader_registration_enabled($ticket_id, $level_id);
		$followers_enabled = EER()->dancing_as->eer_is_followers_registration_enabled($ticket_id, $level_id);

		if (($limit_validation && ((EER()->dancing_as->eer_is_leader($dancing_as) && $leaders_enabled) || (EER()->dancing_as->eer_is_follower($dancing_as) && $followers_enabled))) || !$limit_validation) {
			if ($level_id !== null) {
				$status = $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}eer_sold_tickets(order_id, ticket_id, level_id, unique_key, dancing_as, dancing_with, dancing_with_name, status, position)
							SELECT %d, %d, " . ($level_id === null ? "null" : $level_id) . ", %d, %d, %s, %s, %d, COALESCE(COUNT(event_id) + 1, 0)  FROM  {$wpdb->prefix}eer_sold_tickets AS st JOIN {$wpdb->prefix}eer_events_orders AS eo ON eo.id = st.order_id WHERE eo.event_id = %d AND st.ticket_id = %d", [
					$order_id,
					$ticket_id,
					EER()->sold_ticket->generate_unique_key($event_id),
					$dancing_as,
					$dancing_with,
					$dancing_with_name,
					EER_Enum_Sold_Ticket_Status::WAITING,
					$event_id,
					$ticket_id
				]));
			} else {
				$status = $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}eer_sold_tickets(order_id, ticket_id, level_id, unique_key, dancing_as, dancing_with, dancing_with_name, status, position)
							SELECT %d, %d, NULL, %d, %d, %s, %s, %d, COALESCE(COUNT(event_id) + 1, 0)  FROM  {$wpdb->prefix}eer_sold_tickets AS st JOIN {$wpdb->prefix}eer_events_orders AS eo ON eo.id = st.order_id WHERE eo.event_id = %d AND st.ticket_id = %d", [
					$order_id,
					$ticket_id,
					EER()->sold_ticket->generate_unique_key($event_id),
					$dancing_as,
					$dancing_with,
					$dancing_with_name,
					EER_Enum_Sold_Ticket_Status::WAITING,
					$event_id,
					$ticket_id
				]));
			}

			if ($status !== false) {
				$sold_ticket_id = $wpdb->insert_id;
				$partner_reg    = null;

				if ($leaders_enabled && $followers_enabled && EER()->pairing_mode->is_pairing_enabled($ticket_data->pairing_mode)) {
					if ($dancing_with == null || $dancing_with === '') {
						$dancing_with = '';
					}

					if ($level_id !== null) {
						$partner_reg = $wpdb->get_results($wpdb->prepare("SELECT 
	st.id, 
	eo.user_id, 
	CASE WHEN (st.dancing_with IS NOT NULL AND %s NOT LIKE '' AND st.dancing_with LIKE %s AND u.user_email LIKE %s) THEN 1 WHEN
	(st.dancing_with IS NOT NULL AND st.dancing_with LIKE %s) THEN 2 WHEN (%s NOT LIKE '' AND u.user_email LIKE %s) THEN 2 WHEN
	((st.dancing_with IS NULL OR st.dancing_with LIKE '') AND %s LIKE '') THEN 3 END AS ord 
  FROM {$wpdb->prefix}eer_sold_tickets AS st
  JOIN {$wpdb->prefix}eer_events_orders AS eo ON st.order_id = eo.id
  JOIN {$wpdb->users} AS u ON eo.user_id = u.id 
 WHERE st.id != %d 
   AND st.ticket_id = %d
   AND st.level_id = %d
   AND st.partner_id IS NULL
   AND st.status = %d
   AND st.dancing_as != %d
   AND ((st.dancing_with IS NOT NULL AND %s NOT LIKE '' AND st.dancing_with LIKE %s AND u.user_email LIKE %s) OR (st.dancing_with IS NOT NULL AND st.dancing_with LIKE %s) OR (%s NOT LIKE '' AND u.user_email LIKE %s) OR ((st.dancing_with IS NULL OR st.dancing_with LIKE '') AND %s LIKE '')) ORDER BY ord, st.inserted_datetime LIMIT 1;", [
							$dancing_with,
							$email,
							$dancing_with,
							$email,
							$dancing_with,
							$dancing_with,
							$dancing_with,
							$sold_ticket_id,
							$ticket_id,
							$level_id,
							EER_Enum_Sold_Ticket_Status::WAITING,
							$dancing_as,
							$dancing_with,
							$email,
							$dancing_with,
							$email,
							$dancing_with,
							$dancing_with,
							$dancing_with
						]));
					} else {
						$partner_reg = $wpdb->get_results($wpdb->prepare("SELECT 
	st.id, 
	eo.user_id, 
	CASE WHEN (st.dancing_with IS NOT NULL AND %s NOT LIKE '' AND st.dancing_with LIKE %s AND u.user_email LIKE %s) THEN 1 WHEN
	(st.dancing_with IS NOT NULL AND st.dancing_with LIKE %s) THEN 2 WHEN (%s NOT LIKE '' AND u.user_email LIKE %s) THEN 2 WHEN
	((st.dancing_with IS NULL OR st.dancing_with LIKE '') AND %s LIKE '') THEN 3 END AS ord 
  FROM {$wpdb->prefix}eer_sold_tickets AS st
  JOIN {$wpdb->prefix}eer_events_orders AS eo ON st.order_id = eo.id
  JOIN {$wpdb->users} AS u ON eo.user_id = u.id 
 WHERE st.id != %d 
   AND st.ticket_id = %d
   AND st.level_id IS NULL
   AND st.partner_id IS NULL
   AND st.status = %d
   AND st.dancing_as != %d
   AND ((st.dancing_with IS NOT NULL AND %s NOT LIKE '' AND st.dancing_with LIKE %s AND u.user_email LIKE %s) OR (st.dancing_with IS NOT NULL AND st.dancing_with LIKE %s) OR (%s NOT LIKE '' AND u.user_email LIKE %s) OR ((st.dancing_with IS NULL OR st.dancing_with LIKE '') AND %s LIKE '')) ORDER BY ord, st.inserted_datetime LIMIT 1;", [
							$dancing_with,
							$email,
							$dancing_with,
							$email,
							$dancing_with,
							$dancing_with,
							$dancing_with,
							$sold_ticket_id,
							$ticket_id,
							EER_Enum_Sold_Ticket_Status::WAITING,
							$dancing_as,
							$dancing_with,
							$email,
							$dancing_with,
							$email,
							$dancing_with,
							$dancing_with,
							$dancing_with
						]));
					}
				}

				if ($partner_reg && $partner_reg[0]->id && $partner_reg[0]->user_id) {
					if ($dancing_with == null || $dancing_with === '') {
						$wpdb->update($wpdb->prefix . 'eer_sold_tickets', [
							'partner_id' => $partner_reg[0]->user_id,
							'status'     => EER_Enum_Sold_Ticket_Status::CONFIRMED,
							'confirmation_datetime' => current_time('Y-m-d H:i:s'),
						], [
							'id' => $sold_ticket_id
						]);

						$wpdb->update($wpdb->prefix . 'eer_sold_tickets', [
							'partner_id' => $user_id,
							'status'     => EER_Enum_Sold_Ticket_Status::CONFIRMED,
							'confirmation_datetime' => current_time('Y-m-d H:i:s'),
						], [
							'id' => $partner_reg[0]->id
						]);
					} else {
						$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_sold_tickets AS est
						JOIN (SELECT COALESCE(MAX(COALESCE(cp_position, 0)), 0) + 1 AS np 
						FROM {$wpdb->prefix}eer_sold_tickets WHERE ticket_id = %d 
						AND dancing_with != '' AND status = %d AND cp_position IS NOT NULL) AS pos
  SET est.cp_position = pos.np, partner_id = %d, status = %d, confirmation_datetime = %s
  WHERE est.id = %d", [$ticket_id, EER_Enum_Sold_Ticket_Status::CONFIRMED, $partner_reg[0]->user_id, EER_Enum_Sold_Ticket_Status::CONFIRMED, current_time('Y-m-d H:i:s'), $sold_ticket_id]));

						$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}eer_sold_tickets AS est
						JOIN (SELECT COALESCE(MAX(COALESCE(cp_position,0)), 0) + 1 AS np 
						FROM {$wpdb->prefix}eer_sold_tickets WHERE ticket_id = %d 
						AND dancing_with != '' AND status = %d AND cp_position IS NOT NULL) AS pos
  SET est.cp_position = pos.np, partner_id = %d, status = %d, confirmation_datetime = %s
  WHERE est.id = %d", [$ticket_id, EER_Enum_Sold_Ticket_Status::CONFIRMED, $user_id, EER_Enum_Sold_Ticket_Status::CONFIRMED, current_time('Y-m-d H:i:s'), $partner_reg[0]->id]));
					}


					EER()->ticket_summary->eer_update_ticket_summary($ticket_id, $level_id, [
						'registered_leaders'   => intval($summary->registered_leaders) + 1,
						'registered_followers' => intval($summary->registered_followers) + 1,
						'waiting_leaders'      => (!EER()->dancing_as->eer_is_leader($dancing_as) ? intval($summary->waiting_leaders) - 1 : intval($summary->waiting_leaders)),
						'waiting_followers'    => (!EER()->dancing_as->eer_is_follower($dancing_as) ? intval($summary->waiting_followers) - 1 : intval($summary->waiting_followers)),
					]);

					$sold_ticket = EER()->sold_ticket->eer_get_sold_tickets_data($partner_reg[0]->id);

					if (intval(EER()->event->eer_get_event_option($event_data, 'floating_price_enabled', -1)) !== -1) {
						$payment = apply_filters('eer_get_order_payment', $order_id)->to_pay;

						if ($payment) {
							$return_tickets['paired'][$ticket_id][$sold_ticket_id]['user']['previous_price'] = $payment;
						}

						$payment = apply_filters('eer_get_order_payment', $sold_ticket->order_id)->to_pay;

						if ($payment) {
							$return_tickets['paired'][$ticket_id][$sold_ticket_id]['partner']['previous_price'] = $payment;
						}
					}

					$worker_payment->eer_update_user_payment($order_id);
					$worker_payment->eer_update_user_payment($sold_ticket->order_id);

					if (intval(EER()->event->eer_get_event_option($event_data, 'floating_price_enabled', -1)) !== -1) {
						$payment = apply_filters('eer_get_order_payment', $order_id)->to_pay;

						if ($payment) {
							$return_tickets['paired'][$ticket_id][$sold_ticket_id]['user']['actual_price'] = $payment;
						}

						$payment = apply_filters('eer_get_order_payment', $sold_ticket->order_id)->to_pay;

						if ($payment) {
							$return_tickets['paired'][$ticket_id][$sold_ticket_id]['partner']['actual_price'] = $payment;
						}
					}

					$return_tickets['paired'][$ticket_id][$sold_ticket_id]['user']['sold_ticket_id'] = (int) $sold_ticket_id;
					$return_tickets['paired'][$ticket_id][$sold_ticket_id]['partner']['sold_ticket_id'] = (int) $partner_reg[0]->id;
				} else if (EER()->pairing_mode->is_auto_confirmation_enabled($ticket_data->pairing_mode)) {
					// Confirm all registrations until course is full
					$wpdb->update($wpdb->prefix . 'eer_sold_tickets', [
						'status' => EER_Enum_Sold_Ticket_Status::CONFIRMED
					], [
						'id' => $sold_ticket_id
					]);

					if (EER()->dancing_as->eer_is_leader($dancing_as)) {
						EER()->ticket_summary->eer_update_ticket_summary($ticket_id, $level_id, [
							'registered_leaders' => intval($summary->registered_leaders) + 1,
						]);
					} else {
						EER()->ticket_summary->eer_update_ticket_summary($ticket_id, $level_id, [
							'registered_followers' => intval($summary->registered_followers) + 1,
						]);
					}


					if (intval(EER()->event->eer_get_event_option($event_data, 'floating_price_enabled', -1)) !== -1) {
						$payment = apply_filters('eer_get_order_payment', $order_id)->to_pay;

						if ($payment) {
							$return_tickets['paired'][$ticket_id][$sold_ticket_id]['user']['previous_price'] = $payment;
						}
					}

					$worker_payment->eer_update_user_payment($order_id);

					if (intval(EER()->event->eer_get_event_option($event_data, 'floating_price_enabled', -1)) !== -1) {
						$payment = apply_filters('eer_get_order_payment', $order_id)->to_pay;

						if ($payment) {
							$return_tickets['paired'][$ticket_id][$sold_ticket_id]['user']['actual_price'] = $payment;
						}
					}

					$return_tickets['paired'][$ticket_id][$sold_ticket_id]['user']['sold_ticket_id'] = (int) $sold_ticket_id;;
				} else {
					if (EER()->dancing_as->eer_is_leader($dancing_as)) {
						EER()->ticket_summary->eer_update_ticket_summary($ticket_id, $level_id, [
							'waiting_leaders' => intval($summary->waiting_leaders) + 1,
						]);
					} else {
						EER()->ticket_summary->eer_update_ticket_summary($ticket_id, $level_id, [
							'waiting_followers' => intval($summary->waiting_followers) + 1,
						]);
					}
				}
			}

		}

		return $return_tickets;


	}


}