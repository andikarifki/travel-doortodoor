<?php

namespace Database\Seeders;

use App\Models\Route;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TravelSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Travel',
            'email' => 'admin@travel.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Budi (Sopir HiAce)',
            'email' => 'driver@travel.com',
            'password' => Hash::make('password'),
            'role' => 'driver',
        ]);
        // 1. Tambah Vehicle
        $vehicle = Vehicle::create([
            'name' => 'Toyota HiAce Commuter',
            'license_plate' => 'H 1234 AB',
            'capacity' => 10,
        ]);

        // 2. Tambah Route
        $route = Route::create([
            'origin' => 'Semarang',
            'destination' => 'Yogyakarta',
            'base_price' => 150000,
        ]);

        // 3. Tambah Schedule (Untuk besok jam 08:00)
        Schedule::create([
            'route_id' => $route->id,
            'vehicle_id' => $vehicle->id,
            'departure_time' => now()->addDay()->setTime(8, 0, 0),
        ]);
    }
}
