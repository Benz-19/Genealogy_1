<?php

// Enable error logging
ini_set('log_errors', '1');
ini_set('display_errors', '0');
error_reporting(E_ALL);

// Log the error in the error_log file
ini_set('error_log', __DIR__ . '/../error_log.log');

// Capture fatal errors
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null) {
        $message = sprintf(
            "[%s] %s in %s on line %d\n",
            date('Y-m-d H:i:s'),
            $error['message'],
            $error['file'],
            $error['line']
        );

        error_log($message);
    }
});
