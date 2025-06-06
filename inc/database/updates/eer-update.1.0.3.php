<?php
if (version_compare(get_site_option('eer_db_version', ''), '1.0.3', '<')) {
	global $wpdb;

	$wpdb->query($wpdb->prepare("ALTER TABLE {$wpdb->prefix}eer_tickets ADD COLUMN pairing_mode int DEFAULT %d;", [EER_Enum_Pairing_Mode::AUTOMATIC]));
}
