<?php
// Define minimal WordPress constant to prevent early exit.
define('ABSPATH', __DIR__);

// Simple autoloader for plugin classes.
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../inc/class/' . strtolower(str_replace('_', '-', $class)) . '.class.php';
    if (file_exists($file)) {
        require $file;
    }
});
