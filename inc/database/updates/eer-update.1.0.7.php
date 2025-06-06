<?php
if (version_compare(get_site_option('eer_db_version', ''), '1.0.7', '<')) {
	global $wpdb;

	$wpdb->query("ALTER TABLE {$wpdb->prefix}eer_events_orders ADD COLUMN position int(10) NOT NULL;");
	$wpdb->query("ALTER TABLE {$wpdb->prefix}eer_sold_tickets ADD COLUMN position int(10) NOT NULL;");
}
