<?php
use CustomRouter\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pages\PagesController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Genealogy\GenealogyController;

/**
 * Web Routes
 * Normalized to match the regex-stripped paths
 */

// GET Routes
Route::get('/', [PagesController::class, 'landingPage']);
Route::get('/genealogy/loginpage', [AuthController::class, 'showLoginPage']);
Route::get('/genealogy/genealogy-dashboard', [UserController::class, 'showDashboard']);
Route::get('/genealogy/genealogy-app', [GenealogyController::class, 'showGenealogyPage']);
Route::get('/genealogy/logout', [AuthController::class, 'logout']);

// POST Routes
Route::post('/', [AuthController::class, 'login']); 
Route::post('/genealogy/loginpage', [AuthController::class, 'login']); 
Route::post('/genealogy/register', [AuthController::class, 'register']);