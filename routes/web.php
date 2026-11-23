<?php

use App\Http\Controllers\CarrierController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('shipments', ShipmentController::class);
    Route::resource('carriers', CarrierController::class)->except('show');
    Route::resource('locations', LocationController::class)->except('show');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', fn () => 'Admin area')->name('admin.index');
});

Route::middleware(['auth', 'role:admin,operator'])->prefix('operations')->group(function () {
    Route::get('/', fn () => 'Operations area')->name('operations.index');
});

require __DIR__.'/auth.php';
