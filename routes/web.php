<?php

use App\Http\Controllers\SentimentController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LogoutController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [SentimentController::class, 'index'])->name('sentiments.index');
    Route::post('/analyze', [SentimentController::class, 'analyze'])->name('sentiments.analyze');
    Route::get('/dashboard', [SentimentController::class, 'dashboard'])->name('sentiments.dashboard');
    Route::get('/history', [SentimentController::class, 'history'])->name('sentiments.history');
    Route::get('/sentiments/{sentiment}/edit', [SentimentController::class, 'edit'])->name('sentiments.edit');
    Route::put('/sentiments/{sentiment}', [SentimentController::class, 'update'])->name('sentiments.update');
    Route::delete('/sentiments/{sentiment}', [SentimentController::class, 'destroy'])->name('sentiments.destroy');
    Route::post('/import', [SentimentController::class, 'import'])->name('sentiments.import');
    Route::get('/export', [SentimentController::class, 'export'])->name('sentiments.export');
    Route::get('/print', [SentimentController::class, 'print'])->name('sentiments.print');
});