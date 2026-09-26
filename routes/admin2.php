<?php

use App\Http\Controllers\Admin2\AuthController;
use App\Http\Controllers\Admin2\DashboardController;
use App\Http\Controllers\Admin2\ReportController;
use App\Http\Controllers\Admin2\ResourceController;
use App\Http\Controllers\Admin2\ScheduleController;
use App\Http\Controllers\Admin2\SettingsController;
use App\Http\Middleware\Admin2Authenticate;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware([Admin2Authenticate::class, 'admin'])->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/konfigurasi', [SettingsController::class, 'index'])->name('settings');
    Route::post('/konfigurasi', [SettingsController::class, 'save'])->name('settings.save');

    Route::post('/jadwal-roadmap/{schedule}/generate', ScheduleController::class)->name('schedule.generate');

    Route::get('/keaktifan-peserta', [ReportController::class, 'activity'])->name('reports.activity');
    Route::get('/link-materi-harian', [ReportController::class, 'links'])->name('reports.links');

    // Register only known resources. No arbitrary model/table name can enter a controller.
    foreach (config('admin2') as $key => $definition) {
        $prefix = $definition['group'] === 'Konfigurasi' ? 'konfigurasi/'.$key : $key;
        Route::prefix($prefix)->name($key.'.')->group(function () {
            Route::get('/', [ResourceController::class, 'index'])->name('index');
            Route::get('/export', [ResourceController::class, 'export'])->name('export');
            Route::get('/options/{field}', [ResourceController::class, 'options'])->name('options');
            Route::get('/create', [ResourceController::class, 'form'])->name('create');
            Route::post('/', [ResourceController::class, 'save'])->name('store');
            Route::get('/{record}/edit', [ResourceController::class, 'form'])->whereNumber('record')->name('edit');
            Route::post('/{record}', [ResourceController::class, 'save'])->whereNumber('record')->name('update');
            Route::delete('/{record}', [ResourceController::class, 'destroy'])->whereNumber('record')->name('destroy');
        });
    }

});
