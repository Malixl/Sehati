<?php

use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    return view('pages.landing');
});

// Public Screening Routes with Device Middleware
Route::middleware(['device'])->group(function () {
    Route::get('/consent', [\App\Http\Controllers\ScreeningController::class, 'showConsentForm'])->name('screening.consent');

    Route::get('/respondent', [\App\Http\Controllers\ScreeningController::class, 'showRespondentForm'])->name('screening.respondent');
    Route::get('/questionnaire', [\App\Http\Controllers\ScreeningController::class, 'showQuestionnaireForm'])->name('screening.questionnaire');
    Route::post('/screening', [\App\Http\Controllers\ScreeningController::class, 'store'])->name('screening.store');
    Route::get('/result/{id}', [\App\Http\Controllers\ScreeningController::class, 'show'])->name('screening.result');
    Route::get('/riwayat', [\App\Http\Controllers\ScreeningController::class, 'history'])->name('screening.history');
});

// Map Page (Faskes Terdekat)
Route::get('/map', function () {
    return view('pages.map');
});

// Design System Test Page
Route::get('/test', function () {
    return view('test');
});

// Authentication Routes
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'authenticate'])->middleware('guest');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard Routes
Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('index');
    
    // Responden
    Route::get('/responden', [\App\Http\Controllers\Dashboard\RespondentController::class, 'index'])->name('responden.index');
    Route::post('/responden', [\App\Http\Controllers\Dashboard\RespondentController::class, 'store'])->name('responden.store');
    Route::put('/responden/{id}', [\App\Http\Controllers\Dashboard\RespondentController::class, 'update'])->name('responden.update');
    Route::delete('/responden/{id}', [\App\Http\Controllers\Dashboard\RespondentController::class, 'destroy'])->name('responden.destroy');
    Route::get('/responden/export', [\App\Http\Controllers\Dashboard\RespondentController::class, 'export'])->name('responden.export');
    Route::get('/responden/{id}', [\App\Http\Controllers\Dashboard\RespondentController::class, 'show'])->name('responden.show');
    
    // Skrining
    Route::get('/skrining', [\App\Http\Controllers\Dashboard\ScreeningController::class, 'index'])->name('skrining.index');
    Route::get('/skrining/export', [\App\Http\Controllers\Dashboard\ScreeningController::class, 'export'])->name('skrining.export');
    Route::get('/skrining/{id}', [\App\Http\Controllers\Dashboard\ScreeningController::class, 'show'])->name('skrining.show');
    Route::patch('/skrining/{id}/status', [\App\Http\Controllers\Dashboard\ScreeningController::class, 'updateStatus'])->name('skrining.updateStatus');
    Route::delete('/skrining/{id}', [\App\Http\Controllers\Dashboard\ScreeningController::class, 'destroy'])->name('skrining.destroy');
    
    // Periode Skrining (Super Admin & Owner)
    Route::middleware(['role:super_admin,owner'])->group(function () {
        Route::get('/periode', [\App\Http\Controllers\Dashboard\ScreeningPeriodController::class, 'index'])->name('periode.index');
        Route::post('/periode', [\App\Http\Controllers\Dashboard\ScreeningPeriodController::class, 'store'])->name('periode.store');
        Route::put('/periode/{id}', [\App\Http\Controllers\Dashboard\ScreeningPeriodController::class, 'update'])->name('periode.update');
        Route::patch('/periode/{id}/toggle', [\App\Http\Controllers\Dashboard\ScreeningPeriodController::class, 'toggleActive'])->name('periode.toggle');
        Route::delete('/periode/{id}', [\App\Http\Controllers\Dashboard\ScreeningPeriodController::class, 'destroy'])->name('periode.destroy');

        // Data Posyandu
        Route::get('/posyandu', [\App\Http\Controllers\Dashboard\PosyanduController::class, 'index'])->name('posyandu.index');
        Route::post('/posyandu', [\App\Http\Controllers\Dashboard\PosyanduController::class, 'store'])->name('posyandu.store');
        Route::put('/posyandu/{id}', [\App\Http\Controllers\Dashboard\PosyanduController::class, 'update'])->name('posyandu.update');
        Route::delete('/posyandu/{id}', [\App\Http\Controllers\Dashboard\PosyanduController::class, 'destroy'])->name('posyandu.destroy');
        Route::get('/posyandu/export', [\App\Http\Controllers\Dashboard\PosyanduController::class, 'export'])->name('posyandu.export');
        
        // Manajemen Pengguna
        Route::get('/pengguna', [\App\Http\Controllers\Dashboard\UserController::class, 'index'])->name('pengguna.index');
        Route::post('/pengguna', [\App\Http\Controllers\Dashboard\UserController::class, 'store'])->name('pengguna.store');
        Route::put('/pengguna/{id}', [\App\Http\Controllers\Dashboard\UserController::class, 'update'])->name('pengguna.update');
        Route::delete('/pengguna/{id}', [\App\Http\Controllers\Dashboard\UserController::class, 'destroy'])->name('pengguna.destroy');
        Route::get('/pengguna/export', [\App\Http\Controllers\Dashboard\UserController::class, 'export'])->name('pengguna.export');
    });

    Route::get('/capaian', [\App\Http\Controllers\Dashboard\CapaianController::class, 'index'])->name('capaian.index');
    Route::get('/capaian/export', [\App\Http\Controllers\Dashboard\CapaianController::class, 'export'])->name('capaian.export');
    Route::put('/capaian/village/{id}/target', [\App\Http\Controllers\Dashboard\CapaianController::class, 'updateTarget'])->name('capaian.updateTarget');
    
    Route::get('/profil', [\App\Http\Controllers\Dashboard\ProfileController::class, 'index'])->name('profil.index');
    Route::put('/profil', [\App\Http\Controllers\Dashboard\ProfileController::class, 'update'])->name('profil.update');
    
    // Notifikasi
    Route::get('/notifications/{id}/mark-as-read', [\App\Http\Controllers\Dashboard\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
});
