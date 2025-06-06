<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Worker_Ajax {

	public static function eer_remove_order_callback($order_id) {
		if ($order_id) {
			$order_id = intval($order_id);
			global $wpdb;

			$sold_tickets = EER()->sold_ticket->eer_get_sold_tickets_by_order($order_id);

			$wpdb->delete($wpdb->prefix . 'eer_events_payments', ['order_id' => $order_id]);
			$wpdb->update($wpdb->prefix . 'eer_sold_tickets', ['status' => EER_Enum_Sold_Ticket_Status::DELETED], ['order_id' => $order_id]);
			$wpdb->update($wpdb->prefix . 'eer_events_orders', ['status' => EER_Enum_Order_Status::DELETED], ['id' => $order_id]);

			foreach ($sold_tickets AS $key => $sold_ticket) {
				$update  = [];
				$summary = EER()->ticket_summary->eer_get_ticket_summary($sold_ticket->ticket_id, $sold_ticket->level_id);
				if ($sold_ticket->status == EER_Enum_Sold_Ticket_Status::CONFIRMED) {
					if (EER()->ticket->eer_is_solo($sold_ticket->ticket_id)) {
						$update['registered_tickets'] = $summary->registered_tickets - 1;
					} else if (EER()->dancing_as->eer_is_follower($sold_ticket->dancing_as)) {
						$update['registered_followers'] = $summary->registered_followers - 1;
					} else if (EER()->dancing_as->eer_is_leader($sold_ticket->dancing_as)) {
						$update['registered_leaders'] = $summary->registered_leaders - 1;
					}
				} else if ($sold_ticket->status == EER_Enum_Sold_Ticket_Status::WAITING) {
					if (EER()->dancing_as->eer_is_follower($sold_ticket->dancing_as)) {
						$update['waiting_followers'] = $summary->waiting_followers - 1;
					} else if (EER()->dancing_as->eer_is_leader($sold_ticket->dancing_as)) {
						$update['waiting_leaders'] = $summary->waiting_leaders - 1;
					}
				}

				if (count($update) > 0) {
					$wpdb->update($wpdb->prefix . 'eer_ticket_summary', $update, [
						'ticket_id' => $sold_ticket->ticket_id,
						'level_id'  => $sold_ticket->level_id,
					]);
				}
			}

			return 1;
		}

		return -1;
	}


	public static function eer_remove_order_forever_callback($order_id) {
		global $wpdb;

		if ($order_id !== null) {
			$wpdb->delete($wpdb->prefix . 'eer_events_orders', [
				'id'     => intval($order_id),
				'status' => EER_Enum_Order_Status::DELETED,
			]);

			return 1;
		}

		return -1;
	}


	public static function eer_remove_sold_ticket_callback($sold_ticket_id) {
		if ($sold_ticket_id) {
			global $wpdb;
			$worker_payment = new EER_Worker_Payment();

			$sold_ticket_data = EER()->sold_ticket->eer_get_sold_tickets_data($sold_ticket_id);

			$wpdb->update($wpdb->prefix . 'eer_sold_tickets', ['status' => EER_Enum_Sold_Ticket_Status::DELETED], ['id' => $sold_ticket_id]);
			$worker_payment->eer_update_user_payment($sold_ticket_data->order_id);

			if (!EER()->sold_ticket->eer_are_all_ordered_tickets_deleted($sold_ticket_data->order_id)) {
				$wpdb->update($wpdb->prefix . 'eer_events_orders', ['status' => EER_Enum_Order_Status::DELETED], ['id' => $sold_ticket_data->order_id]);
			}

			$update  = [];
			$summary = EER()->ticket_summary->eer_get_ticket_summary($sold_ticket_data->ticket_id, $sold_ticket_data->level_id);
			if ($sold_ticket_data->status == EER_Enum_Sold_Ticket_Status::CONFIRMED) {
				if (EER()->ticket->eer_is_solo($sold_ticket_data->ticket_id)) {
					$update['registered_tickets'] = $summary->registered_tickets - 1;
				} else if (EER()->dancing_as->eer_is_follower($sold_ticket_data->dancing_as)) {
					$update['registered_followers'] = $summary->registered_followers - 1;
				} else if (EER()->dancing_as->eer_is_leader($sold_ticket_data->dancing_as)) {
					$update['registered_leaders'] = $summary->registered_leaders - 1;
				}
			} else if ($sold_ticket_data->status == EER_Enum_Sold_Ticket_Status::WAITING) {
				if (EER()->dancing_as->eer_is_follower($sold_ticket_data->dancing_as)) {
					$update['waiting_followers'] = $summary->waiting_followers - 1;
				} else if (EER()->dancing_as->eer_is_leader($sold_ticket_data->dancing_as)) {
					$update['waiting_leaders'] = $summary->waiting_leaders - 1;
				}
			}

			if (count($update) > 0) {
				$wpdb->update($wpdb->prefix . 'eer_ticket_summary', $update, [
					'ticket_id' => intval($sold_ticket_data->ticket_id),
					'level_id'  => $sold_ticket_data->level_id,
				]);
			}

			return 1;
		}

		return -1;
	}


	public static function eer_remove_sold_ticket_forever_callback($sold_ticket) {
		global $wpdb;

		if ($sold_ticket) {
			$wpdb->delete($wpdb->prefix . 'eer_sold_tickets', [
				'id'     => $sold_ticket->id,
				'status' => EER_Enum_Sold_Ticket_Status::DELETED,
			]);

			if (EER()->sold_ticket->eer_count_sold_tickets($sold_ticket->order_id) == 0) {
				$wpdb->delete($wpdb->prefix . 'eer_events_orders', [
					'id' => $sold_ticket->order_id,
				]);
			}

			return 1;
		}

		return -1;
	}


	public function confirm_sold_ticket($sold_ticket_id) {
		if ($sold_ticket_id) {
			global $wpdb;
			$return_tickets = [];
			$worker_payment = new EER_Worker_Payment();

			$sold_ticket_data = EER()->sold_ticket->eer_get_sold_tickets_data($sold_ticket_id);
			$ticket_data = EER()->ticket->get_ticket_data($sold_ticket_data->ticket_id);
			$event_data = EER()->event->get_event_data($ticket_data->event_id);

			$wpdb->update($wpdb->prefix . 'eer_sold_tickets', [
				'status' => EER_Enum_Sold_Ticket_Status::CONFIRMED,
				'confirmation_datetime' => current_time('Y-m-d H:i:s'),
			], ['id' => $sold_ticket_id]);

			if (intval(EER()->event->eer_get_event_option($event_data, 'floating_price_enabled', -1)) !== -1) {
				$payment = apply_filters('eer_get_order_payment', $sold_ticket_data->order_id)->to_pay;

				if ($payment) {
					$return_tickets[$sold_ticket_data->ticket_id][$sold_ticket_data->id]['user']['previous_price'] = $payment;
				}
			}

			$worker_payment->eer_update_user_payment($sold_ticket_data->order_id);

			if (intval(EER()->event->eer_get_event_option($event_data, 'floating_price_enabled', -1)) !== -1) {
				$payment = apply_filters('eer_get_order_payment', $sold_ticket_data->order_id)->to_pay;

				if ($payment) {
					$return_tickets[$sold_ticket_data->ticket_id][$sold_ticket_data->id]['user']['actual_price'] = $payment;
				}
			}

			$return_tickets[$sold_ticket_data->ticket_id][$sold_ticket_data->id]['user']['sold_ticket_id'] = (int) $sold_ticket_id;

			$update  = [];
			$summary = EER()->ticket_summary->eer_get_ticket_summary($sold_ticket_data->ticket_id, $sold_ticket_data->level_id);
			$is_solo = EER()->ticket->eer_is_solo($sold_ticket_data->ticket_id);
			if ($sold_ticket_data->status == EER_Enum_Sold_Ticket_Status::DELETED) {
				if ($is_solo) {
					$update['registered_tickets'] = $summary->registered_tickets + 1;
				} else if (EER()->dancing_as->eer_is_follower($sold_ticket_data->dancing_as)) {
					$update['registered_followers'] = $summary->registered_followers + 1;
				} else if (EER()->dancing_as->eer_is_leader($sold_ticket_data->dancing_as)) {
					$update['registered_leaders'] = $summary->registered_leaders + 1;
				}
			} else if ($sold_ticket_data->status == EER_Enum_Sold_Ticket_Status::WAITING) {
				if ($is_solo) {
					$update['registered_tickets'] = $summary->registered_tickets + 1;
					$update['waiting_tickets']    = $summary->waiting_tickets - 1;
				} else if (EER()->dancing_as->eer_is_follower($sold_ticket_data->dancing_as)) {
					$update['registered_followers'] = $summary->registered_followers + 1;
					$update['waiting_followers']    = $summary->waiting_followers - 1;
				} else if (EER()->dancing_as->eer_is_leader($sold_ticket_data->dancing_as)) {
					$update['registered_leaders'] = $summary->registered_leaders + 1;
					$update['waiting_leaders']    = $summary->waiting_leaders - 1;
				}
			}

			if (!empty($update)) {
				$wpdb->update($wpdb->prefix . 'eer_ticket_summary', $update, [
					'ticket_id' => $sold_ticket_data->ticket_id,
					'level_id'  => $sold_ticket_data->level_id,
				]);
			}


			$order = EER()->order->eer_get_order($sold_ticket_data->order_id);

			//EER()->email->eer_send_ticket_confirmation_email($order->event_id, $sold_ticket_data->id);
			EER()->email->eer_send_order_confirmation_email($order->event_id, $return_tickets);

			return 1;
		}

		return -1;
	}


	public function save_payment($data) {
		if (isset($data['payment_type']) && isset($data['order_id'])) {
			global $wpdb;
			$price = null;

			if (($data['payment_type'] === 'paid') && isset($data['payment'])) {
				$price = (int) $data['payment'];
			}

			if (($price !== null) || in_array($data['payment_type'], ['not_paying', 'voucher'])) {
				$wpdb->update($wpdb->prefix . 'eer_events_payments', [
					'payment'    => $price,
					'is_paying'  => ($data['payment_type'] !== 'not_paying'),
					'is_voucher' => ($data['payment_type'] === 'voucher'),
					'note'       => isset($data['note']) ? esc_html($data['note']) : null,
					'status'     => EER_Enum_Payment::PAID
				], [
					'order_id' => (int) $data['order_id'],
				]);

				if (isset($data['eer_payment_email_confirmation']) && filter_var($data['eer_payment_email_confirmation'], FILTER_VALIDATE_BOOLEAN)) {
					EER()->email->eer_send_payment_confirmation_email((int) $data['order_id']);
				}
			}

			$payment                   = EER()->payment->eer_get_payment_by_order($data['order_id']);
			$payment->exp_status       = EER()->enum_payment->get_status($payment);
			$payment->exp_status_title = EER()->enum_payment->get_title($payment->exp_status);

			$order_data = EER()->order->eer_get_order($data['order_id']);

			$payment->payment_wc = EER()->currency->eer_prepare_price($order_data->event_id, ($payment->payment != null) && ($payment->payment != '') ? $payment->payment : 0);

			return $payment;
		}

		return false;
	}


	public static function eer_add_over_limit_callback($data) {
		$worker_event_sale = new EER_Worker_Event_Sale();

		return $worker_event_sale->process_registration(json_decode(stripslashes($data['order_data'])), false);
	}


	public static function eer_remove_ticket_callback($ticket_id) {
		$sold_tickets = EER()->sold_ticket->eer_get_sold_tickets_by_ticket($ticket_id);
		foreach ($sold_tickets as $key => $ticket) {
			apply_filters('eer_remove_sold_ticket', $ticket->id);
		}

		global $wpdb;
		$wpdb->update("{$wpdb->prefix}eer_tickets", ['to_remove' => 1], ['id' => $ticket_id]);

		return 1;
	}


	public static function eer_remove_ticket_forever_callback($ticket_id) {
		$sold_tickets = EER()->sold_ticket->eer_get_sold_tickets_by_ticket($ticket_id);
		foreach ($sold_tickets as $key => $ticket) {
			apply_filters('eer_remove_sold_ticket_forever', $ticket->id);
		}

		global $wpdb;
		$wpdb->delete("{$wpdb->prefix}eer_ticket_summary", ['ticket_id' => $ticket_id]);
		$wpdb->delete("{$wpdb->prefix}eer_tickets", ['id' => $ticket_id]);

		return 1;
	}


	public static function eer_duplicate_event_callback($event_id) {
		$tickets_data = EER()->ticket->get_tickets_by_event($event_id);

		global $wpdb;
		$wpdb->query("START TRANSACTION;");
		//Duplicate event
		$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}eer_events(title, sale_start, sale_end, event_settings) SELECT CONCAT(title, ' - COPY'), sale_start, sale_end, event_settings FROM {$wpdb->prefix}eer_events WHERE id = %d", [$event_id]));
		$new_event_id = $wpdb->insert_id;

		foreach ($tickets_data as $ticket_id => $ticket) {
			//Duplicate ticket
			$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}eer_tickets(event_id, title, price, is_solo, has_levels, max_tickets, max_leaders, max_followers, max_per_order, sold_separately, once_per_user, position, pairing_mode, ticket_settings, to_remove) SELECT %d, title, price, is_solo, has_levels, max_tickets, max_leaders, max_followers, max_per_order, sold_separately, once_per_user, position, pairing_mode, ticket_settings, to_remove FROM {$wpdb->prefix}eer_tickets WHERE id = %d", [$new_event_id, $ticket_id]));
			$new_ticket_id = $wpdb->insert_id;

			//Duplicate ticket summary
			$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}eer_ticket_summary(ticket_id, level_id, max_leaders, max_followers, max_tickets) SELECT %d, level_id, max_leaders, max_followers, max_tickets FROM {$wpdb->prefix}eer_ticket_summary WHERE ticket_id = %d", [$new_ticket_id, $ticket_id]));
		}

		$wpdb->query("COMMIT;");

		return 1;
	}

}

add_filter('eer_add_over_limit', ['EER_Worker_Ajax', 'eer_add_over_limit_callback']);
add_filter('eer_duplicate_event', ['EER_Worker_Ajax', 'eer_duplicate_event_callback']);
add_filter('eer_remove_ticket', ['EER_Worker_Ajax', 'eer_remove_ticket_callback']);
add_filter('eer_remove_ticket_forever', ['EER_Worker_Ajax', 'eer_remove_ticket_forever_callback']);
add_filter('eer_remove_order', ['EER_Worker_Ajax', 'eer_remove_order_callback']);
add_filter('eer_remove_order_forever', ['EER_Worker_Ajax', 'eer_remove_order_forever_callback']);
add_filter('eer_remove_sold_ticket', ['EER_Worker_Ajax', 'eer_remove_sold_ticket_callback']);
add_filter('eer_remove_sold_ticket_forever', ['EER_Worker_Ajax', 'eer_remove_sold_ticket_forever_callback']);