<script setup>
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    booking: Object,
});

const formatRupiah = (val) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    }).format(val);
};

const formatDate = (dateStr) => {
    return new Date(dateStr).toLocaleString("id-ID", {
        dateStyle: "full",
        timeStyle: "short",
    });
};

// URL WhatsApp Admin untuk konfirmasi pembayaran
const whatsappUrl = () => {
    const adminPhone = "6281234567890"; // Ganti dengan nomor WA Admin Travel
    const text =
        `Halo Admin, saya ingin konfirmasi pembayaran untuk booking travel.\n\n` +
        `*Kode Booking:* ${props.booking.booking_code}\n` +
        `*Nama:* ${props.booking.customer_name}\n` +
        `*Rute:* ${props.booking.schedule.route.origin} -> ${props.booking.schedule.route.destination}\n` +
        `*Total Bayar:* ${formatRupiah(props.booking.total_amount)}\n\n` +
        `Berikut saya sertakan bukti transfernya:`;

    return `https://wa.me/${adminPhone}?text=${encodeURIComponent(text)}`;
};
</script>

<template>
    <Head title="Booking Berhasil" />

    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div
            class="max-w-xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
        >
            <!-- Header Sukses -->
            <div class="bg-emerald-600 p-6 text-center text-white">
                <div
                    class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3"
                >
                    <svg
                        class="w-6 h-6 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </div>
                <h1 class="text-xl font-bold">Pemesanan Tiket Berhasil!</h1>
                <p class="text-xs text-emerald-100 mt-1">
                    Kode Booking Anda:
                    <span class="font-mono font-bold text-white text-sm">{{
                        booking.booking_code
                    }}</span>
                </p>
            </div>

            <div class="p-6 space-y-6">
                <!-- Detail Perjalanan -->
                <div class="bg-gray-50 p-4 rounded-xl space-y-2 text-sm">
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Rute</span>
                        <span class="font-semibold text-gray-800"
                            >{{ booking.schedule.route.origin }} &rarr;
                            {{ booking.schedule.route.destination }}</span
                        >
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Waktu Keberangkatan</span>
                        <span class="font-semibold text-gray-800">{{
                            formatDate(booking.schedule.departure_time)
                        }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-gray-500">Nomor Kursi</span>
                        <span class="font-bold text-indigo-600">
                            {{
                                booking.seats
                                    .map((s) => s.seat_number)
                                    .join(", ")
                            }}
                        </span>
                    </div>
                    <div class="flex justify-between pt-1">
                        <span class="text-gray-500">Total Pembayaran</span>
                        <span class="font-bold text-emerald-600 text-base">{{
                            formatRupiah(booking.total_amount)
                        }}</span>
                    </div>
                </div>

                <!-- Informasi Rekening Bank -->
                <div
                    class="border border-indigo-100 bg-indigo-50/50 p-4 rounded-xl"
                >
                    <h3
                        class="text-xs font-bold text-indigo-900 uppercase tracking-wider mb-2"
                    >
                        Petunjuk Pembayaran Transfer Bank
                    </h3>
                    <div class="space-y-1 text-xs text-gray-700">
                        <p>
                            Silakan transfer ke salah satu rekening resmi kami:
                        </p>
                        <div
                            class="bg-white p-2.5 rounded-lg border my-2 space-y-1"
                        >
                            <p class="font-semibold text-gray-800">
                                BCA:
                                <span class="font-mono text-indigo-600"
                                    >1234-5678-90</span
                                >
                                a/n Travel Mandiri
                            </p>
                            <p class="font-semibold text-gray-800">
                                Mandiri:
                                <span class="font-mono text-indigo-600"
                                    >137-000-9876-543</span
                                >
                                a/n Travel Mandiri
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Button Konfirmasi WhatsApp -->
                <div class="space-y-3 pt-2">
                    <a
                        :href="whatsappUrl()"
                        target="_blank"
                        class="w-full bg-emerald-600 text-white font-semibold text-sm py-3 px-4 rounded-xl hover:bg-emerald-700 transition flex items-center justify-center gap-2 shadow-sm"
                    >
                        <span>💬 Konfirmasi Pembayaran via WhatsApp</span>
                    </a>

                    <Link
                        :href="route('booking.search')"
                        class="block text-center text-xs text-gray-500 hover:text-gray-800"
                    >
                        &larr; Kembali ke Halaman Utama
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
