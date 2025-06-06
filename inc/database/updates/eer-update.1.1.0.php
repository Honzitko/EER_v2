<?php
if (version_compare(get_site_option('eer_db_version'), '1.1.0', '<')) {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$wpdb->prefix}eer_events_payments (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			order_id bigint(20) NOT NULL,
			payment_type int DEFAULT NULL,
			to_pay float NOT NULL,
			payment float DEFAULT NULL,
			is_paying tinyint(1) NOT NULL DEFAULT 1,
			is_voucher tinyint(1) NOT NULL DEFAULT 0,
			note text DEFAULT NULL,
			status int DEFAULT 0,
			not_paying int(1) NOT NULL DEFAULT 0, 
			inserted_datetime timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
			confirmation_email_sent_timestamp timestamp NULL DEFAULT NULL,
			PRIMARY KEY id (id),
			FOREIGN KEY (order_id) REFERENCES {$wpdb->prefix}eer_events_orders(id) ON DELETE CASCADE
		) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	$worker = new EER_Worker_Payment();
	foreach (EER()->event->load_events_without_data() as $event_key => $event) {
		foreach (EER()->order->eer_get_orders_by_event($event->id) as $order_key => $order) {
			$worker->eer_update_user_payment($order->id);
		}
	}
}
