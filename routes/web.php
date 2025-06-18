<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\LaboratoriumController;
use App\Http\Controllers\Admin\PerlengkapanController as AdminPerlengkapanController;
use App\Http\Controllers\Admin\PemeliharaanController as AdminPemeliharaanController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\KepalaLab\DashboardController as KepalaLabDashboardController;
use App\Http\Controllers\KepalaLab\PerlengkapanController as KepalaLabPerlengkapanController;
use App\Http\Controllers\KepalaLab\PemeliharaanController as KepalaLabPemeliharaanController;
use App\Http\Controllers\KepalaLab\PeminjamanController as KepalaLabPeminjamanController;
use App\Http\Controllers\KepalaLab\LaporanController as KepalaLabLaporanController;
use App\Http\Controllers\KepalaLab\AsistenController as KepalaLabAsistenController;
use App\Http\Controllers\KepalaLab\ActivityLogController as KepalaLabActivityLogController;
use App\Http\Controllers\AsistenLab\DashboardController as AsistenLabDashboardController;
use App\Http\Controllers\AsistenLab\PerlengkapanController as AsistenLabPerlengkapanController;
use App\Http\Controllers\AsistenLab\PemeliharaanController as AsistenLabPemeliharaanController;
use App\Http\Controllers\AsistenLab\PeminjamanController as AsistenLabPeminjamanController;
use App\Http\Controllers\AsistenLab\LaporanController as AsistenLabLaporanController;
use App\Http\Controllers\AsistenLab\ActivityLogController as AsistenLabActivityLogController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    // General dashboard for users without role
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('pengguna', PenggunaController::class);
        Route::resource('laboratorium', LaboratoriumController::class);
        Route::resource('perlengkapan', AdminPerlengkapanController::class);
        Route::resource('pemeliharaan', AdminPemeliharaanController::class);
        Route::resource('peminjaman', AdminPeminjamanController::class);
        Route::resource('laporan', AdminLaporanController::class);
        Route::get('/laporan/export/pdf', [AdminLaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
        Route::resource('activity-logs', AdminActivityLogController::class)->only(['index', 'show']);
    });

    // Kepala Lab routes
    Route::middleware('role:kepalalab')->prefix('kepalalab')->name('kepalalab.')->group(function () {
        Route::get('/dashboard', [KepalaLabDashboardController::class, 'index'])->name('dashboard');
        Route::resource('perlengkapan', KepalaLabPerlengkapanController::class);
        Route::resource('pemeliharaan', KepalaLabPemeliharaanController::class);
        Route::resource('peminjaman', KepalaLabPeminjamanController::class);
        Route::resource('laporan', KepalaLabLaporanController::class);
        Route::get('/laporan/export/pdf', [KepalaLabLaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
        Route::resource('asisten', KepalaLabAsistenController::class)->only(['index', 'edit', 'update']);
        Route::resource('activity-logs', KepalaLabActivityLogController::class)->only(['index', 'show']);
    });

    // Asisten Lab routes
    Route::middleware('role:asistenlab')->prefix('asistenlab')->name('asistenlab.')->group(function () {
        Route::get('/dashboard', [AsistenLabDashboardController::class, 'index'])->name('dashboard');
        Route::resource('perlengkapan', AsistenLabPerlengkapanController::class);
        Route::resource('pemeliharaan', AsistenLabPemeliharaanController::class);
        Route::resource('peminjaman', AsistenLabPeminjamanController::class);
        Route::resource('laporan', AsistenLabLaporanController::class);
        Route::get('/laporan/export/pdf', [AsistenLabLaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
        Route::resource('activity-logs', AsistenLabActivityLogController::class)->only(['index', 'show']);
    });
});