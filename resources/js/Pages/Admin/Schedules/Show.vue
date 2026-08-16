<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    schedule: {
        type: Object,
        required: true,
        default: () => ({
            bookings: [],
            route: {},
            vehicle: {},
            manifest: {},
        }),
    },
});

// Format Tanggal Indonesia
const formatDate = (dateStr) => {
    if (!dateStr) return "-";
    return new Date(dateStr).toLocaleString("id-ID", {
        dateStyle: "full",
        timeStyle: "short",
    });
};

// Format Rupiah
const formatRupiah = (val) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    }).format(val || 0);
};

// Format Link WhatsApp Konfirmasi E-Tiket
const formatWA = (booking) => {
    let clean = (booking.customer_phone || "").replace(/[^0-9]/g, "");

    if (clean.startsWith("0")) {
        clean = "62" + clean.slice(1);
    } else if (clean.startsWith("8")) {
        clean = "62" + clean;
    }

    // Ambil daftar nomor kursi
    const seatNumbers =
        booking.seats && booking.seats.length > 0
            ? booking.seats.map((s) => s.seat_number).join(", ")
            : "-";

    const origin = props.schedule.route?.origin || "-";
    const destination = props.schedule.route?.destination || "-";

    // Template Pesan Konfirmasi / E-Tiket
    const message =
        `*[KONFIRMASI E-TIKET TRAVEL]*\n\n` +
        `Halo *${booking.customer_name}*,\n` +
        `Pemesanan travel Anda telah dikonfirmasi!\n\n` +
        `📋 *DETAIL PEMESANAN:*\n` +
        `• Kode Booking: #${booking.booking_code}\n` +
        `• Rute: ${origin} ➔ ${destination}\n` +
        `• Jadwal: ${formatDate(props.schedule.departure_time)}\n` +
        `• No. Kursi: ${seatNumbers}\n` +
        `• Total Bayar: ${formatRupiah(booking.total_amount)}\n\n` +
        `📍 *LOKASI PENJEMPUTAN:*\n` +
        `${booking.pick_up_address}\n\n` +
        `Driver kami akan menghubungi Anda menjelang jam keberangkatan. Terima kasih!`;

    return `https://wa.me/${clean}?text=${encodeURIComponent(message)}`;
};

// Fungsi Ubah Status Pembayaran tanpa reload / scroll jumping
const changeStatus = (bookingId, newStatus) => {
    router.patch(
        route("admin.bookings.update-status", bookingId),
        { status: newStatus },
        {
            preserveScroll: true,
            preserveState: true,
        },
    );
};
</script>

<template>
    <Head title="Detail Pemesan & Manifest" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2
                        class="font-semibold text-xl text-gray-800 leading-tight"
                    >
                        Detail Pemesan
                    </h2>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ schedule.route?.origin }} &rarr;
                        {{ schedule.route?.destination }} |
                        {{ formatDate(schedule.departure_time) }}
                    </p>
                </div>
                <Link
                    :href="route('admin.schedules.index')"
                    class="text-xs text-gray-600 bg-gray-100 px-3 py-2 rounded-lg border hover:bg-gray-200 transition"
                >
                    &larr; Kembali
                </Link>
            </div>
        </template>

        <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Information Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div
                    class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm"
                >
                    <span class="text-xs text-gray-400 block"
                        >Armada / Mobil</span
                    >
                    <span class="font-bold text-gray-800">
                        {{ schedule.vehicle?.name || "Belum Set" }}
                        <template v-if="schedule.vehicle?.license_plate">
                            ({{ schedule.vehicle.license_plate }})
                        </template>
                    </span>
                </div>
                <div
                    class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm"
                >
                    <span class="text-xs text-gray-400 block"
                        >Sopir / Driver</span
                    >
                    <span class="font-bold text-emerald-600">
                        {{
                            schedule.manifest?.driver?.name ||
                            "Belum Ditugaskan"
                        }}
                    </span>
                </div>
                <div
                    class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm"
                >
                    <span class="text-xs text-gray-400 block"
                        >Total Pemesan</span
                    >
                    <span class="font-bold text-indigo-600">
                        {{ schedule.bookings?.length || 0 }} Transaksi
                    </span>
                </div>
            </div>

            <!-- Tabel Daftar Customer -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
            >
                <div class="p-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Daftar Customer</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead class="bg-gray-50 text-gray-600 border-b">
                            <tr>
                                <th class="p-4">Kode Booking</th>
                                <th class="p-4">Nama Customer</th>
                                <th class="p-4">No. Kursi</th>
                                <th class="p-4">Alamat Jemput & Antar</th>
                                <th class="p-4">Total</th>
                                <th class="p-4">Status Pembayaran</th>
                                <th class="p-4">Aksi WhatsApp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="b in schedule.bookings"
                                :key="b.id"
                                class="hover:bg-gray-50/50 transition-colors"
                            >
                                <td
                                    class="p-4 font-mono font-bold text-indigo-600 whitespace-nowrap"
                                >
                                    {{ b.booking_code }}
                                </td>
                                <td class="p-4">
                                    <div class="font-semibold text-gray-800">
                                        {{ b.customer_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ b.customer_phone }}
                                    </div>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <span
                                        class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded font-bold"
                                    >
                                        Kursi
                                        {{
                                            b.seats
                                                ?.map((s) => s.seat_number)
                                                .join(", ") || "-"
                                        }}
                                    </span>
                                </td>
                                <td class="p-4 text-xs space-y-1 min-w-[200px]">
                                    <p>
                                        <span
                                            class="font-semibold text-emerald-700"
                                            >Jemput:</span
                                        >
                                        {{ b.pick_up_address }}
                                    </p>
                                    <p>
                                        <span
                                            class="font-semibold text-indigo-700"
                                            >Antar:</span
                                        >
                                        {{ b.drop_off_address }}
                                    </p>
                                </td>
                                <td
                                    class="p-4 font-semibold text-gray-800 whitespace-nowrap"
                                >
                                    {{ formatRupiah(b.total_amount) }}
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <select
                                        :value="b.status"
                                        @change="
                                            changeStatus(
                                                b.id,
                                                $event.target.value,
                                            )
                                        "
                                        class="text-xs font-semibold rounded-lg border-gray-300 py-1 px-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors cursor-pointer"
                                        :class="{
                                            'bg-amber-50 text-amber-700 border-amber-300':
                                                b.status === 'pending',
                                            'bg-emerald-50 text-emerald-700 border-emerald-300':
                                                b.status === 'confirmed',
                                            'bg-red-50 text-red-700 border-red-300':
                                                b.status === 'cancelled',
                                        }"
                                    >
                                        <option value="pending">
                                            🟡 Pending (Belum Bayar)
                                        </option>
                                        <option value="confirmed">
                                            🟢 Confirmed (Lunas)
                                        </option>
                                        <option value="cancelled">
                                            🔴 Cancelled (Batal)
                                        </option>
                                    </select>
                                </td>
                                <td class="p-4 whitespace-nowrap">
                                    <a
                                        :href="formatWA(b)"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-medium rounded-lg shadow-sm transition"
                                    >
                                        💬 Kirim Tiket WA
                                    </a>
                                </td>
                            </tr>
                            <tr
                                v-if="
                                    !schedule.bookings ||
                                    schedule.bookings.length === 0
                                "
                            >
                                <td
                                    colspan="7"
                                    class="p-6 text-center text-gray-400"
                                >
                                    Belum ada customer yang mendaftar pada
                                    jadwal ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
