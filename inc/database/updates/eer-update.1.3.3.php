<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;
$wpdb->query("ALTER TABLE {$wpdb->prefix}eer_events MODIFY sale_start timestamp NULL DEFAULT NULL, MODIFY sale_end timestamp NULL DEFAULT NULL;");
