<script setup>
import { ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
    schedule: Object,
    bookedSeats: Array,
});

const form = useForm({
    schedule_id: props.schedule.id,
    customer_name: "",
    customer_phone: "",
    customer_email: "",
    pick_up_address: "",
    drop_off_address: "",
    selected_seats: [],
});

const toggleSeat = (seatNum) => {
    if (props.bookedSeats.includes(seatNum)) return;

    const index = form.selected_seats.indexOf(seatNum);
    if (index > -1) {
        form.selected_seats.splice(index, 1);
    } else {
        form.selected_seats.push(seatNum);
    }
};

const submitBooking = () => {
    form.post(route("booking.store"));
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
    <Head title="Form Pemesanan Tiket" />

    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
        <div
            class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6"
        >
            <h1 class="text-xl font-bold text-gray-800">
                Detail Pemesanan Door to Door
            </h1>
            <p class="text-sm text-gray-500 mb-6">
                Rute:
                <span class="font-semibold text-gray-700"
                    >{{ schedule.route.origin }} &rarr;
                    {{ schedule.route.destination }}</span
                >
            </p>

            <form @submit.prevent="submitBooking" class="space-y-6">
                <!-- 1. Pilih Kursi -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2"
                        >Pilih Nomor Kursi</label
                    >
                    <div class="grid grid-cols-5 gap-2 max-w-xs">
                        <button
                            v-for="seatNum in schedule.vehicle.capacity"
                            :key="seatNum"
                            type="button"
                            @click="toggleSeat(seatNum)"
                            :disabled="bookedSeats.includes(seatNum)"
                            :class="[
                                'py-2.5 text-sm font-semibold rounded-lg border transition text-center',
                                bookedSeats.includes(seatNum)
                                    ? 'bg-gray-200 text-gray-400 border-gray-200 cursor-not-allowed'
                                    : form.selected_seats.includes(seatNum)
                                      ? 'bg-indigo-600 text-white border-indigo-600'
                                      : 'bg-white text-gray-700 border-gray-300 hover:border-indigo-500',
                            ]"
                        >
                            {{ seatNum }}
                        </button>
                    </div>
                    <span
                        v-if="form.errors.selected_seats"
                        class="text-xs text-red-500 mt-1 block"
                        >{{ form.errors.selected_seats }}</span
                    >
                </div>

                <!-- 2. Data Pemesan -->
                <div class="space-y-3 pt-4 border-t border-gray-100">
                    <h3 class="font-bold text-sm text-gray-800">
                        Data Pemesan (Guest)
                    </h3>

                    <div>
                        <input
                            v-model="form.customer_name"
                            type="text"
                            placeholder="Nama Lengkap"
                            class="w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        />
                        <span
                            v-if="form.errors.customer_name"
                            class="text-xs text-red-500 mt-1 block"
                            >{{ form.errors.customer_name }}</span
                        >
                    </div>

                    <div>
                        <input
                            v-model="form.customer_phone"
                            type="text"
                            placeholder="Nomor WhatsApp (misal: 08123456789)"
                            class="w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        />
                        <span
                            v-if="form.errors.customer_phone"
                            class="text-xs text-red-500 mt-1 block"
                            >{{ form.errors.customer_phone }}</span
                        >
                    </div>

                    <div>
                        <input
                            v-model="form.customer_email"
                            type="email"
                            placeholder="Email (Opsional)"
                            class="w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        />
                    </div>
                </div>

                <!-- 3. Alamat Jemput & Antar -->
                <div class="space-y-3 pt-4 border-t border-gray-100">
                    <h3 class="font-bold text-sm text-gray-800">
                        Alamat Penjemputan & Pengantaran
                    </h3>

                    <div>
                        <textarea
                            v-model="form.pick_up_address"
                            rows="2"
                            placeholder="Alamat Jemput Lengkap (Lengkap dengan patokan)"
                            class="w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        ></textarea>
                        <span
                            v-if="form.errors.pick_up_address"
                            class="text-xs text-red-500 mt-1 block"
                            >{{ form.errors.pick_up_address }}</span
                        >
                    </div>

                    <div>
                        <textarea
                            v-model="form.drop_off_address"
                            rows="2"
                            placeholder="Alamat Antar/Tujuan Lengkap"
                            class="w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        ></textarea>
                        <span
                            v-if="form.errors.drop_off_address"
                            class="text-xs text-red-500 mt-1 block"
                            >{{ form.errors.drop_off_address }}</span
                        >
                    </div>

                    <!-- Informational Alert Box -->
                    <div
                        class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-900 flex items-start gap-2.5"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 text-amber-600 flex-shrink-0 mt-0.5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        <div>
                            <span class="font-bold">Informasi Biaya Area:</span>
                            <p class="mt-0.5 leading-relaxed">
                                Apabila titik penjemputan / pengantaran di luar
                                jangkauan area utama, akan ada
                                <strong
                                    >penyesuaian harga / biaya tambahan</strong
                                >.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total & Submit -->
                <div
                    class="pt-6 border-t border-gray-100 flex justify-between items-center"
                >
                    <div>
                        <p class="text-xs text-gray-500">Total Pembayaran</p>
                        <p class="text-xl font-bold text-indigo-600">
                            {{
                                formatRupiah(
                                    form.selected_seats.length *
                                        schedule.route.base_price,
                                )
                            }}
                        </p>
                        <p
                            class="text-[11px] text-amber-600 font-medium mt-0.5"
                        >
                            *Belum termasuk penyesuaian harga / biaya tambahan
                            luar jangkauan (jika ada).
                        </p>
                    </div>

                    <button
                        type="submit"
                        :disabled="
                            form.processing || form.selected_seats.length === 0
                        "
                        class="bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 text-white font-semibold text-sm px-6 py-3 rounded-xl transition"
                    >
                        Proses Booking Tiket
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
