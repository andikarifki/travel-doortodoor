<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\User; // Import model User
use App\Models\Vehicle;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil statistik ringkas untuk halaman dashboard
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'today_schedules' => Schedule::whereDate('departure_time', today())->count(),
            'total_vehicles' => Vehicle::count(),
            'total_drivers' => User::where('role', 'driver')->count(), // Hitung driver dari tabel users
            'total_routes' => Route::count(),
        ];

        // 5 Booking terbaru
        $recentBookings = Booking::with(['schedule.route'])
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentBookings' => $recentBookings,
        ]);
    }
}
