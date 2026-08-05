<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripManifest extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'driver_id',
        'status',
    ];

    // Relasi: Manifest milik 1 Jadwal
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // Relasi: Driver diambil dari tabel users bawaan Breeze
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
