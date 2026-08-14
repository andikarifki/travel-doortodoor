<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { computed } from "vue";

const props = defineProps({
    schedules: Array,
});

const formatDate = (dateStr) => {
    return new Date(dateStr).toLocaleString("id-ID", {
        weekday: "short",
        day: "numeric",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

// Statistik sederhana untuk rangkuman atas
const totalSchedules = computed(() => props.schedules?.length || 0);
const totalBookings = computed(
    () =>
        props.schedules?.reduce(
            (acc, curr) => acc + (curr.bookings?.length || 0),
            0,
        ) || 0,
);
const unassignedDrivers = computed(
    () => props.schedules?.filter((s) => !s.manifest?.driver).length || 0,
);

// Fungsi Hapus dengan Konfirmasi
const deleteSchedule = (id) => {
    if (
        confirm(
            "Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.",
        )
    ) {
        router.delete(route("admin.schedules.destroy", id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Kelola Jadwal & Driver" />

    <AuthenticatedLayout>
        <template #header>
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <div>
                    <h2
                        class="font-bold text-2xl text-gray-900 dark:text-white tracking-tight"
                    >
                        Jadwal Keberangkatan
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Kelola alokasi armada, driver, dan pemesan travel.
                    </p>
                </div>
                <Link
                    :href="route('admin.schedules.create')"
                    class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-medium text-sm px-4 py-2.5 rounded-xl transition duration-150 shadow-sm shadow-indigo-200 dark:shadow-none shrink-0"
                >
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>
                    Buat Jadwal Baru
                </Link>
            </div>
        </template>

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- 📊 STATISTIK RINGKAS -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div
                    class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center gap-4"
                >
                    <div
                        class="p-3 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg"
                    >
                        📅
                    </div>
                    <div>
                        <p
                            class="text-xs font-medium text-gray-500 dark:text-gray-400"
                        >
                            Total Jadwal
                        </p>
                        <p
                            class="text-xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ totalSchedules }}
                        </p>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center gap-4"
                >
                    <div
                        class="p-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg"
                    >
                        🎟️
                    </div>
                    <div>
                        <p
                            class="text-xs font-medium text-gray-500 dark:text-gray-400"
                        >
                            Total Pemesan
                        </p>
                        <p
                            class="text-xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ totalBookings }} Penumpang
                        </p>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700/60 shadow-sm flex items-center gap-4"
                >
                    <div
                        class="p-3 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg"
                    >
                        ⚠️
                    </div>
                    <div>
                        <p
                            class="text-xs font-medium text-gray-500 dark:text-gray-400"
                        >
                            Belum Ada Driver
                        </p>
                        <p
                            class="text-xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ unassignedDrivers }} Jadwal
                        </p>
                    </div>
                </div>
            </div>

            <!-- 📋 TABEL JADWAL -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 overflow-hidden"
            >
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr
                                class="bg-gray-50/75 dark:bg-gray-700/40 text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700 text-xs font-semibold uppercase tracking-wider"
                            >
                                <th class="p-4 pl-6">Rute Perjalanan</th>
                                <th class="p-4">Waktu Keberangkatan</th>
                                <th class="p-4">Armada</th>
                                <th class="p-4">Driver Assigned</th>
                                <th class="p-4 text-center">Okupansi</th>
                                <th class="p-4 pr-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-100 dark:divide-gray-700/60"
                        >
                            <tr
                                v-for="s in schedules"
                                :key="s.id"
                                class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition duration-150"
                            >
                                <!-- Rute -->
                                <td class="p-4 pl-6">
                                    <div
                                        class="flex items-center gap-2 font-semibold text-gray-900 dark:text-white"
                                    >
                                        <span>{{ s.route.origin }}</span>
                                        <svg
                                            class="w-4 h-4 text-gray-400 shrink-0"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M14 5l7 7m0 0l-7 7m7-7H3"
                                            />
                                        </svg>
                                        <span>{{ s.route.destination }}</span>
                                    </div>
                                </td>

                                <!-- Waktu Keberangkatan -->
                                <td
                                    class="p-4 whitespace-nowrap text-gray-600 dark:text-gray-300"
                                >
                                    <div class="flex items-center gap-1.5">
                                        <svg
                                            class="w-4 h-4 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                        <span>{{
                                            formatDate(s.departure_time)
                                        }}</span>
                                    </div>
                                </td>

                                <!-- Armada -->
                                <td class="p-4 whitespace-nowrap">
                                    <div
                                        class="font-medium text-gray-800 dark:text-gray-200"
                                    >
                                        {{ s.vehicle.name }}
                                    </div>
                                    <div
                                        class="text-xs text-gray-400 font-mono"
                                    >
                                        {{ s.vehicle.license_plate }}
                                    </div>
                                </td>

                                <!-- Driver -->
                                <td class="p-4 whitespace-nowrap">
                                    <span
                                        v-if="s.manifest?.driver"
                                        class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 text-xs px-2.5 py-1 rounded-full font-medium border border-emerald-200/50 dark:border-emerald-800/40"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full bg-emerald-500"
                                        ></span>
                                        {{ s.manifest.driver.name }}
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 text-xs px-2.5 py-1 rounded-full font-medium border border-amber-200/50 dark:border-amber-800/40"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full bg-amber-500"
                                        ></span>
                                        Belum Ditetapkan
                                    </span>
                                </td>

                                <!-- Total Booking -->
                                <td class="p-4 text-center whitespace-nowrap">
                                    <span
                                        class="inline-block bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs px-2.5 py-1 rounded-lg font-bold"
                                    >
                                        👥 {{ s.bookings.length }} Orang
                                    </span>
                                </td>

                                <!-- Action Buttons -->
                                <td
                                    class="p-4 pr-6 text-right whitespace-nowrap"
                                >
                                    <div
                                        class="flex items-center justify-end gap-2"
                                    >
                                        <!-- Detail Customer -->
                                        <Link
                                            :href="
                                                route(
                                                    'admin.schedules.show',
                                                    s.id,
                                                )
                                            "
                                            class="inline-flex items-center gap-1 text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-600 dark:bg-indigo-900/40 dark:hover:bg-indigo-900/60 dark:text-indigo-300 font-medium px-2.5 py-1.5 rounded-lg transition"
                                            title="Lihat Customer"
                                        >
                                            <span>Detail</span>
                                            <svg
                                                class="w-3.5 h-3.5"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 5l7 7-7 7"
                                                />
                                            </svg>
                                        </Link>

                                        <!-- Edit -->
                                        <Link
                                            :href="
                                                route(
                                                    'admin.schedules.edit',
                                                    s.id,
                                                )
                                            "
                                            class="p-1.5 text-gray-500 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition"
                                            title="Edit Jadwal"
                                        >
                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                />
                                            </svg>
                                        </Link>

                                        <!-- Hapus -->
                                        <button
                                            @click="deleteSchedule(s.id)"
                                            class="p-1.5 text-gray-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition"
                                            title="Hapus Jadwal"
                                        >
                                            <svg
                                                class="w-4 h-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- State ketika data kosong -->
                            <tr v-if="schedules.length === 0">
                                <td colspan="6" class="p-12 text-center">
                                    <div
                                        class="flex flex-col items-center justify-center"
                                    >
                                        <div
                                            class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 mb-3"
                                        >
                                            🚐
                                        </div>
                                        <p
                                            class="text-base font-semibold text-gray-700 dark:text-gray-300"
                                        >
                                            Belum ada jadwal dibuat
                                        </p>
                                        <p
                                            class="text-xs text-gray-400 mt-1 max-w-sm"
                                        >
                                            Jadwal keberangkatan yang Anda
                                            tambahkan akan muncul di sini secara
                                            terstruktur.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
