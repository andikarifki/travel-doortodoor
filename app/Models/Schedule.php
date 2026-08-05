<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'vehicle_id',
        'departure_time',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
    ];

    // Relasi: Jadwal milik 1 Rute
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    // Relasi: Jadwal menggunakan 1 Armada
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // Relasi: Jadwal memiliki banyak pesanan (booking)
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Relasi: Jadwal memiliki 1 manifest perjalanan
    public function manifest()
    {
        return $this->hasOne(TripManifest::class);
    }
}
