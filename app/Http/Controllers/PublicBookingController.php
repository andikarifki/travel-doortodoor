<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
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
                $bookedSeatsCount = $schedule->bookings->flatMap->seats->count();
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

        $bookedSeatNumbers = $schedule->bookings
            ->where('status', '!=', 'cancelled')
            ->flatMap->seats
            ->pluck('seat_number')
            ->toArray();

        return Inertia::render('Public/BookingForm', [
            'schedule' => $schedule,
            'bookedSeats' => $bookedSeatNumbers,
        ]);
    }

    // 3. Simpan Booking
    public function store(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'pick_up_address' => 'required|string',
            'drop_off_address' => 'required|string',
            'selected_seats' => 'required|array|min:1',
            'selected_seats.*' => 'integer|min:1',
        ]);

        $schedule = Schedule::with('route')->findOrFail($validated['schedule_id']);

        $totalSeats = count($validated['selected_seats']);
        $totalAmount = $totalSeats * $schedule->route->base_price;

        $booking = Booking::create([
            'booking_code' => 'TRV-'.strtoupper(Str::random(6)),
            'schedule_id' => $schedule->id,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'pick_up_address' => $validated['pick_up_address'],
            'drop_off_address' => $validated['drop_off_address'],
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        foreach ($validated['selected_seats'] as $seatNumber) {
            $booking->seats()->create([
                'seat_number' => $seatNumber,
            ]);
        }

        return redirect()->route('booking.success', $booking->booking_code);
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
}
