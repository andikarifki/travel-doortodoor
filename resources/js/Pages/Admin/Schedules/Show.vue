<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    schedule: Object,
});

const formatDate = (dateStr) => {
    return new Date(dateStr).toLocaleString("id-ID", {
        dateStyle: "full",
        timeStyle: "short",
    });
};

const formatRupiah = (val) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    }).format(val);
};

const formatWA = (phone) => {
    let clean = phone.replace(/[^0-9]/g, "");
    if (clean.startsWith("0")) clean = "62" + clean.slice(1);
    return `https://wa.me/${clean}`;
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
                        {{ schedule.route.origin }} &rarr;
                        {{ schedule.route.destination }} |
                        {{ formatDate(schedule.departure_time) }}
                    </p>
                </div>
                <Link
                    :href="route('admin.schedules.index')"
                    class="text-xs text-gray-600 bg-gray-100 px-3 py-2 rounded-lg border"
                    >&larr; Kembali</Link
                >
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
                    <span class="font-bold text-gray-800"
                        >{{ schedule.vehicle.name }} ({{
                            schedule.vehicle.license_plate
                        }})</span
                    >
                </div>
                <div
                    class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm"
                >
                    <span class="text-xs text-gray-400 block"
                        >Sopir / Driver</span
                    >
                    <span class="font-bold text-emerald-600">{{
                        schedule.manifest?.driver?.name || "Belum Ditugaskan"
                    }}</span>
                </div>
                <div
                    class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm"
                >
                    <span class="text-xs text-gray-400 block"
                        >Total Pemesan</span
                    >
                    <span class="font-bold text-indigo-600"
                        >{{ schedule.bookings.length }} Transaksi</span
                    >
                </div>
            </div>

            <!-- Tabel Daftar Customer yang Mendaftar -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
            >
                <div class="p-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">
                        Daftar Customer (Guest Booking)
                    </h3>
                </div>

                <table class="w-full text-left border-collapse text-sm">
                    <thead class="bg-gray-50 text-gray-600 border-b">
                        <tr>
                            <th class="p-4">Kode Booking</th>
                            <th class="p-4">Nama Customer</th>
                            <th class="p-4">No. Kursi</th>
                            <th class="p-4">Alamat Jemput & Antar</th>
                            <th class="p-4">Total</th>
                            <th class="p-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="b in schedule.bookings" :key="b.id">
                            <td class="p-4 font-mono font-bold text-indigo-600">
                                {{ b.booking_code }}
                            </td>
                            <td class="p-4">
                                <div class="font-semibold text-gray-800">
                                    {{ b.customer_name }}
                                </div>
                                <a
                                    :href="formatWA(b.customer_phone)"
                                    target="_blank"
                                    class="text-xs text-emerald-600 hover:underline"
                                >
                                    💬 {{ b.customer_phone }}
                                </a>
                            </td>
                            <td class="p-4">
                                <span
                                    class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded font-bold"
                                >
                                    Kursi
                                    {{
                                        b.seats
                                            .map((s) => s.seat_number)
                                            .join(", ")
                                    }}
                                </span>
                            </td>
                            <td class="p-4 text-xs space-y-1">
                                <p>
                                    <span class="font-semibold text-emerald-700"
                                        >Jemput:</span
                                    >
                                    {{ b.pick_up_address }}
                                </p>
                                <p>
                                    <span class="font-semibold text-indigo-700"
                                        >Antar:</span
                                    >
                                    {{ b.drop_off_address }}
                                </p>
                            </td>
                            <td class="p-4 font-semibold text-gray-800">
                                {{ formatRupiah(b.total_amount) }}
                            </td>
                            <td class="p-4">
                                <span
                                    class="text-xs px-2.5 py-1 rounded-full font-semibold uppercase bg-amber-100 text-amber-800"
                                >
                                    {{ b.status }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="schedule.bookings.length === 0">
                            <td
                                colspan="6"
                                class="p-6 text-center text-gray-400"
                            >
                                Belum ada customer yang mendaftar pada jadwal
                                ini.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
