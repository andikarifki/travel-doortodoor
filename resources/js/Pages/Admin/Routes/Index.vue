<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    routes: Array,
});

const deleteRoute = (id) => {
    if (confirm("Yakin ingin menghapus rute ini?")) {
        router.delete(route("admin.routes.destroy", id));
    }
};

const formatRupiah = (val) => {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    }).format(val);
};
</script>

<template>
    <Head title="Kelola Rute" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Daftar Rute Travel
                </h2>
                <Link
                    :href="route('admin.routes.create')"
                    class="bg-indigo-600 text-white font-semibold text-xs px-4 py-2.5 rounded-lg hover:bg-indigo-700 shadow-sm"
                >
                    + Tambah Rute
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
                            <th class="p-4">Kota Asal (Origin)</th>
                            <th class="p-4">Kota Tujuan (Destination)</th>
                            <th class="p-4">Harga Dasar</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="r in routes" :key="r.id">
                            <td class="p-4 font-semibold text-gray-800">
                                {{ r.origin }}
                            </td>
                            <td class="p-4 font-semibold text-gray-800">
                                {{ r.destination }}
                            </td>
                            <td class="p-4 font-bold text-emerald-600">
                                {{ formatRupiah(r.base_price) }}
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <Link
                                    :href="route('admin.routes.edit', r.id)"
                                    class="text-xs text-amber-600 hover:underline font-semibold"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="deleteRoute(r.id)"
                                    class="text-xs text-red-600 hover:underline font-semibold"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        <tr v-if="routes.length === 0">
                            <td
                                colspan="4"
                                class="p-6 text-center text-gray-400"
                            >
                                Belum ada data rute.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
