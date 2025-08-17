<?php
// Define minimal WordPress constant to prevent early exit.
define('ABSPATH', __DIR__ . '/');

// Stub WordPress hooks.
if (!function_exists('add_action')) {
    function add_action($tag, $function_to_add) {
        // no-op for tests
    }
}

// Simple autoloader for plugin classes.
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../inc/class/' . strtolower(str_replace('_', '-', $class)) . '.class.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Provide a simple replacement for WordPress's sanitize_text_field when running tests.
if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($str)
    {
        return is_scalar($str) ? preg_replace('/[\r\n\t ]+/', ' ', trim(strip_tags($str))) : '';
    }
}
