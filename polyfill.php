<?php
if (!function_exists('create_function')) {
    function create_function($args, $code) {
        $args = trim($args);
        return eval('return function(' . $args . ') {' . $code . '};');
    }
}
