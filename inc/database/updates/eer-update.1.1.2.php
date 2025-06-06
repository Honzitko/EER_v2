<?php
if (version_compare(get_site_option('eer_db_version'), '1.1.2', '<')) {
	global $wpdb;

	$wpdb->query("ALTER TABLE {$wpdb->prefix}eer_tickets ADD COLUMN to_remove tinyint(1) NOT NULL DEFAULT 0;");
}
