<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

// Set default value agar tidak error jika prop bernilai undefined / null
const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            total_bookings: 0,
            pending_bookings: 0,
            today_schedules: 0,
            total_drivers: 0,
        }),
    },
    recentBookings: {
        type: Array,
        default: () => [],
    },
});

// Penanganan aman untuk format Rupiah jika val bernilai null/undefined
const formatRupiah = (val) => {
    if (val === null || val === undefined || isNaN(val)) return "Rp 0";
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    }).format(val);
};
</script>

<template>
    <Head title="Dashboard Admin" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard Control Panel
            </h2>
        </template>

        <div class="py-8 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- 1. Menu Akses Cepat (Quick Navigation Cards) -->
                <div>
                    <h3
                        class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3"
                    >
                        Menu Kelola Data
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Menu Schedules -->
                        <Link
                            :href="route('admin.schedules.index')"
                            class="p-5 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-500 hover:shadow-md transition group"
                        >
                            <div
                                class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-indigo-600 group-hover:text-white transition"
                            >
                                📅
                            </div>
                            <h4
                                class="font-bold text-gray-800 group-hover:text-indigo-600 transition"
                            >
                                Jadwal Travel
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">
                                Kelola jam & kuota keberangkatan
                            </p>
                        </Link>

                        <!-- Menu Drivers -->
                        <Link
                            :href="route('admin.drivers.index')"
                            class="p-5 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-500 hover:shadow-md transition group"
                        >
                            <div
                                class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-emerald-600 group-hover:text-white transition"
                            >
                                👨‍✈️
                            </div>
                            <h4
                                class="font-bold text-gray-800 group-hover:text-indigo-600 transition"
                            >
                                Akun Sopir / Driver
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">
                                Kelola data & akun login sopir
                            </p>
                        </Link>

                        <!-- Menu Vehicles -->
                        <Link
                            :href="route('admin.vehicles.index')"
                            class="p-5 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-500 hover:shadow-md transition group"
                        >
                            <div
                                class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-amber-600 group-hover:text-white transition"
                            >
                                🚐
                            </div>
                            <h4
                                class="font-bold text-gray-800 group-hover:text-indigo-600 transition"
                            >
                                Armada / Mobil
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">
                                Kelola data mobil & kapasitas kursi
                            </p>
                        </Link>

                        <!-- Menu Routes -->
                        <Link
                            :href="route('admin.routes.index')"
                            class="p-5 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-500 hover:shadow-md transition group"
                        >
                            <div
                                class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center mb-3 group-hover:bg-rose-600 group-hover:text-white transition"
                            >
                                🗺️
                            </div>
                            <h4
                                class="font-bold text-gray-800 group-hover:text-indigo-600 transition"
                            >
                                Rute Perjalanan
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">
                                Kelola kota asal, tujuan & harga
                            </p>
                        </Link>
                    </div>
                </div>

                <!-- 2. Ringkasan Statistik -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div
                        class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm"
                    >
                        <span class="text-xs text-gray-500 font-medium"
                            >Total Pemesanan</span
                        >
                        <p class="text-2xl font-black text-gray-800 mt-1">
                            {{ stats?.total_bookings ?? 0 }}
                        </p>
                    </div>
                    <div
                        class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm"
                    >
                        <span class="text-xs text-amber-600 font-medium"
                            >Pending Pembayaran</span
                        >
                        <p class="text-2xl font-black text-amber-600 mt-1">
                            {{ stats?.pending_bookings ?? 0 }}
                        </p>
                    </div>
                    <div
                        class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm"
                    >
                        <span class="text-xs text-indigo-600 font-medium"
                            >Jadwal Hari Ini</span
                        >
                        <p class="text-2xl font-black text-indigo-600 mt-1">
                            {{ stats?.today_schedules ?? 0 }}
                        </p>
                    </div>
                    <div
                        class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm"
                    >
                        <span class="text-xs text-emerald-600 font-medium"
                            >Total Driver</span
                        >
                        <p class="text-2xl font-black text-emerald-600 mt-1">
                            {{ stats?.total_drivers ?? 0 }}
                        </p>
                    </div>
                </div>

                <!-- 3. Pemesanan Terbaru -->
                <div
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
                >
                    <h3 class="text-base font-bold text-gray-800 mb-4">
                        Pemesanan Terbaru
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50"
                            >
                                <tr>
                                    <th class="px-4 py-3">Kode</th>
                                    <th class="px-4 py-3">Customer</th>
                                    <th class="px-4 py-3">Rute</th>
                                    <th class="px-4 py-3">Total</th>
                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                <tr
                                    v-for="b in recentBookings"
                                    :key="b.id"
                                    class="hover:bg-gray-50"
                                >
                                    <td
                                        class="px-4 py-3 font-mono font-semibold text-indigo-600"
                                    >
                                        #{{ b.booking_code }}
                                    </td>
                                    <td
                                        class="px-4 py-3 font-medium text-gray-800"
                                    >
                                        {{ b.customer_name }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ b.schedule?.route?.origin ?? "-" }} →
                                        {{
                                            b.schedule?.route?.destination ??
                                            "-"
                                        }}
                                    </td>
                                    <td class="px-4 py-3 font-semibold">
                                        {{ formatRupiah(b.total_amount) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs font-semibold capitalize"
                                            :class="{
                                                'bg-amber-100 text-amber-800':
                                                    b.status === 'pending',
                                                'bg-emerald-100 text-emerald-800':
                                                    b.status === 'confirmed',
                                                'bg-rose-100 text-rose-800':
                                                    b.status === 'cancelled',
                                            }"
                                        >
                                            {{ b.status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr
                                    v-if="
                                        !recentBookings ||
                                        recentBookings.length === 0
                                    "
                                >
                                    <td
                                        colspan="5"
                                        class="px-4 py-6 text-center text-gray-400"
                                    >
                                        Belum ada pemesanan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
