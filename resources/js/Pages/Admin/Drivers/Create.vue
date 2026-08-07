<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const form = useForm({
    name: "",
    email: "",
    password: "",
});

const submit = () => {
    form.post(route("admin.drivers.store"));
};
</script>

<template>
    <Head title="Tambah Driver Baru" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Driver Baru
                </h2>
                <Link
                    :href="route('admin.drivers.index')"
                    class="text-xs text-gray-600 bg-gray-100 px-3 py-2 rounded-lg border"
                    >&larr; Kembali</Link
                >
            </div>
        </template>

        <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-100"
            >
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 mb-1"
                            >Nama Lengkap Driver</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="misal: Budi Santoso"
                            class="w-full rounded-lg border-gray-300 text-sm"
                            required
                        />
                        <span
                            v-if="form.errors.name"
                            class="text-red-500 text-xs mt-1 block"
                            >{{ form.errors.name }}</span
                        >
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 mb-1"
                            >Email (Untuk Login Driver)</label
                        >
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="budi@travel.com"
                            class="w-full rounded-lg border-gray-300 text-sm"
                            required
                        />
                        <span
                            v-if="form.errors.email"
                            class="text-red-500 text-xs mt-1 block"
                            >{{ form.errors.email }}</span
                        >
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 mb-1"
                            >Password</label
                        >
                        <input
                            v-model="form.password"
                            type="password"
                            placeholder="••••••••"
                            class="w-full rounded-lg border-gray-300 text-sm"
                            required
                        />
                        <span
                            v-if="form.errors.password"
                            class="text-red-500 text-xs mt-1 block"
                            >{{ form.errors.password }}</span
                        >
                    </div>

                    <div class="pt-4 flex justify-end gap-2">
                        <Link
                            :href="route('admin.drivers.index')"
                            class="bg-gray-200 text-gray-700 text-xs px-4 py-2.5 rounded-lg font-semibold"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-indigo-600 text-white font-semibold text-xs px-5 py-2.5 rounded-lg hover:bg-indigo-700"
                        >
                            Simpan Driver
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
