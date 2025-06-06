<?php
if (version_compare(get_site_option('eer_db_version', ''), '1.0.6', '<')) {
	global $wpdb;

	$wpdb->query("ALTER TABLE {$wpdb->prefix}eer_events_payments CHANGE `payed` `payment` float DEFAULT NULL;");
}
