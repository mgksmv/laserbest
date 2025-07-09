<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstallController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');
Route::withoutMiddleware('web')->post('/install', [InstallController::class, 'store'])->name('install.store');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
