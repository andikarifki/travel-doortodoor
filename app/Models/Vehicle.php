<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'license_plate',
        'capacity',
    ];

    // Relasi: Satu armada bisa dipakai di banyak jadwal
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
