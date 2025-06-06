<?php
if (version_compare(get_site_option('eer_db_version'), '1.2.6', '<')) {
	global $wpdb;

	$wpdb->query("ALTER TABLE {$wpdb->prefix}eer_sold_tickets ADD COLUMN confirmation_datetime timestamp NULL DEFAULT NULL;");

	$wpdb->query("UPDATE {$wpdb->prefix}eer_sold_tickets SET confirmation_datetime = inserted_datetime WHERE status = 1");
}
