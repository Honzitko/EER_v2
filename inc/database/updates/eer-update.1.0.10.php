<?php
if (version_compare(get_site_option('eer_db_version', ''), '1.0.10', '<')) {
	global $wpdb;

	$wpdb->query("ALTER TABLE {$wpdb->prefix}eer_sold_tickets ADD COLUMN dancing_with_name varchar(100);");
}
