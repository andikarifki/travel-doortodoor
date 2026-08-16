<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Seat; // Pastikan model Seat di-import jika digunakan terpisah
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PublicBookingController extends Controller
{
    // 1. Method index (wajib ada untuk route '/')
    public function index(Request $request)
    {
        $query = Schedule::with(['route', 'vehicle', 'bookings.seats']);

        if ($request->has('origin') && $request->has('destination')) {
            $query->whereHas('route', function ($q) use ($request) {
                $q->where('origin', 'like', '%'.$request->origin.'%')
                    ->where('destination', 'like', '%'.$request->destination.'%');
            });
        }

        $schedules = $query->where('departure_time', '>=', now())
            ->orderBy('departure_time', 'asc')
            ->get()
            ->map(function ($schedule) {
                $bookedSeatsCount = $schedule->bookings
                    ->where('status', '!=', 'cancelled')
                    ->flatMap->seats->count();
                    
                $schedule->available_seats = $schedule->vehicle->capacity - $bookedSeatsCount;

                return $schedule;
            });

        return Inertia::render('Public/Search', [
            'schedules' => $schedules,
            'filters' => $request->only(['origin', 'destination']),
        ]);
    }

    // 2. Form Pemesanan
    public function show(Schedule $schedule)
    {
        $schedule->load(['route', 'vehicle', 'bookings.seats']);

        $bookedSeatsCount = $schedule->bookings
            ->where('status', '!=', 'cancelled')
            ->flatMap->seats
            ->count();

        $availableSeatsCount = $schedule->vehicle->capacity - $bookedSeatsCount;

        return Inertia::render('Public/BookingForm', [
            'schedule' => $schedule,
            'availableSeats' => $availableSeatsCount,
        ]);
    }

    // 3. Simpan Booking & Redirect Langsung ke WhatsApp Admin
    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'pick_up_address' => 'required|string',
            'drop_off_address' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $schedule = Schedule::with(['route', 'vehicle', 'bookings.seats'])->findOrFail($validated['schedule_id']);

        // A. Cek Nomor Kursi yang Sudah Terisi
        $bookedSeatNumbers = $schedule->bookings
            ->where('status', '!=', 'cancelled')
            ->flatMap->seats
            ->pluck('seat_number')
            ->toArray();

        // B. Cari Kursi Kosong Secara Otomatis Berdasarkan Kapasitas Kendaraan
        $assignedSeats = [];
        for ($i = 1; $i <= $schedule->vehicle->capacity; $i++) {
            if (!in_array($i, $bookedSeatNumbers)) {
                $assignedSeats[] = $i;
            }

            if (count($assignedSeats) === (int) $validated['quantity']) {
                break;
            }
        }

        // Cek jika kursi yang tersedia tidak cukup
        if (count($assignedSeats) < (int) $validated['quantity']) {
            return redirect()->back()->withErrors([
                'quantity' => 'Sisa kursi tidak mencukupi untuk jumlah pemesanan ini.',
            ]);
        }

        $totalSeats = count($assignedSeats);
        $totalAmount = $totalSeats * $schedule->route->base_price;
        $bookingCode = 'TRV-'.strtoupper(Str::random(6));

        // C. Simpan data booking utama ke Database
        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'schedule_id' => $schedule->id,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'pick_up_address' => $validated['pick_up_address'],
            'drop_off_address' => $validated['drop_off_address'],
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        // D. Simpan nomor kursi otomatis ke relasi seats
        foreach ($assignedSeats as $seatNumber) {
            $booking->seats()->create([
                'seat_number' => $seatNumber,
            ]);
        }

        // E. Susun format teks pesan WhatsApp Admin
        $seatsText = implode(', ', $assignedSeats);
        $formattedPrice = 'Rp '.number_format($totalAmount, 0, ',', '.');
        $adminWaNumber = '62895380744368';

        $message = "*BOOKING TIKET BARU*\n";
        $message .= "--------------------------------------\n";
        $message .= "*Kode Booking:* #".$bookingCode."\n";
        $message .= "*Nama:* ".$validated['customer_name']."\n";
        $message .= "*No HP/WA:* ".$validated['customer_phone']."\n";
        $message .= "*Rute:* ".$schedule->route->origin." -> ".$schedule->route->destination."\n";
        $message .= "*Jumlah Tiket:* ".$totalSeats." Tiket (Kursi: No. ".$seatsText.")\n";
        $message .= "*Alamat Jemput:* ".$validated['pick_up_address']."\n";
        $message .= "*Alamat Antar:* ".$validated['drop_off_address']."\n";
        $message .= "*Total Tiket:* ".$formattedPrice."\n";
        $message .= "--------------------------------------\n";
        $message .= "_Mohon konfirmasi ketersediaan titik jemput/antar serta penyesuaian harga / biaya tambahan luar jangkauan (jika ada)._";

        $waUrl = 'https://wa.me/'.$adminWaNumber.'?text='.urlencode($message);

        // F. Redirect eksternal Inertia langsung ke WhatsApp Admin
        return Inertia::location($waUrl);
    }

    // 4. Halaman Sukses
    public function success($code)
    {
        $booking = Booking::with(['schedule.route', 'schedule.vehicle', 'seats'])
            ->where('booking_code', $code)
            ->firstOrFail();

        return Inertia::render('Public/Success', [
            'booking' => $booking,
        ]);
    }

    // 5. Cetak E-Tiket PDF
    public function downloadTicket($booking_code)
    {
        ini_set('memory_limit', '256M');

        try {
            $booking = Booking::with(['schedule.route', 'schedule.vehicle', 'seats'])
                ->where('booking_code', $booking_code)
                ->firstOrFail();

            $pdf = Pdf::loadView('pdf.ticket', compact('booking'))
                ->setPaper('a4', 'portrait')
                ->setOption([
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'sans-serif',
                ]);

            return $pdf->stream("E-Tiket-{$booking->booking_code}.pdf");

        } catch (Exception $e) {
            return response('Gagal memproses E-Tiket PDF: '.$e->getMessage(), 500);
        }
    }
}