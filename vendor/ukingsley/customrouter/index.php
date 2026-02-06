<?php


require __DIR__ . '/vendor/autoload.php';

use CustomRouter\Route;

require __DIR__ . '/routes/web.php';


// Dispatch the matched route
Route::dispatch();
