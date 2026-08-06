<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route as TravelRoute;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RouteController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Routes/Index', [
            'routes' => TravelRoute::latest()->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Routes/Create');
    }

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

    public function edit($id)
    {
        $travelRoute = TravelRoute::findOrFail($id);

        return Inertia::render('Admin/Routes/Edit', [
            'routeData' => $travelRoute, // Menggunakan key 'routeData' agar tidak bentrok dengan keyword 'route' di Vue
        ]);
    }

    public function update(Request $request, $id)
    {
        $travelRoute = TravelRoute::findOrFail($id);

        $validated = $request->validate([
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
        ]);

        $travelRoute::where('id', $id)->update($validated);

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $travelRoute = TravelRoute::findOrFail($id);
        $travelRoute->delete();

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil dihapus.');
    }
}
