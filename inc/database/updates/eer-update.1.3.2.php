<?php
if (version_compare(get_site_option('eer_db_version'), '1.3.2', '<')) {
	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$wpdb->prefix}eer_event_managers (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			user_id bigint(20) NOT NULL,
			event_id bigint(20) NOT NULL,
			PRIMARY KEY id (id)
		) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}
