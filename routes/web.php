<?php

use App\Http\Controllers\SentimentController;
use Illuminate\Support\Facades\Route;

/// Public Routes
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LogoutController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/sentiments', [App\Http\Controllers\SentimentController::class, 'index'])->name('sentiments.index');
    Route::post('/analyze', [App\Http\Controllers\SentimentController::class, 'analyze'])->name('sentiments.analyze');
    Route::get('/dashboard', [App\Http\Controllers\SentimentController::class, 'dashboard'])->name('sentiments.dashboard');
    Route::get('/history', [App\Http\Controllers\SentimentController::class, 'history'])->name('sentiments.history');
    Route::get('/sentiments/{sentiment}/edit', [App\Http\Controllers\SentimentController::class, 'edit'])->name('sentiments.edit');
    Route::put('/sentiments/{sentiment}', [App\Http\Controllers\SentimentController::class, 'update'])->name('sentiments.update');
    Route::delete('/sentiments/{sentiment}', [App\Http\Controllers\SentimentController::class, 'destroy'])->name('sentiments.destroy');
    Route::post('/import', [App\Http\Controllers\SentimentController::class, 'import'])->name('sentiments.import');
    Route::get('/export', [App\Http\Controllers\SentimentController::class, 'export'])->name('sentiments.export');
    Route::get('/print', [App\Http\Controllers\SentimentController::class, 'print'])->name('sentiments.print');
});