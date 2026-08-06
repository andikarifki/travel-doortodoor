<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";

const props = defineProps({
    schedules: Array,
    filters: Object,
});

const origin = ref(props.filters.origin || "");
const destination = ref(props.filters.destination || "");

const handleSearch = () => {
    router.get(
        "/",
        { origin: origin.value, destination: destination.value },
        { preserveState: true },
    );
};

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
</script>

<template>
    <Head title="Pesan Travel Door-to-Door" />

    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-lg">
                <h1 class="text-2xl font-bold">Travel Door to Door</h1>
                <p class="text-indigo-100 text-sm mt-1">
                    Pesan tiket travel tanpa perlu daftar akun!
                </p>

                <!-- Form Filter -->
                <form
                    @submit.prevent="handleSearch"
                    class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3"
                >
                    <input
                        v-model="origin"
                        type="text"
                        placeholder="Kota Asal (mis: Semarang)"
                        class="rounded-xl border-none text-gray-800 text-sm focus:ring-2 focus:ring-indigo-400"
                    />
                    <input
                        v-model="destination"
                        type="text"
                        placeholder="Kota Tujuan (mis: Jogja)"
                        class="rounded-xl border-none text-gray-800 text-sm focus:ring-2 focus:ring-indigo-400"
                    />
                    <button
                        type="submit"
                        class="bg-indigo-900 hover:bg-indigo-950 text-white font-semibold rounded-xl text-sm py-2 px-4 transition"
                    >
                        Cari Jadwal
                    </button>
                </form>
            </div>

            <!-- List Jadwal -->
            <div class="space-y-4">
                <h2 class="text-lg font-bold text-gray-800">
                    Jadwal Keberangkatan
                </h2>

                <div
                    v-if="schedules.length === 0"
                    class="bg-white rounded-xl p-8 text-center text-gray-500 shadow-sm"
                >
                    Belum ada jadwal keberangkatan yang tersedia saat ini.
                </div>

                <div
                    v-for="item in schedules"
                    :key="item.id"
                    class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:shadow-md transition"
                >
                    <div>
                        <div
                            class="flex items-center gap-2 text-indigo-600 font-bold text-lg"
                        >
                            <span>{{ item.route.origin }}</span>
                            <span>&rarr;</span>
                            <span>{{ item.route.destination }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            Jam:
                            <span class="font-semibold text-gray-800">{{
                                formatDate(item.departure_time)
                            }}</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Armada: {{ item.vehicle.name }} (Sisa
                            {{ item.available_seats }} kursi)
                        </p>
                    </div>

                    <div
                        class="w-full sm:w-auto flex sm:flex-col justify-between sm:items-end items-center border-t sm:border-t-0 pt-3 sm:pt-0 border-gray-100"
                    >
                        <span class="text-lg font-bold text-emerald-600">{{
                            formatRupiah(item.route.base_price)
                        }}</span>
                        <Link
                            :href="route('booking.show', item.id)"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2.5 rounded-lg transition mt-2"
                        >
                            Pilih Kursi
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
