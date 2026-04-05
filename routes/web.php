<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\ServiceScheduleController;
use App\Http\Controllers\Admin\QueueController as AdminQueueController;
use App\Http\Controllers\Officer\DashboardController as OfficerDashboard;
use App\Http\Controllers\Officer\QueueController as OfficerQueueController;
use App\Http\Controllers\Public\QueueRegistrationController;
use App\Http\Controllers\Public\DisplayController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────
// Public (no auth required)
// ──────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('public.index'));
Route::get('/display', [DisplayController::class, 'index'])->name('public.display');

Route::prefix('antrian')->name('public.')->group(function () {
    Route::get('/',                        [QueueRegistrationController::class, 'index'])->name('index');
    Route::get('/konfirmasi',              [QueueRegistrationController::class, 'confirmation'])->name('confirmation');
    Route::get('/{service:slug}',          [QueueRegistrationController::class, 'show'])->name('register');
    Route::post('/{service:slug}',         [QueueRegistrationController::class, 'store'])->name('register.store');
});

// ──────────────────────────────────────────────
// Auth
// ──────────────────────────────────────────────
Route::get('/login',  [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ──────────────────────────────────────────────
// Admin
// ──────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('users',     UserController::class);
    Route::resource('services',  ServiceController::class);
    Route::resource('counters',  CounterController::class);
    Route::resource('schedules', ServiceScheduleController::class);

    Route::get('queues', [AdminQueueController::class, 'index'])->name('queues.index');
});

// ──────────────────────────────────────────────
// Officer
// ──────────────────────────────────────────────
Route::prefix('officer')->name('officer.')->middleware(['auth', 'officer'])->group(function () {
    Route::get('select-counter',  [OfficerDashboard::class, 'selectCounter'])->name('counter.select');
    Route::post('select-counter', [OfficerDashboard::class, 'storeCounter'])->name('counter.store');

    Route::get('/',               [OfficerDashboard::class, 'index'])->name('dashboard');

    Route::post('queue/call-next',       [OfficerQueueController::class, 'callNext'])->name('queue.call-next');
    Route::patch('queue/{queue}/done',   [OfficerQueueController::class, 'done'])->name('queue.done');
    Route::patch('queue/{queue}/skip',   [OfficerQueueController::class, 'skip'])->name('queue.skip');
});

