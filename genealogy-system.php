<?php
/**
 * Plugin Name: Genealogy System
 * Description: Custom Genealogy Application (isolated, WP-safe)
 * Version: 1.0.0
 * Author: Kingsley
 */

if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/
define('GENEALOGY_PATH', plugin_dir_path(__FILE__));
define('GENEALOGY_URL', plugin_dir_url(__FILE__));

/*
|--------------------------------------------------------------------------
| WordPress Routing Support
|--------------------------------------------------------------------------
*/

// 1. Tell WP to recognize our custom route variable
add_filter('query_vars', function($vars) {
    $vars[] = 'app_route';
    return $vars;
});

// 2. Map /genealogy/xyz to our page and capture 'xyz'
add_action('init', function() {
    add_rewrite_rule('^genealogy/([^/]+)/?', 'index.php?pagename=genealogy&app_route=$matches[1]', 'top');
});

// 3. Flush rules on activation
register_activation_hook(__FILE__, function() {
    add_rewrite_rule('^genealogy/([^/]+)/?', 'index.php?pagename=genealogy&app_route=$matches[1]', 'top');
    flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, 'flush_rewrite_rules');

/*
|--------------------------------------------------------------------------
| Load App Logic
|--------------------------------------------------------------------------
*/
$autoload = GENEALOGY_PATH . 'vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

$genealogy_app = GENEALOGY_PATH . 'public/app.php';
if (file_exists($genealogy_app)) {
    require_once $genealogy_app;
}

/*
|--------------------------------------------------------------------------
| Assets & Shortcode
|--------------------------------------------------------------------------
*/
add_action('wp_enqueue_scripts', function () {
    if (is_singular()) {
        wp_enqueue_style('genealogy-css', GENEALOGY_URL . 'public/css/genealogy.css');
        wp_enqueue_script('genealogy-js', GENEALOGY_URL . 'public/js/genealogy.js', ['jquery'], '1.0', true);
    }
});

add_shortcode('genealogy_app', function () {
    if (function_exists('genealogy_render_app')) {
        return genealogy_render_app();
    }
    return 'App Error: Render function missing.';
});