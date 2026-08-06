<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    routes: Array,
    vehicles: Array,
    drivers: Array,
});

const form = useForm({
    route_id: "",
    vehicle_id: "",
    departure_time: "",
    driver_id: "",
});

const submitSchedule = () => {
    form.post(route("admin.schedules.store"));
};
</script>

<template>
    <Head title="Tambah Jadwal Keberangkatan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Buat Jadwal Keberangkatan Baru
                </h2>
                <Link
                    :href="route('admin.schedules.index')"
                    class="text-xs text-gray-600 hover:text-gray-900 bg-gray-100 px-3 py-2 rounded-lg border"
                >
                    &larr; Kembali ke Daftar
                </Link>
            </div>
        </template>

        <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-100"
            >
                <form @submit.prevent="submitSchedule" class="space-y-4">
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 mb-1"
                            >Pilih Rute</label
                        >
                        <select
                            v-model="form.route_id"
                            class="w-full rounded-lg border-gray-300 text-sm"
                        >
                            <option value="" disabled>-- Pilih Rute --</option>
                            <option
                                v-for="r in routes"
                                :key="r.id"
                                :value="r.id"
                            >
                                {{ r.origin }} &rarr; {{ r.destination }}
                            </option>
                        </select>
                        <div
                            v-if="form.errors.route_id"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.route_id }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 mb-1"
                            >Pilih Armada</label
                        >
                        <select
                            v-model="form.vehicle_id"
                            class="w-full rounded-lg border-gray-300 text-sm"
                        >
                            <option value="" disabled>-- Pilih Mobil --</option>
                            <option
                                v-for="v in vehicles"
                                :key="v.id"
                                :value="v.id"
                            >
                                {{ v.name }} ({{ v.license_plate }})
                            </option>
                        </select>
                        <div
                            v-if="form.errors.vehicle_id"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.vehicle_id }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 mb-1"
                            >Waktu Keberangkatan</label
                        >
                        <input
                            v-model="form.departure_time"
                            type="datetime-local"
                            class="w-full rounded-lg border-gray-300 text-sm"
                        />
                        <div
                            v-if="form.errors.departure_time"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.departure_time }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 mb-1"
                            >Tugaskan Sopir/Driver</label
                        >
                        <select
                            v-model="form.driver_id"
                            class="w-full rounded-lg border-gray-300 text-sm"
                        >
                            <option value="">-- Belum Ditugaskan --</option>
                            <option
                                v-for="d in drivers"
                                :key="d.id"
                                :value="d.id"
                            >
                                {{ d.name }}
                            </option>
                        </select>
                    </div>

                    <div class="pt-4 flex justify-end gap-2">
                        <Link
                            :href="route('admin.schedules.index')"
                            class="bg-gray-200 text-gray-700 text-xs px-4 py-2.5 rounded-lg font-semibold"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-indigo-600 text-white font-semibold text-xs px-5 py-2.5 rounded-lg hover:bg-indigo-700"
                        >
                            Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
