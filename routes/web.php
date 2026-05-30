<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsDetectorController;

// Mengarahkan halaman utama ke view detektor
Route::get('/', [NewsDetectorController::class, 'index'])->name('detector.index');

// Endpoint API internal untuk memproses analisis berita palsu
Route::post('/analyze', [NewsDetectorController::class, 'analyze'])->name('detector.analyze');