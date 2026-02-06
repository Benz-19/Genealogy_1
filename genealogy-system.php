<?php
/**
 * Plugin Name: Genealogy System
 * Description: Custom genealogy management system
 * Version: 1.0
 * Author: Kingsley
 */

if (!defined('ABSPATH')) {
    exit;
}

// Plugin paths
define('GENEALOGY_PATH', plugin_dir_path(__FILE__));
define('GENEALOGY_URL', plugin_dir_url(__FILE__));

/*
|--------------------------------------------------------------------------
| Load Core Dependencies (SAFE AT BOOT TIME)
|--------------------------------------------------------------------------
*/
$autoload = GENEALOGY_PATH . 'vendor/autoload.php';

if (!file_exists($autoload)) {
    wp_die('Genealogy plugin error: vendor/autoload.php not found.');
}

require_once $autoload;
require_once GENEALOGY_PATH . 'bootstrap.php';

/*
|--------------------------------------------------------------------------
| Enqueue Assets
|--------------------------------------------------------------------------
*/
function genealogy_assets() {
    wp_enqueue_style(
        'genealogy-css',
        GENEALOGY_URL . 'public/css/genealogy.css',
        [],
        '1.0'
    );

    wp_enqueue_script(
        'genealogy-js',
        GENEALOGY_URL . 'public/js/genealogy.js',
        ['jquery'],
        '1.0',
        true
    );

    // Pass base URL to JS (VERY IMPORTANT)
    wp_localize_script('genealogy-js', 'GENEALOGY_APP', [
        'base_url' => get_permalink()
    ]);
}
add_action('wp_enqueue_scripts', 'genealogy_assets');

/*
|--------------------------------------------------------------------------
| Shortcode = App Entry Point
|--------------------------------------------------------------------------
*/
function genealogy_render_app() {
    ob_start();

    require GENEALOGY_PATH . 'public/app.php';

    return ob_get_clean();
}
add_shortcode('genealogy_app', 'genealogy_render_app');
