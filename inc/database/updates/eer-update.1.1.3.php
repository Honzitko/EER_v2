<?php
if (version_compare(get_site_option('eer_db_version'), '1.1.3', '<')) {
	global $wpdb;

	$results = $wpdb->get_results("SELECT id FROM {$wpdb->prefix}eer_ticket_summary GROUP BY ticket_id, level_id ORDER BY id");
	$ids     = [];

	foreach ($results as $key => $result) {
		$ids[] = $result->id;
	}

	$wpdb->query("DELETE FROM {$wpdb->prefix}eer_ticket_summary WHERE id NOT IN (" . implode(",", $ids) . ")");
}
