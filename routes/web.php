<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\VisitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/visit-modal', [VisitController::class, 'getModalData']);
});

Route::withoutMiddleware('web')->post('/install', [InstallController::class, 'store'])->name('install.store');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
