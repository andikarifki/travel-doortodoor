<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    schedules: Array,
});

const formatDate = (dateStr) => {
    return new Date(dateStr).toLocaleString("id-ID", {
        dateStyle: "medium",
        timeStyle: "short",
    });
};
</script>

<template>
    <Head title="Kelola Jadwal & Driver" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Daftar Jadwal Keberangkatan
                </h2>
                <Link
                    :href="route('admin.schedules.create')"
                    class="bg-indigo-600 text-white font-semibold text-xs px-4 py-2.5 rounded-lg hover:bg-indigo-700 shadow-sm"
                >
                    + Buat Jadwal Baru
                </Link>
            </div>
        </template>

        <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
            >
                <table class="w-full text-left border-collapse text-sm">
                    <thead class="bg-gray-50 text-gray-600 border-b">
                        <tr>
                            <th class="p-4">Rute</th>
                            <th class="p-4">Waktu</th>
                            <th class="p-4">Armada</th>
                            <th class="p-4">Driver</th>
                            <th class="p-4">Total Booking</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="s in schedules" :key="s.id">
                            <td class="p-4 font-semibold text-indigo-600">
                                {{ s.route.origin }} &rarr;
                                {{ s.route.destination }}
                            </td>
                            <td class="p-4">
                                {{ formatDate(s.departure_time) }}
                            </td>
                            <td class="p-4">
                                {{ s.vehicle.name }} ({{
                                    s.vehicle.license_plate
                                }})
                            </td>
                            <td class="p-4">
                                <span
                                    v-if="s.manifest?.driver"
                                    class="bg-emerald-100 text-emerald-800 text-xs px-2.5 py-1 rounded-full font-semibold"
                                >
                                    {{ s.manifest.driver.name }}
                                </span>
                                <span
                                    v-else
                                    class="text-xs text-gray-400 italic"
                                >
                                    Belum Ada Driver
                                </span>
                            </td>
                            <td class="p-4 font-bold">
                                {{ s.bookings.length }} Pemesan
                            </td>
                            <td class="p-4 text-right">
                                <Link
                                    :href="route('admin.schedules.show', s.id)"
                                    class="text-xs bg-indigo-50 text-indigo-600 font-semibold px-3 py-1.5 rounded-lg border border-indigo-100 hover:bg-indigo-100"
                                >
                                    Lihat Customer &rarr;
                                </Link>
                                <Link
                                    :href="route('admin.schedules.edit', s.id)"
                                    class="text-xs text-amber-600 hover:underline font-semibold"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="deleteSchedule(s.id)"
                                    class="text-xs text-red-600 hover:underline font-semibold"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        <tr v-if="schedules.length === 0">
                            <td
                                colspan="6"
                                class="p-6 text-center text-gray-400"
                            >
                                Belum ada jadwal yang dibuat.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
