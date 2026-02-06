<?php

/**

 * Plugin Name: Genealogy System

 * Description: Custom genealogy management system integrated into WP dev server

 * Version: 1.0

 * Author: Kingsley

 */



if (!defined('ABSPATH')) {

    exit; // No direct access

}



define('GENEALOGY_PATH', plugin_dir_path(__FILE__));

define('GENEALOGY_URL', plugin_dir_url(__FILE__));



/*

|--------------------------------------------------------------------------

| Load Dependencies (Your System)

|--------------------------------------------------------------------------

*/

require_once GENEALOGY_PATH . 'vendor/autoload.php';

require_once GENEALOGY_PATH . 'bootstrap.php';

require_once GENEALOGY_PATH . 'router/api.php';

require_once GENEALOGY_PATH . 'router/web.php';



/*

|--------------------------------------------------------------------------

| Enqueue Your CSS & JS

|--------------------------------------------------------------------------

*/

function genealogy_assets() {

    wp_enqueue_style(

        'genealogy-css',

        GENEALOGY_URL . 'public/css/genealogy.css'

    );



    wp_enqueue_script(

        'genealogy-js',

        GENEALOGY_URL . 'public/js/genealogy.js',

        ['jquery'],

        null,

        true

    );

}

add_action('wp_enqueue_scripts', 'genealogy_assets');



/*

|--------------------------------------------------------------------------

| Shortcode to Render Your App

|--------------------------------------------------------------------------

*/

function genealogy_render_app() {

    ob_start();



    if (session_status() === PHP_SESSION_NONE) {

        session_start();

    }



    $_SERVER['REQUEST_URI'] = '/' . ($_GET['page'] ?? '');



    CustomRouter\Route::dispatch();



    return ob_get_clean();

}





add_shortcode('genealogy_app', 'genealogy_render_app');