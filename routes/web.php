<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\VisitAvailabilityController;
use App\Http\Controllers\Admin\DeliveryZoneController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\PlateOrderController;
use App\Http\Controllers\CustomRequestController;
use App\Http\Controllers\VehicleInterestController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PaymentController;
use App\Models\Vehicle;
use App\Models\Setting;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Carbon\Carbon;

Route::post('/locale', [LocaleController::class, 'setLocale'])->name('locale.set');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'featuredVehicle' => Vehicle::where('is_featured', true)
            ->where('is_available', true)
            ->first(),
        'vehicles' => Vehicle::where(function($query) {
                $query->where('is_available', true)
                      ->orWhere(function($q) {
                          $q->where('status', 'sold')
                            ->where('sold_at', '>=', Carbon::now()->subDays(7));
                      });
            })
            ->latest()
            ->take(6)
            ->get(),
    ]);
});

Route::get('/catalogue', function () {
    return Inertia::render('Catalogue', [
        'vehicles' => Vehicle::where(function($query) {
                $query->where('is_available', true)
                      ->orWhere(function($q) {
                          $q->where('status', 'sold')
                            ->where('sold_at', '>=', Carbon::now()->subDays(7));
                      });
            })
            ->latest()
            ->paginate(12),
    ]);
})->name('catalogue');

Route::get('/plaques', function () {
    return Inertia::render('Plaques', [
        'prices' => [
            'plexiglass' => Setting::get('plate_price_plexiglass', '14.90'),
            'alu' => Setting::get('plate_price_alu', '12.90'),
        ]
    ]);
})->name('plaques');

Route::post('/plate-orders', [PlateOrderController::class, 'store'])->name('plate-orders.store');

// Routes de paiement Stripe
Route::post('/payment/checkout', [PaymentController::class, 'createCheckoutSession'])->name('payment.checkout');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/webhook/stripe', [PaymentController::class, 'webhook'])->name('webhook.stripe');

Route::post('/custom-requests', [CustomRequestController::class, 'store'])->name('custom-requests.store');
Route::post('/vehicle-interests', [VehicleInterestController::class, 'store'])->name('vehicle-interests.store');
Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
Route::get('/visit-availabilities', [VehicleInterestController::class, 'getAvailabilities'])->name('visit-availabilities.get');
Route::get('/delivery-cost/{postalCode}', [VehicleInterestController::class, 'getDeliveryCost'])->name('delivery-cost.get');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('vehicles', VehicleController::class);
    Route::resource('leads', AdminLeadController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::get('/plate-orders', [PlateOrderController::class, 'index'])->name('plate-orders.index');
    Route::patch('/plate-orders/{order}/status', [PlateOrderController::class, 'updateStatus'])->name('plate-orders.update-status');
    Route::get('/custom-requests', [CustomRequestController::class, 'index'])->name('custom-requests.index');
    Route::patch('/custom-requests/{customRequest}/status', [CustomRequestController::class, 'updateStatus'])->name('custom-requests.update-status');
    Route::get('/vehicle-interests', [VehicleInterestController::class, 'index'])->name('vehicle-interests.index');
    Route::patch('/vehicle-interests/{interest}/status', [VehicleInterestController::class, 'updateStatus'])->name('vehicle-interests.update-status');
    Route::resource('visit-availabilities', VisitAvailabilityController::class)->except(['show', 'create', 'edit']);
    Route::resource('delivery-zones', DeliveryZoneController::class)->except(['show', 'create', 'edit']);
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
