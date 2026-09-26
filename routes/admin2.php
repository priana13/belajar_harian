<?php

use App\Http\Controllers\Admin2\DashboardController;
use App\Http\Controllers\Admin2\ResourceController;
use App\Http\Controllers\Admin2\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');
Route::get('/konfigurasi', [SettingsController::class, 'index'])->name('settings');
Route::post('/konfigurasi', [SettingsController::class, 'save'])->name('settings.save');

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
