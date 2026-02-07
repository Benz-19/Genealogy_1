<?php

if (!defined('ABSPATH')) {
    exit;
}

function genealogy_render_app() {

    // Never run in admin / REST
    if (is_admin() || defined('REST_REQUEST')) {
        return '';
    }

    // ---- SAFETY CHECKS ----
    $autoload = GENEALOGY_PATH . 'vendor/autoload.php';
    $bootstrap = GENEALOGY_PATH . 'bootstrap.php';
    $api = GENEALOGY_PATH . 'router/api.php';
    $web = GENEALOGY_PATH . 'router/web.php';

    foreach ([$autoload, $bootstrap, $api, $web] as $file) {
        if (!file_exists($file)) {
            return '<pre>Genealogy error: missing file ' . esc_html(basename($file)) . '</pre>';
        }
    }

    // ---- LOAD DEPENDENCIES ----
    require_once $autoload;
    require_once $bootstrap;
    require_once $api;
    require_once $web;

    if (!class_exists('CustomRouter\Route')) {
        return '<pre>Genealogy error: Router not loaded</pre>';
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Normalize URL for your router
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    $_SERVER['REQUEST_URI'] = '/' . $path;

    ob_start();
    CustomRouter\Route::dispatch();
    return ob_get_clean();
}
