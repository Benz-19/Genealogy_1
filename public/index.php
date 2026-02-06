<?php
require __DIR__ . '/../error_logs/error_log.php';

if(!session_start()){
    session_start();
}

use CustomRouter\Route;

require __DIR__ ."/../vendor/autoload.php";
require __DIR__ ."/../bootstrap.php";
require __DIR__."/../router/api.php";
require __DIR__."/../router/web.php";

Route::dispatch();