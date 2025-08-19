<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../inc/class/eer-debug.class.php';

if (!class_exists('WP_Error')) {
    class WP_Error {
        private $errors = [];

        public function __construct($code = '', $message = '', $data = null)
        {
            if ($code) {
                $this->add($code, $message, $data);
            }
        }

        public function add($code, $message, $data = null)
        {
            $this->errors[$code][] = [
                'message' => $message,
                'data'    => $data,
            ];
        }

        public function get_error_codes()
        {
            return array_keys($this->errors);
        }

        public function get_error_messages($code = '')
        {
            if ($code) {
                return array_column($this->errors[$code] ?? [], 'message');
            }

            $all = [];
            foreach ($this->errors as $items) {
                foreach ($items as $item) {
                    $all[] = $item['message'];
                }
            }

            return $all;
        }

        public function get_error_data($code)
        {
            return $this->errors[$code][0]['data'] ?? null;
        }
    }
}

class DebugLogTest extends TestCase
{
    public function test_log_creates_file()
    {
        $log = tempnam(sys_get_temp_dir(), 'eer');
        EER_Debug::init(true, $log);
        EER_Debug::log('hello');
        $this->assertFileExists($log);
        $this->assertStringContainsString('hello', file_get_contents($log));
        unlink($log);
    }

    public function test_log_wp_error()
    {
        $log = tempnam(sys_get_temp_dir(), 'eer');
        EER_Debug::init(true, $log);
        $error = new WP_Error('sample', 'Sample message', ['foo' => 'bar']);
        EER_Debug::log_wp_error($error);
        $contents = file_get_contents($log);
        $this->assertStringContainsString('WP_Error sample: Sample message', $contents);
        $this->assertStringContainsString('"foo":"bar"', $contents);
        unlink($log);
    }
}
