<?php
use CustomRouter\Route;
use App\Http\Controllers\Genealogy\GenealogyController;

/**
 * API Routes
 * Matches the path after '/genealogy' is stripped.
 */
Route::get('/genealogy/api/genealogy/tree', [GenealogyController::class, 'getTreeData']);
Route::get('/genealogy/api/genealogy/stats', [GenealogyController::class, 'getStats']);
Route::get('/genealogy/api/genealogy/upline', [GenealogyController::class, 'getUplineData']);