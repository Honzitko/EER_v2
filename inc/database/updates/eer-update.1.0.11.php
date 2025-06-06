<?php
if (version_compare(get_site_option('eer_db_version', ''), '1.0.11', '<')) {
	global $wpdb;

	$wpdb->query("ALTER TABLE {$wpdb->prefix}eer_sold_tickets ADD COLUMN cp_position int(10) DEFAULT NULL;");
}
