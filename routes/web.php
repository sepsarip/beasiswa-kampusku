<?php

use App\Http\Controllers\BeasiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('beasiswa.pilihan');
});

Route::get('/beasiswa', [BeasiswaController::class, 'pilihan'])->name('beasiswa.pilihan');
Route::get('/daftar', [BeasiswaController::class, 'create'])->name('beasiswa.daftar');
Route::post('/daftar', [BeasiswaController::class, 'store'])->name('beasiswa.store');
Route::get('/hasil', [BeasiswaController::class, 'hasil'])->name('beasiswa.hasil');
Route::get('/berkas/{id}', [BeasiswaController::class, 'downloadBerkas'])->name('beasiswa.berkas');
