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

// Load Composer autoloader (CRITICAL)
$autoload = GENEALOGY_PATH . 'vendor/autoload.php';

if (!file_exists($autoload)) {
    wp_die(
        '<h1>GENEALOGY CRITICAL ERROR</h1>
        <p>Composer autoload.php not found.</p>
        <p>Path expected: <code>' . esc_html($autoload) . '</code></p>'
    );
}

require_once $autoload;


/*
|--------------------------------------------------------------------------
| FORCE ERROR DISPLAY (plugin-level, no wp-config needed)
|--------------------------------------------------------------------------
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/
define('GENEALOGY_PATH', plugin_dir_path(__FILE__));
define('GENEALOGY_URL', plugin_dir_url(__FILE__));

/*
|--------------------------------------------------------------------------
| Load app definition ONLY (no execution)
|--------------------------------------------------------------------------
*/
$genealogy_app = GENEALOGY_PATH . 'public/app.php';

if (file_exists($genealogy_app)) {
    require_once $genealogy_app;
} else {
    // Fail gracefully instead of fatal error
    add_action('wp_footer', function () use ($genealogy_app) {
        echo '<pre style="color:red">
Genealogy error:
Missing file → ' . esc_html($genealogy_app) . '
</pre>';
    });
}

/*
|--------------------------------------------------------------------------
| Assets (loaded only on frontend pages)
|--------------------------------------------------------------------------
*/
add_action('wp_enqueue_scripts', function () {

    if (!is_singular()) {
        return;
    }

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
| Shortcode registration (guarded)
|--------------------------------------------------------------------------
*/
if (function_exists('genealogy_render_app')) {

    add_shortcode('genealogy_app', function () {

        try {
            return genealogy_render_app();
        } catch (Throwable $e) {

            return '<pre style="color:red; background:#111; padding:12px;">
GENEALOGY CRITICAL ERROR
-----------------------
Message: ' . esc_html($e->getMessage()) . '
File: ' . esc_html($e->getFile()) . '
Line: ' . esc_html($e->getLine()) . '
</pre>';
        }
    });

} else {

    add_action('wp_footer', function () {
        echo '<pre style="color:red">
Genealogy error:
genealogy_render_app() not found
</pre>';
    });
}
