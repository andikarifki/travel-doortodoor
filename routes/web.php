<?php

use App\Http\Controllers\Admin\RouteController as AdminRouteController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicBookingController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Public Routes (Tanpa Login / Guest Customer)
|--------------------------------------------------------------------------
*/

// Halaman utama pencarian travel
Route::get('/', [PublicBookingController::class, 'index'])->name('booking.search');

// Detail jadwal & form kursi/alamat
Route::get('/schedule/{schedule}', [PublicBookingController::class, 'show'])->name('booking.show');

// Proses submit booking
Route::post('/booking', [PublicBookingController::class, 'store'])->name('booking.store');

// Halaman konfirmasi / e-ticket
Route::get('/booking/success/{code}', [PublicBookingController::class, 'success'])->name('booking.success');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', function () {
        return redirect()->route('admin.schedules.index');
    })->name('dashboard');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/schedules', [AdminScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/create', [AdminScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/schedules', [AdminScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/schedules/{schedule}', [AdminScheduleController::class, 'show'])->name('schedules.show');
        Route::get('/schedules/{schedule}/edit', [AdminScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/schedules/{schedule}', [AdminScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{schedule}', [AdminScheduleController::class, 'destroy'])->name('schedules.destroy');
        // Vehicles (Armada)
        Route::resource('vehicles', AdminVehicleController::class);
        // Routes (Rute Travel)
        Route::resource('routes', AdminRouteController::class);
    });
});

require __DIR__.'/auth.php';
