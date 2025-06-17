<?php

use App\Http\Controllers\PrayerRequestController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [PrayerRequestController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'organization'])->group(function () {
    Route::put('/requests/{request}', [PrayerRequestController::class, 'update'])->name('requests.update');
    Route::resource('requests', PrayerRequestController::class)
        ->only(['index', 'store', 'create'])
        ->except('update');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/requests', [App\Http\Controllers\Admin\PrayerRequestController::class, 'index'])->name('admin.requests.index');
    Route::put('/requests/{prayerRequest}', [App\Http\Controllers\Admin\PrayerRequestController::class, 'update'])->name('admin.requests.update');
    Route::delete('/requests/{prayerRequest}', [App\Http\Controllers\Admin\PrayerRequestController::class, 'destroy'])->name('admin.requests.destroy');
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
});

Route::get('/prayer-wall', [PrayerRequestController::class, 'wall'])
    ->middleware(['auth', 'verified'])
    ->name('prayer-wall');

Route::post('/requests/{prayerRequest}/pray', [PrayerRequestController::class, 'pray'])
    ->name('requests.pray')
    ->middleware(['auth']);

Route::post('/requests/{prayerRequest}/updates', [PrayerRequestController::class, 'storeUpdate'])
    ->name('requests.update.store')
    ->middleware(['auth']);

Route::middleware(['auth'])->group(function () {
    Route::put('/requests/updates/{update}', [PrayerRequestController::class, 'updateUpdate'])
        ->name('requests.updates.update');
    Route::delete('/requests/updates/{update}', [PrayerRequestController::class, 'destroyUpdate'])
        ->name('requests.updates.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/requests/{prayerRequest}', [PrayerRequestController::class, 'show'])
        ->name('requests.show');
});

require __DIR__.'/auth.php';
