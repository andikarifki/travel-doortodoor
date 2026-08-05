<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin',
        'destination',
        'base_price',
    ];

    // Relasi: Satu rute punya banyak jadwal keberangkatan
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
