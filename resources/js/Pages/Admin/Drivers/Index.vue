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
                <div>
                    <h2
                        class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight"
                    >
                        Daftar Sopir / Driver
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Kelola seluruh akun driver travel yang terdaftar
                    </p>
                </div>
                <Link
                    :href="route('admin.drivers.create')"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition-colors"
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
                    <span>Tambah Driver</span>
                </Link>
            </div>
        </template>

        <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 overflow-hidden"
            >
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead
                            class="bg-gray-50/80 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700 text-xs font-semibold uppercase tracking-wider"
                        >
                            <tr>
                                <th class="py-3.5 px-5">Nama Driver</th>
                                <th class="py-3.5 px-5">Email / Akun Login</th>
                                <th class="py-3.5 px-5">Role</th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-100 dark:divide-gray-700/60"
                        >
                            <tr
                                v-for="d in drivers"
                                :key="d.id"
                                class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors"
                            >
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 flex items-center justify-center font-bold text-xs uppercase shrink-0"
                                        >
                                            {{ d.name.charAt(0) }}
                                        </div>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-gray-100"
                                        >
                                            {{ d.name }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    class="py-4 px-5 text-gray-600 dark:text-gray-300"
                                >
                                    {{ d.email }}
                                </td>
                                <td class="py-4 px-5">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/40 uppercase"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full bg-emerald-500"
                                        ></span>
                                        {{ d.role }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-right space-x-2">
                                    <!-- Tombol Edit -->
                                    <Link
                                        :href="
                                            route('admin.drivers.edit', d.id)
                                        "
                                        class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300 px-2.5 py-1.5 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-950/40 transition-colors"
                                    >
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
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                            />
                                        </svg>
                                        Edit
                                    </Link>

                                    <!-- Tombol Hapus -->
                                    <button
                                        @click="deleteDriver(d.id)"
                                        class="inline-flex items-center gap-1 text-xs font-medium text-rose-600 hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300 px-2.5 py-1.5 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors"
                                    >
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
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            />
                                        </svg>
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="drivers.length === 0">
                                <td
                                    colspan="4"
                                    class="py-12 text-center text-gray-400 dark:text-gray-500"
                                >
                                    Belum ada akun driver yang terdaftar.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
