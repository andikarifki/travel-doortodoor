<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route as TravelRoute;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RouteController extends Controller
{
    // List Rute
    public function index()
    {
        return Inertia::render('Admin/Routes/Index', [
            'routes' => TravelRoute::latest()->get(),
        ]);
    }

    // Form Tambah Rute
    public function create()
    {
        return Inertia::render('Admin/Routes/Create');
    }

    // Simpan Rute Baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
        ]);

        TravelRoute::create($validated);

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil ditambahkan.');
    }

    // Form Edit Rute
    public function edit(TravelRoute $route)
    {
        return Inertia::render('Admin/Routes/Edit', [
            'route' => $route,
        ]);
    }

    // Update Rute
    public function update(Request $request, TravelRoute $route)
    {
        $validated = $request->validate([
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
        ]);

        $route->update($validated);

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil diperbarui.');
    }

    // Hapus Rute
    public function destroy(TravelRoute $route)
    {
        $route->delete();

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil dihapus.');
    }
}
