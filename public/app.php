<?php

require_once __DIR__ . '/../error_logs/error_log.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SERVER['REQUEST_URI'] = '/' . ($_GET['page'] ?? '');

// Load routes ONLY at runtime
require_once GENEALOGY_PATH . 'router/api.php';
require_once GENEALOGY_PATH . 'router/web.php';

// Dispatch router
CustomRouter\Route::dispatch();
