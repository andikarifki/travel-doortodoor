<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>E-Tiket {{ $booking->booking_code }}</title>
    <style>
        body { font-family: sans-serif; color: #333; font-size: 12px; margin: 0; padding: 10px; }
        .ticket-box { border: 2px dashed #4f46e5; border-radius: 8px; padding: 15px; background: #fff; }
        .header-table { width: 100%; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 15px; }
        .title { font-size: 18px; font-weight: bold; color: #4f46e5; margin: 0; }
        .code { font-size: 14px; font-weight: bold; color: #111827; text-align: right; }
        .table-info { width: 100%; border-collapse: collapse; }
        .table-info td { padding: 5px 0; vertical-align: top; }
        .label { color: #6b7280; width: 35%; }
        .value { font-weight: bold; color: #111827; }
        .price-box { background: #ecfdf5; border: 1px solid #a7f3d0; padding: 10px; border-radius: 6px; margin-top: 15px; text-align: right; }
        .footer { margin-top: 20px; font-size: 10px; color: #9ca3af; text-align: center; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="ticket-box">
        <table class="header-table">
            <tr>
                <td>
                    <div class="title">E-TIKET FAZZA TRAVEL</div>
                    <small style="color: #6b7280;">Bukti Pemesanan Resmi</small>
                </td>
                <td class="code">
                    KODE BOOKING<br>
                    <span style="color: #4f46e5; font-size: 16px;">#{{ $booking->booking_code }}</span>
                </td>
            </tr>
        </table>

        <table class="table-info">
            <tr>
                <td class="label">Nama Pemesan</td>
                <td class="value">: {{ $booking->customer_name }} ({{ $booking->customer_phone }})</td>
            </tr>
            <tr>
                <td class="label">Rute Perjalanan</td>
                <!-- PERBAIKAN DI SINI: ganti titik menjadi arrow -> -->
                <td class="value">: {{ $booking->schedule->route->origin }} &rarr; {{ $booking->schedule->route->destination }}</td>
            </tr>
            <tr>
                <td class="label">Waktu Keberangkatan</td>
                <td class="value">: {{ date('d-m-Y H:i', strtotime($booking->schedule->departure_time)) }} WIB</td>
            </tr>
            <tr>
                <td class="label">Armada / Mobil</td>
                <td class="value">: {{ $booking->schedule->vehicle->name ?? '-' }} ({{ $booking->schedule->vehicle->license_plate ?? '-' }})</td>
            </tr>
            <tr>
                <td class="label">Nomor Kursi</td>
                <td class="value">: 
                    @if($booking->seats && count($booking->seats) > 0)
                        {{ implode(', ', $booking->seats->pluck('seat_number')->toArray()) }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Lokasi Penjemputan</td>
                <td class="value">: {{ $booking->pick_up_address }}</td>
            </tr>
            <tr>
                <td class="label">Lokasi Pengantaran</td>
                <td class="value">: {{ $booking->drop_off_address }}</td>
            </tr>
        </table>

        <div class="price-box">
            <span style="color: #065f46; font-size: 11px;">TOTAL PEMBAYARAN</span><br>
            <span style="font-size: 16px; font-weight: bold; color: #047857;">
                Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
            </span>
        </div>

        <div class="footer">
            Harap tunjukkan E-Tiket ini kepada driver saat penjemputan. Terima kasih.
        </div>
    </div>
</body>
</html>