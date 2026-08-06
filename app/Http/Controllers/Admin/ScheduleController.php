<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route as TravelRoute;
use App\Models\Schedule;
use App\Models\TripManifest;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    // 1. Halaman List Jadwal
    public function index()
    {
        $schedules = Schedule::with(['route', 'vehicle', 'manifest.driver', 'bookings'])
            ->orderBy('departure_time', 'desc')
            ->get();

        return Inertia::render('Admin/Schedules/Index', [
            'schedules' => $schedules,
        ]);
    }

    // 2. Halaman Form Tambah Jadwal
    public function create()
    {
        return Inertia::render('Admin/Schedules/Create', [
            'routes' => TravelRoute::all(),
            'vehicles' => Vehicle::all(),
            'drivers' => User::where('role', 'driver')->get(),
        ]);
    }

    // 3. Simpan Jadwal Baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'departure_time' => 'required|date',
            'driver_id' => 'nullable|exists:users,id',
        ]);

        $schedule = Schedule::create([
            'route_id' => $validated['route_id'],
            'vehicle_id' => $validated['vehicle_id'],
            'departure_time' => $validated['departure_time'],
        ]);

        TripManifest::create([
            'schedule_id' => $schedule->id,
            'driver_id' => $validated['driver_id'] ?? null,
            'status' => 'scheduled',
        ]);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    // 4. Detail Pemesan / Customer per Jadwal
    public function show(Schedule $schedule)
    {
        $schedule->load([
            'route',
            'vehicle',
            'manifest.driver',
            'bookings.seats', // Mengambil data pemesan & nomor kursi
        ]);

        return Inertia::render('Admin/Schedules/Show', [
            'schedule' => $schedule,
        ]);
    }
}
