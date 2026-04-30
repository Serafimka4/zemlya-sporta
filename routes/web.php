<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AdminController;

Route::get('/', [RegistrationController::class, 'index']);
Route::get('/api/regions', [RegistrationController::class, 'getRegions']);
Route::get('/api/municipalities', [RegistrationController::class, 'getMunicipalities']);
Route::post('/api/register', [RegistrationController::class, 'store']);

// Admin routes
Route::get('/admin', [AdminController::class, 'login']);
Route::post('/admin/login', [AdminController::class, 'authenticate']);
Route::get('/admin/logout', [AdminController::class, 'logout']);
Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
Route::get('/admin/export', [AdminController::class, 'export']);
Route::get('/admin/applications/{id}', [AdminController::class, 'show']);
