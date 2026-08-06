<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const form = useForm({
    name: "",
    license_plate: "",
    capacity: 10,
});

const submit = () => {
    form.post(route("admin.vehicles.store"));
};
</script>

<template>
    <Head title="Tambah Armada" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Armada Baru
                </h2>
                <Link
                    :href="route('admin.vehicles.index')"
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
                            >Nama Mobil / Tipe</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="misal: HiAce Commuter"
                            class="w-full rounded-lg border-gray-300 text-sm"
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
                            >Plat Nomor</label
                        >
                        <input
                            v-model="form.license_plate"
                            type="text"
                            placeholder="misal: H 1234 AB"
                            class="w-full rounded-lg border-gray-300 text-sm"
                        />
                        <span
                            v-if="form.errors.license_plate"
                            class="text-red-500 text-xs mt-1 block"
                            >{{ form.errors.license_plate }}</span
                        >
                    </div>

                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 mb-1"
                            >Kapasitas Kursi</label
                        >
                        <input
                            v-model="form.capacity"
                            type="number"
                            min="1"
                            max="60"
                            class="w-full rounded-lg border-gray-300 text-sm"
                        />
                        <span
                            v-if="form.errors.capacity"
                            class="text-red-500 text-xs mt-1 block"
                            >{{ form.errors.capacity }}</span
                        >
                    </div>

                    <div class="pt-4 flex justify-end gap-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-indigo-600 text-white font-semibold text-xs px-5 py-2.5 rounded-lg hover:bg-indigo-700"
                        >
                            Simpan Armada
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
