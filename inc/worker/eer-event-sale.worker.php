<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Worker_Event_Sale {

	private $worker_registration_couple;
	private $worker_registration_solo;


	public function __construct() {
		$this->worker_registration_couple = new EER_Event_Sale_Couple_Worker();
		$this->worker_registration_solo   = new EER_Event_Sale_Solo_Worker();
	}


	public function process_registration($data, $limit_validation = true) {
		$status      = false;
		$return_data = [];

		if ($this->eer_registration_form_validation($data)) {
			$status = $this->eer_complete_registration($data, $limit_validation);
		}

		if ($status) {
			$template_thank_you_page       = new EER_Template_Event_Sale_Thank_You_Page();
			$return_data['thank_you_text'] = $template_thank_you_page->print_content($data->event_id, $status);
		}

		global $eer_reg_errors;
		if (count($eer_reg_errors->get_error_messages()) !== 0) {
			$return_data['errors'] = $eer_reg_errors;
		}

		return $return_data;
	}


	private function eer_complete_registration($data, $limit_validation = true) {
		$eer_data = $this->eer_get_valid_data($data, $limit_validation);

		global $eer_reg_errors, $wpdb;
		$return_tickets = [];
		$order_id       = null;

		if (count($eer_reg_errors->get_error_messages()) === 0) {

			if (isset($eer_data['valid'])) {
				$user_id = EER()->user->eer_process_user_registration($data);

				$status = $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}eer_events_orders(user_id, event_id, order_info, unique_key, position)
							SELECT %d, %d, %s, %d, COALESCE(COUNT(event_id) + 1, 0) FROM  {$wpdb->prefix}eer_events_orders WHERE event_id = %d", [$user_id, $data->event_id, json_encode($data->user_info), EER()->order->generate_unique_order_key($data->event_id), $data->event_id]));

				$order_id = $wpdb->insert_id;
				do_action('eer_after_order_insert', $order_id, $data);
				foreach ($eer_data['valid'] as $ticket_id => $ticket) {
					$wpdb->query("START TRANSACTION;");
					$ticket_data = EER()->ticket->get_ticket_data($ticket_id);

					if (EER()->ticket->eer_is_solo($ticket_id, $ticket_data)) {
						$new_sold_tickets = $this->worker_registration_solo->process_registration($order_id, $ticket_id, $ticket_data, $ticket->number_of_tickets, $ticket, $data->event_id, $limit_validation);
					} else {
						$new_sold_tickets = $this->worker_registration_couple->process_registration($order_id, $user_id, $ticket_id, $ticket_data, $ticket, $data->event_id, $limit_validation);
					}

					if (isset($new_sold_tickets['paired'])) {
						$return_tickets['paired'][$ticket_id] = $new_sold_tickets['paired'][$ticket_id];
					}

					$wpdb->query("COMMIT;");

					$return_tickets['registered'][$ticket_id] = $ticket_data;
				}
			}

			if (isset($eer_data['full'])) {
				$return_tickets['full'] = $eer_data['full'];
			}

			EER()->email->eer_send_order_email($order_id, $return_tickets);


			if (!isset($data->user_info->eer_disable_confirmation_email) || (isset($data->user_info->eer_disable_confirmation_email) && !$data->user_info->eer_disable_confirmation_email)) {
				if (isset($return_tickets['paired']) && $return_tickets['paired']) {
					EER()->email->eer_send_order_confirmation_email($data->event_id, $return_tickets['paired']);
				}
			}

			return $return_tickets;
		}

		return false;
	}


	private function eer_registration_form_validation($data) {
		global $eer_reg_errors;
		$eer_reg_errors = new WP_Error;

		$event_data = EER()->event->get_event_data($data->event_id);

		if ($this->eer_check_required('name', $data->user_info)) {
			$eer_reg_errors->add('user_info.name', __('Name is required.', 'easy-event-registration'));
		}
		if ($this->eer_check_required('surname', $data->user_info)) {
			$eer_reg_errors->add('user_info.surname', __('Surname is required.', 'easy-event-registration'));
		}
		if ($this->eer_check_required('email', $data->user_info)) {
			$eer_reg_errors->add('user_info.email', __('Email is required.', 'easy-event-registration'));
		}
		if ((intval(EER()->event->eer_get_event_option($event_data, 'phone_required', -1)) === 1) && $this->eer_check_required('phone', $data->user_info)) {
			$eer_reg_errors->add('user_info.phone', __('Phone is required.', 'easy-event-registration'));
		}
		if ((intval(EER()->event->eer_get_event_option($event_data, 'country_required', -1)) === 1) && $this->eer_check_required('country', $data->user_info)) {
			$eer_reg_errors->add('user_info.country', __('Country is required.', 'easy-event-registration'));
		}

		if (!$data->tickets) {
			$eer_reg_errors->add('tickets.all.empty', __('At least one ticket is required to select.', 'easy-event-registration'));
		} else {
			if (isset($data->user_info->email) && email_exists($data->user_info->email)) {
				$user = get_user_by('email', $data->user_info->email);
				foreach ($data->tickets as $ticket_id => $ticket) {
					$ticket_data = EER()->ticket->get_ticket_data($ticket_id);
					if ((intval($ticket_data->once_per_user) === 1) && EER()->sold_ticket->eer_ticket_order_exists($ticket_id, $user->ID)) {
						$eer_reg_errors->add('tickets.' . $ticket_id . '.once_per_user', __('You already registered this ticket on given email. You can register this ticket just once per user.', 'easy-event-registration'));
					}
				}
			}
		}

		return count($eer_reg_errors->get_error_messages()) === 0;
	}


	private function eer_get_valid_data($data, $limit_validation = true) {
		global $eer_reg_errors;
		$return_data = [];
		$test_full   = false;

		foreach ($data->tickets as $ticket_id => $ticket) {
			if ($limit_validation) {
				if (EER()->ticket->eer_is_solo($ticket_id)) {
					$test_full = EER()->dancing_as->eer_is_solo_registration_enabled($ticket_id, ((isset($ticket->level_id) && ($ticket->level_id !== '')) ? $ticket->level_id : null));
				} else {
					if (EER()->dancing_as->eer_is_leader($ticket->dancing_as)) {
						$test_full = EER()->dancing_as->eer_is_leader_registration_enabled($ticket_id, ((isset($ticket->level_id) && ($ticket->level_id !== '')) ? $ticket->level_id : null));
					} else if (EER()->dancing_as->eer_is_follower($ticket->dancing_as)) {
						$test_full = EER()->dancing_as->eer_is_followers_registration_enabled($ticket_id, ((isset($ticket->level_id) && ($ticket->level_id !== '')) ? $ticket->level_id : null));
					}
				}

				if ($test_full) {
					$return_data['valid'][$ticket_id] = $ticket;
				} else {
					$return_data['full'][$ticket_id] = $ticket;
					$eer_reg_errors->add('tickets.' . $ticket_id . '.full', __('Sorry this ticket is already sold out.', 'easy-event-registration'));
				}
			} else {
				$return_data['valid'][$ticket_id] = $ticket;
			}
		}

		return $return_data;
	}


	private function eer_check_required($key, $data) {
		return !isset($data->$key) || trim($data->$key) == '';
	}
}