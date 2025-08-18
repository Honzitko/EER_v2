<?php
if (!defined('ABSPATH')) {
    exit;
}

class EER_Debug
{
    private static $enabled = null;
    private static $log_file = null;

    public static function init($enabled = null, $log_file = null)
    {
        self::$enabled = $enabled ?? (defined('EER_DEBUG') ? EER_DEBUG : false);
        self::$log_file = $log_file ?? __DIR__ . '/../..' . '/eer-debug.log';
    }

    public static function log($message)
    {
        if (self::$enabled === null) {
            self::init();
        }

        if (!self::$enabled) {
            return;
        }

        $date = date('Y-m-d H:i:s');
        $entry = '[' . $date . '] ' . $message . PHP_EOL;
        file_put_contents(self::$log_file, $entry, FILE_APPEND);
    }

    public static function get_log_file()
    {
        if (self::$log_file === null) {
            self::init();
        }
        return self::$log_file;
    }
}
