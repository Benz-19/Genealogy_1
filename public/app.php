<?php

if (!defined('ABSPATH')) {
    exit;
}

tion genealogy_render_app() {

    if (is_admin() || defined('REST_REQUEST')) {
        return '';
    }

    // Load dependencies only when needed
    require_once GENEALOGY_PATH . 'vendor/autoload.php';
    require_once GENEALOGY_PATH . 'bootstrap.php';
    require_once GENEALOGY_PATH . 'router/api.php';
    require_once GENEALOGY_PATH . 'router/web.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Normalize request for your router
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $_SERVER['REQUEST_URI'] = '/' . $path;

    ob_start();
    CustomRouter\Route::dispatch();
    return ob_get_clean();
}
