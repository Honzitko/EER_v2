<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Ajax {

	public static function eer_process_order_callback() {
		$worker_event_sale = new EER_Worker_Event_Sale();
		wp_send_json($worker_event_sale->process_registration(json_decode(stripslashes($_POST['order_data']))), 200);
	}


	public static function eer_remove_order_callback() {
		if (isset($_POST['order_id'])) {
			wp_send_json(apply_filters('eer_remove_order', $_POST['order_id']));
			wp_die();
		}
		echo -1;
		wp_die();
	}


	public static function eer_remove_order_forever_callback() {
		if (isset($_POST['order_id'])) {
			wp_send_json(apply_filters('eer_remove_order_forever', $_POST['order_id']));
			wp_die();
		}
		echo -1;
		wp_die();
	}


	public static function eer_remove_sold_ticket_callback() {
		if (isset($_POST['sold_ticket_id'])) {
			wp_send_json(apply_filters('eer_remove_sold_ticket', $_POST['sold_ticket_id']));
			wp_die();
		}
		echo -1;
		wp_die();
	}


	public static function eer_confirm_sold_ticket_callback() {
		if (isset($_POST['sold_ticket_id'])) {
			$worker_ajax = new EER_Worker_Ajax();
			echo $worker_ajax->confirm_sold_ticket($_POST['sold_ticket_id']);
			wp_die();
		}
		echo -1;
		wp_die();
	}


	public static function eer_edit_order_callback() {
		$data = $_POST;
		if (isset($data['order_id'])) {
			echo 1;
			wp_die();
		}
		echo -1;
		wp_die();
	}


	public static function eer_edit_sold_ticket_callback() {
		$data = $_POST;
		if (isset($data['sold_order_id'])) {
			echo 1;
			wp_die();
		}
		echo -1;
		wp_die();
	}


	public static function eer_save_payment_callback() {
		$data = $_POST;
		if (isset($data['order_id'])) {
			$worker_ajax = new EER_Worker_Ajax();
			wp_send_json($worker_ajax->save_payment($_POST));
			wp_die();
		}
		wp_send_json(false);
		wp_die();
	}


	public function eer_remove_sold_ticket_forever_callback() {
		if (isset($_POST['sold_ticket_id'])) {
			// get registration
			$sold_ticket = EER()->sold_ticket->eer_get_sold_tickets_data($_POST['sold_ticket_id']);

			if ($sold_ticket && ($sold_ticket->status == EER_Enum_Sold_Ticket_Status::DELETED)) {
				wp_send_json(apply_filters('eer_remove_sold_ticket_forever', $sold_ticket));
				wp_die();
			}
		}
		echo -1;
		wp_die();
	}


	public static function eer_add_ticket_registration_callback() {
		if (isset($_POST['order_data'])) {
			wp_send_json(apply_filters('eer_add_over_limit', $_POST));
		}
		echo -1;
		wp_die();
	}


	public static function eer_remove_ticket_callback() {
		if (isset($_POST['ticket_id'])) {
			wp_send_json(apply_filters('eer_remove_ticket', $_POST['ticket_id']));
		}
		echo -1;
		wp_die();
	}


	public static function eer_remove_ticket_forever_callback() {
		if (isset($_POST['ticket_id'])) {
			wp_send_json(apply_filters('eer_remove_ticket_forever', $_POST['ticket_id']));
		}
		echo -1;
		wp_die();
	}


	public static function eer_tinymce_load_events_callback() {
		wp_send_json(EER()->event->load_tinymce_events());
		wp_die();
	}


	public static function eer_duplicate_event_callback() {
		if (isset($_POST['event_id'])) {
			wp_send_json(apply_filters('eer_duplicate_event', $_POST['event_id']));
		}
		echo -1;
		wp_die();
	}

}

//Frontend
add_action('wp_ajax_eer_process_order', ['EER_Ajax', 'eer_process_order_callback']);
add_action('wp_ajax_nopriv_eer_process_order', ['EER_Ajax', 'eer_process_order_callback']);

//Backend
add_action('wp_ajax_eer_add_ticket_registration', ['EER_Ajax', 'eer_add_ticket_registration_callback']);
add_action('wp_ajax_eer_confirm_sold_ticket', ['EER_Ajax', 'eer_confirm_sold_ticket_callback']);
add_action('wp_ajax_eer_duplicate_event', ['EER_Ajax', 'eer_duplicate_event_callback']);
add_action('wp_ajax_eer_remove_order', ['EER_Ajax', 'eer_remove_order_callback']);
add_action('wp_ajax_eer_remove_order_forever', ['EER_Ajax', 'eer_remove_order_forever_callback']);
add_action('wp_ajax_eer_remove_sold_ticket', ['EER_Ajax', 'eer_remove_sold_ticket_callback']);
add_action('wp_ajax_eer_remove_sold_ticket_forever', ['EER_Ajax', 'eer_remove_sold_ticket_forever_callback']);
add_action('wp_ajax_eer_remove_ticket', ['EER_Ajax', 'eer_remove_ticket_callback']);
add_action('wp_ajax_eer_remove_ticket_forever', ['EER_Ajax', 'eer_remove_ticket_forever_callback']);
add_action('wp_ajax_eer_save_payment', ['EER_Ajax', 'eer_save_payment_callback']);
add_action('wp_ajax_eer_tinymce_load_events', ['EER_Ajax', 'eer_tinymce_load_events_callback']);



