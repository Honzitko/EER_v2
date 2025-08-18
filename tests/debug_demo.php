<?php
require __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../inc/class/eer-debug.class.php';

EER_Debug::init(true, __DIR__ . '/debug.log');
EER_Debug::log('Debugging is working');

echo 'Debug log written to ' . EER_Debug::get_log_file() . PHP_EOL;
