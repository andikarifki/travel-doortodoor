<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class DriverController extends Controller
{
    // List Driver
    public function index()
    {
        $drivers = User::where('role', 'driver')->latest()->get();

        return Inertia::render('Admin/Drivers/Index', [
            'drivers' => $drivers,
        ]);
    }

    // Form Tambah Driver
    public function create()
    {
        return Inertia::render('Admin/Drivers/Create');
    }

    // Simpan Driver Baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'driver', // Opsi otomatis dikunci sebagai driver
        ]);

        return redirect()->route('admin.drivers.index')->with('success', 'Driver berhasil ditambahkan.');
    }

    // Hapus Akun Driver
    public function destroy($id)
    {
        $driver = User::where('role', 'driver')->findOrFail($id);
        $driver->delete();

        return redirect()->route('admin.drivers.index')->with('success', 'Akun driver berhasil dihapus.');
    }
}
