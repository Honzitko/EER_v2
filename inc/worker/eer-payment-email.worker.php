<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Worker_Payment_Email
{

	public function eer_update_payment_email_timestamp($payment_id) {
		global $wpdb;

		$wpdb->update($wpdb->prefix . 'eer_events_payments', [
			'confirmation_email_sent_timestamp' => current_time('Y-m-d H:i:s')
		], [
			'id' => $payment_id
		]);
	}

}
