<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');

Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('animals', App\Http\Controllers\AnimalController::class);
    
    Route::get('/messages', [App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/api/{receiverId}', [App\Http\Controllers\MessageController::class, 'fetchMessages'])->name('messages.api.fetch');
    Route::post('/messages/api/{receiverId}', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.api.store');

    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile',  [App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/farm',     [App\Http\Controllers\SettingsController::class, 'updateFarm'])->name('settings.farm');
    Route::post('/settings/password', [App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.password');

    // Geofencing — specific paths MUST be before {id} wildcard routes
    Route::get('/geofences', [App\Http\Controllers\GeofenceController::class, 'index'])->name('geofences.index');
    Route::post('/geofences', [App\Http\Controllers\GeofenceController::class, 'store'])->name('geofences.store');
    Route::post('/geofences/check', [App\Http\Controllers\GeofenceController::class, 'checkAnimal'])->name('geofences.check');
    Route::patch('/geofences/alerts/{id}/resolve', [App\Http\Controllers\GeofenceController::class, 'resolveAlert'])->name('geofences.alerts.resolve');
    Route::get('/api/geofences', [App\Http\Controllers\GeofenceController::class, 'apiIndex'])->name('geofences.api');
    // Wildcard {id} routes last
    Route::patch('/geofences/{id}/toggle', [App\Http\Controllers\GeofenceController::class, 'toggle'])->name('geofences.toggle');
    Route::delete('/geofences/{id}', [App\Http\Controllers\GeofenceController::class, 'destroy'])->name('geofences.destroy');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
