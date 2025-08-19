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

    public static function log_wp_error($error)
    {
        if (!($error instanceof WP_Error)) {
            return;
        }

        foreach ($error->get_error_codes() as $code) {
            $messages = $error->get_error_messages($code);
            $data     = $error->get_error_data($code);

            foreach ($messages as $message) {
                $entry = 'WP_Error ' . $code . ': ' . $message;
                if (!empty($data)) {
                    $json  = function_exists('wp_json_encode') ? wp_json_encode($data) : json_encode($data);
                    $entry .= ' | Data: ' . $json;
                }
                self::log($entry);
            }
        }
    }

    public static function get_log_file()
    {
        if (self::$log_file === null) {
            self::init();
        }
        return self::$log_file;
    }
}
