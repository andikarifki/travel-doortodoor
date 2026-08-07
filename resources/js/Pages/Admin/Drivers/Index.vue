<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    drivers: Array,
});

const deleteDriver = (id) => {
    if (confirm("Yakin ingin menghapus akun driver ini?")) {
        router.delete(route("admin.drivers.destroy", id));
    }
};
</script>

<template>
    <Head title="Kelola Sopir / Driver" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Daftar Sopir / Driver
                </h2>
                <Link
                    :href="route('admin.drivers.create')"
                    class="bg-indigo-600 text-white font-semibold text-xs px-4 py-2.5 rounded-lg hover:bg-indigo-700 shadow-sm"
                >
                    + Tambah Driver Baru
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
                            <th class="p-4">Nama Driver</th>
                            <th class="p-4">Email / Akun Login</th>
                            <th class="p-4">Role</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="d in drivers" :key="d.id">
                            <td class="p-4 font-semibold text-gray-800">
                                {{ d.name }}
                            </td>
                            <td class="p-4 text-gray-600">{{ d.email }}</td>
                            <td class="p-4">
                                <span
                                    class="bg-emerald-100 text-emerald-800 text-xs px-2.5 py-1 rounded-full font-semibold uppercase"
                                >
                                    {{ d.role }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button
                                    @click="deleteDriver(d.id)"
                                    class="text-xs text-red-600 hover:underline font-semibold"
                                >
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        <tr v-if="drivers.length === 0">
                            <td
                                colspan="4"
                                class="p-6 text-center text-gray-400"
                            >
                                Belum ada akun driver yang terdaftar.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
