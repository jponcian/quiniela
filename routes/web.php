<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\PredictionPdfController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\PaymentController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/partidos', [\App\Http\Controllers\GameController::class, 'index'])->name('matches.all');
Route::get('/ranking', [RankingController::class, 'index'])->name('ranking');
Route::view('/reglas', 'rules')->name('rules');

// Rutas de Predicciones
Route::middleware('auth')->group(function () {
    Route::get('/mis-pronosticos', [PredictionController::class, 'index'])->name('predictions.index');
    Route::post('/api/predictions', [PredictionController::class, 'store']);
    Route::get('/mis-pronosticos/pdf', [PredictionPdfController::class, 'download'])->name('predictions.pdf');
});

// Rutas de Grupos
Route::middleware('auth')->group(function () {
    Route::get('/grupos', [GroupController::class, 'index'])->name('groups.index');
    Route::post('/grupos', [GroupController::class, 'store'])->name('groups.store');
    Route::post('/grupos/join', [GroupController::class, 'join'])->name('groups.join');
    Route::post('/grupos/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');
    Route::delete('/grupos/{group}', [GroupController::class, 'delete'])->name('groups.delete');
});

// Rutas de Registro
Route::get('/registro', [RegisterController::class, 'show']);
Route::post('/registro', [RegisterController::class, 'register']);
Route::get('/api/check-cedula', [RegisterController::class, 'checkCedula']);

// Rutas de Login
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas Administrativas
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/matches', [AdminController::class, 'matches'])->name('matches.index');
    Route::post('/matches/{game}', [AdminController::class, 'updateMatch'])->name('matches.update');
    Route::post('/sync-matches', [AdminController::class, 'syncMatches'])->name('sync');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
});
