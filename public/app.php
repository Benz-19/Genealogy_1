<?php
require __DIR__ . '/../error_logs/error_log.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
