<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'schedule_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'pick_up_address',
        'drop_off_address',
        'total_amount',
        'status',
    ];

    // Relasi: Pemesanan berada di 1 Jadwal
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // Relasi: Satu pemesanan bisa memesan beberapa nomor kursi
    public function seats()
    {
        return $this->hasMany(BookingSeat::class);
    }
}
