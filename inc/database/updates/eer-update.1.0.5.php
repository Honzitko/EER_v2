<?php
if (version_compare(get_site_option('eer_db_version', ''), '1.0.5', '<')) {
	global $wpdb;

	$wpdb->query("ALTER TABLE {$wpdb->prefix}eer_events_payments ADD COLUMN is_paying tinyint(1) NOT NULL DEFAULT 1;");
	$wpdb->query("ALTER TABLE {$wpdb->prefix}eer_events_payments ADD COLUMN is_voucher tinyint(1) NOT NULL DEFAULT 0;");
	$wpdb->query("ALTER TABLE {$wpdb->prefix}eer_events_payments ADD COLUMN note text DEFAULT NULL;");
}
