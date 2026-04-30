<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;

Route::get('/', [RegistrationController::class, 'index']);
Route::get('/api/regions', [RegistrationController::class, 'getRegions']);
Route::get('/api/municipalities', [RegistrationController::class, 'getMunicipalities']);
Route::post('/api/register', [RegistrationController::class, 'store']);
