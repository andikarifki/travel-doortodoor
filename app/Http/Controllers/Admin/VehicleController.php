<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehicleController extends Controller
{
    // List Armada
    public function index()
    {
        return Inertia::render('Admin/Vehicles/Index', [
            'vehicles' => Vehicle::latest()->get(),
        ]);
    }

    // Form Tambah Armada
    public function create()
    {
        return Inertia::render('Admin/Vehicles/Create');
    }

    // Simpan Armada Baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'license_plate' => 'required|string|max:50|unique:vehicles,license_plate',
            'capacity' => 'required|integer|min:1|max:60',
        ]);

        Vehicle::create($validated);

        return redirect()->route('admin.vehicles.index')->with('success', 'Armada berhasil ditambahkan.');
    }

    // Form Edit Armada
    public function edit(Vehicle $vehicle)
    {
        return Inertia::render('Admin/Vehicles/Edit', [
            'vehicle' => $vehicle,
        ]);
    }

    // Update Armada
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'license_plate' => 'required|string|max:50|unique:vehicles,license_plate,'.$vehicle->id,
            'capacity' => 'required|integer|min:1|max:60',
        ]);

        $vehicle->update($validated);

        return redirect()->route('admin.vehicles.index')->with('success', 'Armada berhasil diperbarui.');
    }

    // Hapus Armada
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')->with('success', 'Armada berhasil dihapus.');
    }
}
