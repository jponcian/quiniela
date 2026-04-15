<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\PredictionPdfController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/ranking', [\App\Http\Controllers\RankingController::class, 'index'])->name('ranking');
Route::view('/reglas', 'rules')->name('rules');

// Rutas de Predicciones (API)
Route::post('/api/predictions', [PredictionController::class, 'store'])->middleware('auth');
Route::get('/mis-pronosticos/pdf', [PredictionPdfController::class, 'download'])->middleware('auth')->name('predictions.pdf');

// Rutas de Registro
Route::get('/registro', [RegisterController::class, 'show']);
Route::post('/registro', [RegisterController::class, 'register']);
Route::get('/api/check-cedula', [RegisterController::class, 'checkCedula']);

// Rutas de Login
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
