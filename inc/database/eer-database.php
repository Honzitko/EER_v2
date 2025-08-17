<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Database
{

	public static function eer_db_check()
	{
		if (get_site_option('eer_db_version') == NULL) {
			self::database_install();
		} elseif (version_compare(get_site_option('eer_db_version', ''), EER_VERSION, '<')) {
			self::database_update();
		}
	}


	private static function database_install()
	{
		self::create_tables();

		update_option('eer_db_version', EER_VERSION);
	}


	private static function database_update()
	{
		include_once 'updates/eer-update.1.0.3.php';
		include_once 'updates/eer-update.1.0.4.php';
		include_once 'updates/eer-update.1.0.5.php';
		include_once 'updates/eer-update.1.0.6.php';
		include_once 'updates/eer-update.1.0.7.php';
		include_once 'updates/eer-update.1.0.10.php';
		include_once 'updates/eer-update.1.0.11.php';
		include_once 'updates/eer-update.1.0.12.php';
		include_once 'updates/eer-update.1.1.0.php';
		include_once 'updates/eer-update.1.1.2.php';
		include_once 'updates/eer-update.1.1.3.php';
		include_once 'updates/eer-update.1.2.3.php';
                include_once 'updates/eer-update.1.2.6.php';
                include_once 'updates/eer-update.1.3.2.php';
                include_once 'updates/eer-update.1.3.3.php';
                update_option('eer_db_version', EER_VERSION);
        }

	public static function create_tables()
	{
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

                $sql = "CREATE TABLE {$wpdb->prefix}eer_events (
                        id bigint(20) NOT NULL AUTO_INCREMENT,
                        is_passed boolean NOT NULL DEFAULT FALSE,
                        title mediumtext NOT NULL,
                        sale_start timestamp NULL DEFAULT NULL,
                        sale_end timestamp NULL DEFAULT NULL,
                        event_settings longtext,
                        PRIMARY KEY id (id)
                ) $charset_collate;";

		$sql .= "CREATE TABLE {$wpdb->prefix}eer_tickets (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			event_id bigint(20) NOT NULL,
			title mediumtext NOT NULL,
			price int(10) NOT NULL DEFAULT 0,
			is_solo tinyint(1) NOT NULL DEFAULT 1,
			has_levels tinyint(1) NOT NULL DEFAULT 0,
			max_tickets int(10) NOT NULL DEFAULT 0,
			max_leaders int(10),
			max_followers int(10),
			max_per_order int(10) NOT NULL DEFAULT 0,
			sold_separately boolean NOT NULL DEFAULT FALSE,
			once_per_user boolean NOT NULL DEFAULT FALSE,
			position int(10) NOT NULL DEFAULT FALSE,
			pairing_mode int DEFAULT 1,
			ticket_settings longtext,
			to_remove tinyint(1) NOT NULL DEFAULT 0,
			PRIMARY KEY id (id)
		) $charset_collate;";

		$sql .= "CREATE TABLE {$wpdb->prefix}eer_events_orders (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			user_id bigint(20) NOT NULL,
			event_id bigint(20) NOT NULL,
			payment_type int NOT NULL,
			unique_key varchar(10) NOT NULL,
			order_info text NOT NULL,
			status int NOT NULL DEFAULT 0,
			inserted_datetime timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
			position int(10) NOT NULL,
			PRIMARY KEY id (id)
		) $charset_collate;";

		$sql .= "CREATE TABLE {$wpdb->prefix}eer_events_payments (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			order_id bigint(20) NOT NULL,
			payment_type int DEFAULT NULL,
			to_pay float NOT NULL,
			payment float DEFAULT NULL,
			is_paying tinyint(1) NOT NULL DEFAULT 1,
			is_voucher tinyint(1) NOT NULL DEFAULT 0,
			note text DEFAULT NULL,
			status int DEFAULT 0,
			not_paying int(1) NOT NULL DEFAULT 0,
			discount_info text DEFAULT NULL,
			inserted_datetime timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
			confirmation_email_sent_timestamp timestamp NULL DEFAULT NULL,
			PRIMARY KEY id (id)
		) $charset_collate;";

		$sql .= "CREATE TABLE {$wpdb->prefix}eer_sold_tickets (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			ticket_id bigint(20) NOT NULL,
			order_id bigint(20) NOT NULL,
			level_id int(10) DEFAULT NULL,
			unique_key varchar(10) NOT NULL,
			status int NOT NULL DEFAULT 0,
			dancing_as tinyint(1) NOT NULL,
			dancing_with text,
			dancing_with_name varchar(100),
			partner_id bigint(20) DEFAULT NULL,
			inserted_datetime timestamp DEFAULT CURRENT_TIMESTAMP NOT NULL,
			confirmation_datetime timestamp NULL DEFAULT NULL,
			position int(10) NOT NULL,
			cp_position int(10) DEFAULT NULL,
			PRIMARY KEY id (id)
		) $charset_collate;";

		$sql .= "CREATE TABLE {$wpdb->prefix}eer_ticket_summary (
			id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			ticket_id bigint(20) UNSIGNED NOT NULL,
			level_id int(10) DEFAULT NULL,
			max_leaders int(10) DEFAULT 0 NOT NULL,
			max_followers int(10) DEFAULT 0 NOT NULL,
			max_tickets int(10) DEFAULT 0 NOT NULL,
			registered_leaders int(10) DEFAULT 0 NOT NULL,
			registered_followers int(10) DEFAULT 0 NOT NULL,
			registered_tickets int(10) DEFAULT 0 NOT NULL,
			waiting_leaders int(10) DEFAULT 0 NOT NULL,
			waiting_followers int(10) DEFAULT 0 NOT NULL,
			waiting_tickets int(10) DEFAULT 0 NOT NULL,
			PRIMARY KEY id (id)
		) $charset_collate;";

		$sql .= "CREATE TABLE {$wpdb->prefix}eer_event_managers (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			user_id bigint(20) NOT NULL,
			event_id bigint(20) NOT NULL,
			PRIMARY KEY id (id)
		) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
}

add_action('plugins_loaded', ['EER_Database', 'eer_db_check']);
