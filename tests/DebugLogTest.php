<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../inc/class/eer-debug.class.php';

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
}
