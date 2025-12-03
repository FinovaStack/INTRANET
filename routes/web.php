<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InstallationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Installation Routes
Route::prefix('install')->group(function () {
    Route::get('/', [InstallationController::class, 'welcome'])->name('install.welcome');
    Route::get('/requirements', [InstallationController::class, 'requirements'])->name('install.requirements');
    Route::get('/database', [InstallationController::class, 'database'])->name('install.database');
    Route::post('/database', [InstallationController::class, 'storeDatabase'])->name('install.database.store');
    Route::get('/migrate', [InstallationController::class, 'migrate'])->name('install.migrate');
    Route::post('/migrate', [InstallationController::class, 'runMigrations'])->name('install.migrate.run');
    Route::get('/admin', [InstallationController::class, 'admin'])->name('install.admin');
    Route::post('/admin', [InstallationController::class, 'storeAdmin'])->name('install.admin.store');
    Route::get('/complete', [InstallationController::class, 'complete'])->name('install.complete');
});

require __DIR__.'/auth.php';
