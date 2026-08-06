<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    vehicles: Array,
});

const deleteVehicle = (id) => {
    if (confirm("Yakin ingin menghapus armada ini?")) {
        router.delete(route("admin.vehicles.destroy", id));
    }
};
</script>

<template>
    <Head title="Kelola Armada" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Daftar Armada / Mobil
                </h2>
                <Link
                    :href="route('admin.vehicles.create')"
                    class="bg-indigo-600 text-white font-semibold text-xs px-4 py-2.5 rounded-lg hover:bg-indigo-700 shadow-sm"
                >
                    + Tambah Armada
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
                            <th class="p-4">Nama Armada</th>
                            <th class="p-4">Plat Nomor</th>
                            <th class="p-4">Kapasitas Kursi</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="v in vehicles" :key="v.id">
                            <td class="p-4 font-semibold text-gray-800">
                                {{ v.name }}
                            </td>
                            <td class="p-4 font-mono text-indigo-600">
                                {{ v.license_plate }}
                            </td>
                            <td class="p-4">{{ v.capacity }} Kursi</td>
                            <td class="p-4 text-right space-x-2">
                                <Link
                                    :href="route('admin.vehicles.edit', v.id)"
                                    class="text-xs text-amber-600 hover:underline font-semibold"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="deleteVehicle(v.id)"
                                    class="text-xs text-red-600 hover:underline font-semibold"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        <tr v-if="vehicles.length === 0">
                            <td
                                colspan="4"
                                class="p-6 text-center text-gray-400"
                            >
                                Belum ada data armada.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
