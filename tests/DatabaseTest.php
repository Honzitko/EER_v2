<?php
use PHPUnit\Framework\TestCase;

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

if (!function_exists('add_action')) {
    function add_action($tag, $function_to_add) {
        // no-op for tests
    }
}

require_once __DIR__ . '/../inc/database/eer-database.php';

class DatabaseTest extends TestCase
{
    public function test_events_table_allows_null_sale_dates()
    {
        global $wpdb;
        $wpdb = new class {
            public $prefix = '';
            public function get_charset_collate() { return ''; }
        };

        $GLOBALS['last_sql'] = '';
        EER_Database::create_tables();
        $this->assertStringContainsString('sale_start timestamp NULL', $GLOBALS['last_sql']);
        $this->assertStringContainsString('sale_end timestamp NULL', $GLOBALS['last_sql']);
    }
}

