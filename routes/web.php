<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\PlateOrderController;
use App\Models\Vehicle;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'featuredVehicle' => Vehicle::where('is_featured', true)
            ->where('is_available', true)
            ->first(),
        'vehicles' => Vehicle::where('is_available', true)
            ->latest()
            ->take(6)
            ->get(),
    ]);
});

Route::get('/catalogue', function () {
    return Inertia::render('Catalogue', [
        'vehicles' => Vehicle::where('is_available', true)
            ->latest()
            ->paginate(12),
    ]);
})->name('catalogue');

Route::get('/plaques', function () {
    return Inertia::render('Plaques');
})->name('plaques');

Route::post('/plate-orders', [PlateOrderController::class, 'store'])->name('plate-orders.store');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('vehicles', VehicleController::class);
    Route::resource('leads', LeadController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::get('/plate-orders', [PlateOrderController::class, 'index'])->name('plate-orders.index');
    Route::patch('/plate-orders/{order}/status', [PlateOrderController::class, 'updateStatus'])->name('plate-orders.update-status');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
