<?php
use CustomRouter\Route;

if (!defined('ABSPATH')) exit;

function genealogy_render_app() {
    if (is_admin()) return '';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    try {
        require_once GENEALOGY_PATH . 'bootstrap.php';
        require_once GENEALOGY_PATH . 'router/web.php';
        require_once GENEALOGY_PATH . 'router/api.php';

        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Normalize: Remove '/genealogy' prefix and clean slashes
        $internalPath = preg_replace('#^/genealogy(/|$)#i', '/', $path);
        $normalizedPath = '/' . ltrim($internalPath, '/');
        if ($normalizedPath !== '/') {
            $normalizedPath = rtrim($normalizedPath, '/');
        }

        // Determine if this is an API request
        $isApi = str_contains($normalizedPath, '/api/');

        if ($isApi) {
            // Kill any previous output/buffers to ensure clean JSON
            while (ob_get_level()) {
                ob_end_clean();
            }
            Route::dispatch($method, $normalizedPath);
            // Controllers for API will call exit, so this line is rarely reached
            exit; 
        } else {
            ob_start();
            Route::dispatch($method, $normalizedPath);
            return ob_get_clean();
        }

    } catch (Throwable $e) {
        if (ob_get_length()) ob_end_clean();
        return '<pre style="color:red;background:#111;padding:12px;">Router Error: ' . esc_html($e->getMessage()) . '</pre>';
    }
}