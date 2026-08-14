<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    driver: {
        type: Object,
        required: true,
    },
});

// Inisialisasi form dengan data awal dari props
const form = useForm({
    name: props.driver.name,
    email: props.driver.email,
    password: "", // Dikosongkan agar tidak mengubah password kecuali diisi
});

const submit = () => {
    form.put(route("admin.drivers.update", props.driver.id), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Edit Driver" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Edit Driver: {{ driver.name }}
                </h2>
                <Link
                    :href="route('admin.drivers.index')"
                    class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    &larr; Kembali
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-2xl dark:bg-gray-800"
                >
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Nama Driver -->
                        <div>
                            <InputLabel for="name" value="Nama Lengkap" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
                            />
                        </div>

                        <!-- Email -->
                        <div>
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                required
                                autocomplete="username"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.email"
                            />
                        </div>

                        <!-- Password (Opsional) -->
                        <div>
                            <InputLabel
                                for="password"
                                value="Password Baru (Opsional)"
                            />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                autocomplete="new-password"
                                placeholder="Biarkan kosong jika tidak ingin mengubah password"
                            />
                            <p
                                class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                            >
                                Kosongkan kolom ini jika Anda tidak berniat
                                mengganti password driver.
                            </p>
                            <InputError
                                class="mt-2"
                                :message="form.errors.password"
                            />
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex items-center justify-end gap-3 pt-4">
                            <Link
                                :href="route('admin.drivers.index')"
                                class="rounded-xl px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200"
                            >
                                Batal
                            </Link>
                            <PrimaryButton :disabled="form.processing">
                                {{
                                    form.processing
                                        ? "Menyimpan..."
                                        : "Simpan Perubahan"
                                }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
