<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PublicBookingController extends Controller
{
    /**
     * Helper function untuk menghitung sisa kursi secara aman
     */
    private function calculateAvailableSeats(Schedule $schedule): int
    {
        $bookedSeatsCount = $schedule->bookings
            ->where('status', '!=', 'cancelled')
            ->flatMap->seats
            ->count();

        $capacity = $schedule->vehicle?->capacity ?? 0;

        return max(0, $capacity - $bookedSeatsCount);
    }

    // 1. Method index (Halaman Pencarian Jadwal)
    public function index(Request $request)
    {
        $query = Schedule::with(['route', 'vehicle', 'bookings.seats']);

        if ($request->filled('origin') && $request->filled('destination')) {
            $query->whereHas('route', function ($q) use ($request) {
                $q->where('origin', 'like', '%'.$request->origin.'%')
                  ->where('destination', 'like', '%'.$request->destination.'%');
            });
        }

        $schedules = $query->where('departure_time', '>=', now())
            ->orderBy('departure_time', 'asc')
            ->get()
            ->map(function ($schedule) {
                $schedule->available_seats = $this->calculateAvailableSeats($schedule);
                return $schedule;
            });

        return Inertia::render('Public/Search', [
            'schedules' => $schedules,
            'filters'   => $request->only(['origin', 'destination']),
        ]);
    }

    // 2. Form Pemesanan
    public function show(Schedule $schedule)
    {
        $schedule->load(['route', 'vehicle', 'bookings.seats']);

        $availableSeatsCount = $this->calculateAvailableSeats($schedule);

        return Inertia::render('Public/BookingForm', [
            'schedule'       => $schedule,
            'availableSeats' => (int) $availableSeatsCount,
        ]);
    }

    // 3. Simpan Booking & Redirect Langsung ke WhatsApp Admin
    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id'      => 'required|exists:schedules,id',
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_email'   => 'nullable|email|max:255',
            'pick_up_address'  => 'required|string',
            'drop_off_address' => 'required|string',
            'quantity'         => 'required|integer|min:1',
        ]);

        try {
            // Gunakan Transaction & Lock untuk mencegah double-booking di millisecond yang sama
            $waUrl = DB::transaction(function () use ($validated) {
                $schedule = Schedule::with(['route', 'vehicle', 'bookings.seats'])
                    ->lockForUpdate()
                    ->findOrFail($validated['schedule_id']);

                // A. Cek Nomor Kursi yang Sudah Terisi
                $bookedSeatNumbers = $schedule->bookings
                    ->where('status', '!=', 'cancelled')
                    ->flatMap->seats
                    ->pluck('seat_number')
                    ->toArray();

                // B. Cari Kursi Kosong Secara Otomatis
                $capacity = $schedule->vehicle?->capacity ?? 0;
                $assignedSeats = [];

                for ($i = 1; $i <= $capacity; $i++) {
                    if (!in_array($i, $bookedSeatNumbers)) {
                        $assignedSeats[] = $i;
                    }

                    if (count($assignedSeats) === (int) $validated['quantity']) {
                        break;
                    }
                }

                // Cek ketersediaan kursi
                if (count($assignedSeats) < (int) $validated['quantity']) {
                    throw new Exception('Sisa kursi tidak mencukupi untuk jumlah pemesanan ini.');
                }

                $totalSeats = count($assignedSeats);
                $basePrice = $schedule->route?->base_price ?? 0;
                $totalAmount = $totalSeats * $basePrice;
                $bookingCode = 'TRV-'.strtoupper(Str::random(6));

                // C. Simpan data booking
                $booking = Booking::create([
                    'booking_code'    => $bookingCode,
                    'schedule_id'     => $schedule->id,
                    'customer_name'   => $validated['customer_name'],
                    'customer_phone'  => $validated['customer_phone'],
                    'customer_email'  => $validated['customer_email'] ?? null,
                    'pick_up_address' => $validated['pick_up_address'],
                    'drop_off_address'=> $validated['drop_off_address'],
                    'total_amount'    => $totalAmount,
                    'status'          => 'pending',
                ]);

                // D. Simpan nomor kursi
                foreach ($assignedSeats as $seatNumber) {
                    $booking->seats()->create([
                        'seat_number' => $seatNumber,
                    ]);
                }

                // E. Susun Format Teks WhatsApp Admin
                $seatsText = implode(', ', $assignedSeats);
                $formattedPrice = 'Rp '.number_format($totalAmount, 0, ',', '.');
                $adminWaNumber = config('app.admin_wa_number', '62895380744368');
                $origin = $schedule->route?->origin ?? '-';
                $destination = $schedule->route?->destination ?? '-';

                $message = "*BOOKING TIKET BARU*\n";
                $message .= "--------------------------------------\n";
                $message .= "*Kode Booking:* #".$bookingCode."\n";
                $message .= "*Nama:* ".$validated['customer_name']."\n";
                $message .= "*No HP/WA:* ".$validated['customer_phone']."\n";
                $message .= "*Rute:* ".$origin." -> ".$destination."\n";
                $message .= "*Jumlah Tiket:* ".$totalSeats." Tiket (Kursi: No. ".$seatsText.")\n";
                $message .= "*Alamat Jemput:* ".$validated['pick_up_address']."\n";
                $message .= "*Alamat Antar:* ".$validated['drop_off_address']."\n";
                $message .= "*Total Tiket:* ".$formattedPrice."\n";
                $message .= "--------------------------------------\n";
                $message .= "_Mohon konfirmasi ketersediaan titik jemput/antar serta penyesuaian harga / biaya tambahan luar jangkauan (jika ada)._";

                return 'https://wa.me/'.$adminWaNumber.'?text='.urlencode($message);
            });

            // F. Redirect eksternal ke WA
            return Inertia::location($waUrl);

        } catch (Exception $e) {
            return redirect()->back()->withErrors([
                'quantity' => $e->getMessage(),
            ]);
        }
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
                    'defaultFont'     => 'sans-serif',
                ]);

            return $pdf->stream("E-Tiket-{$booking->booking_code}.pdf");

        } catch (Exception $e) {
            return response('Gagal memproses E-Tiket PDF: '.$e->getMessage(), 500);
        }
    }
}