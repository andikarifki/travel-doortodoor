<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const form = useForm({
    origin: "",
    destination: "",
    base_price: "",
});

const submit = () => {
    form.post(route("admin.routes.store"));
};
</script>

<template>
    <Head title="Tambah Rute" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Rute Baru
                </h2>
                <Link
                    :href="route('admin.routes.index')"
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
                            >Kota Asal (Origin)</label
                        >
                        <input
                            v-model="form.origin"
                            type="text"
                            placeholder="misal: Semarang"
                            class="w-full rounded-lg border-gray-300 text-sm"
                        />
                        <span
                            v-if="form.errors.origin"
                            class="text-red-500 text-xs mt-1 block"
                            >{{ form.errors.origin }}</span
                        >
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 mb-1"
                            >Kota Tujuan (Destination)</label
                        >
                        <input
                            v-model="form.destination"
                            type="text"
                            placeholder="misal: Yogyakarta"
                            class="w-full rounded-lg border-gray-300 text-sm"
                        />
                        <span
                            v-if="form.errors.destination"
                            class="text-red-500 text-xs mt-1 block"
                            >{{ form.errors.destination }}</span
                        >
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 mb-1"
                            >Harga Dasar (Rp)</label
                        >
                        <input
                            v-model="form.base_price"
                            type="number"
                            placeholder="misal: 150000"
                            class="w-full rounded-lg border-gray-300 text-sm"
                        />
                        <span
                            v-if="form.errors.base_price"
                            class="text-red-500 text-xs mt-1 block"
                            >{{ form.errors.base_price }}</span
                        >
                    </div>

                    <div class="pt-4 flex justify-end gap-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-indigo-600 text-white font-semibold text-xs px-5 py-2.5 rounded-lg hover:bg-indigo-700"
                        >
                            Simpan Rute
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
