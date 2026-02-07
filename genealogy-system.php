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

define('GENEALOGY_PATH', plugin_dir_path(__FILE__));
define('GENEALOGY_URL', plugin_dir_url(__FILE__));

/*
|--------------------------------------------------------------------------
| Load core app (NO execution here)
|--------------------------------------------------------------------------
*/
require_once GENEALOGY_PATH . 'app.php';

/*
|--------------------------------------------------------------------------
| Assets
|--------------------------------------------------------------------------
*/
add_action('wp_enqueue_scripts', function () {

    if (!is_singular()) return;

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
});

/*
|--------------------------------------------------------------------------
| Shortcode
|--------------------------------------------------------------------------
*/
add_shortcode('genealogy_app', 'genealogy_render_app');
