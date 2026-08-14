<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'admin', 'driver', 'passenger', dll.
        'phone',
    ];

    // Relasi jika user ber-role driver membawa jadwal
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'driver_id');
    }
}
