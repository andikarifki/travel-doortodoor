<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DriverController as AdminDriverController;
use App\Http\Controllers\Admin\RouteController as AdminRouteController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicBookingController;
use Illuminate\Support\Facades\Route;

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

// Stream/Download E-Tiket PDF (Public Akses agar customer bisa cetak)
Route::get('/booking/ticket/{booking_code}', [PublicBookingController::class, 'downloadTicket'])->name('booking.ticket');

/*
|--------------------------------------------------------------------------
| Admin Routes (Wajib Auth)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard Utama Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Admin Group
    Route::prefix('admin')->name('admin.')->group(function () {
        // Schedules
        Route::get('/schedules', [AdminScheduleController::class, 'index'])->name('schedules.index');
        Route::get('/schedules/create', [AdminScheduleController::class, 'create'])->name('schedules.create');
        Route::post('/schedules', [AdminScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/schedules/{schedule}', [AdminScheduleController::class, 'show'])->name('schedules.show');
        Route::get('/schedules/{schedule}/edit', [AdminScheduleController::class, 'edit'])->name('schedules.edit');
        Route::put('/schedules/{schedule}', [AdminScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{schedule}', [AdminScheduleController::class, 'destroy'])->name('schedules.destroy');

        // Update Status Booking Pembayaran
        Route::patch('/bookings/{booking}/status', [AdminScheduleController::class, 'updateBookingStatus'])->name('bookings.update-status');

        // Vehicles (Armada)
        Route::resource('vehicles', AdminVehicleController::class);

        // Routes
        Route::get('/routes', [AdminRouteController::class, 'index'])->name('routes.index');
        Route::get('/routes/create', [AdminRouteController::class, 'create'])->name('routes.create');
        Route::post('/routes', [AdminRouteController::class, 'store'])->name('routes.store');
        Route::get('/routes/{id}/edit', [AdminRouteController::class, 'edit'])->name('routes.edit');
        Route::put('/routes/{id}', [AdminRouteController::class, 'update'])->name('routes.update');
        Route::delete('/routes/{id}', [AdminRouteController::class, 'destroy'])->name('routes.destroy');

        // Drivers (Kelola Akun Sopir)
        Route::get('/drivers', [AdminDriverController::class, 'index'])->name('drivers.index');
        Route::get('/drivers/create', [AdminDriverController::class, 'create'])->name('drivers.create');
        Route::post('/drivers', [AdminDriverController::class, 'store'])->name('drivers.store');
        Route::delete('/drivers/{id}', [AdminDriverController::class, 'destroy'])->name('drivers.destroy');
    });
});

require __DIR__.'/auth.php';
