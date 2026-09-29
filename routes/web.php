<?php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\LandingController;
use App\Http\Middleware\AdminBasicAuth;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');

Route::middleware('throttle:10,1')->group(function () {
    Route::post('/join', [LandingController::class, 'joinList'])->name('join');
    Route::post('/sponsor', [LandingController::class, 'sponsor'])->name('sponsor');
});

Route::middleware(AdminBasicAuth::class)->prefix('admin')->group(function () {
    Route::get('/leads.csv', [ExportController::class, 'leads'])->name('admin.leads');
    Route::get('/sponsors.csv', [ExportController::class, 'sponsors'])->name('admin.sponsors');
});
