<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes (Session Management)
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('siswa.index');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Siswa Routes
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::get('/siswa/export/excel', [SiswaController::class, 'exportExcel'])->name('siswa.export');
    Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
    Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

    // Profile Routes (Requirement #10)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
