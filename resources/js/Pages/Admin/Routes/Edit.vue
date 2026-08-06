<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    routeData: Object,
});

const form = useForm({
    origin: props.routeData?.origin || "",
    destination: props.routeData?.destination || "",
    base_price: props.routeData?.base_price || "",
});

const submitRoute = () => {
    form.put(route("admin.routes.update", props.routeData.id));
};
</script>

<template>
    <Head title="Edit Rute Travel" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Rute Travel
                </h2>
                <Link
                    :href="route('admin.routes.index')"
                    class="text-xs text-gray-600 hover:text-gray-900 bg-gray-100 px-3 py-2 rounded-lg border"
                >
                    &larr; Kembali
                </Link>
            </div>
        </template>

        <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-100"
            >
                <form @submit.prevent="submitRoute" class="space-y-4">
                    <div>
                        <label
                            class="block text-xs font-semibold text-gray-600 mb-1"
                            >Kota Asal (Origin)</label
                        >
                        <input
                            v-model="form.origin"
                            type="text"
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                            required
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
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                            required
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
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                            required
                        />
                        <span
                            v-if="form.errors.base_price"
                            class="text-red-500 text-xs mt-1 block"
                            >{{ form.errors.base_price }}</span
                        >
                    </div>

                    <div class="pt-4 flex justify-end gap-2">
                        <Link
                            :href="route('admin.routes.index')"
                            class="bg-gray-200 text-gray-700 text-xs px-4 py-2.5 rounded-lg font-semibold hover:bg-gray-300"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-indigo-600 text-white font-semibold text-xs px-5 py-2.5 rounded-lg hover:bg-indigo-700 disabled:opacity-50"
                        >
                            {{
                                form.processing ? "Menyimpan..." : "Update Rute"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
