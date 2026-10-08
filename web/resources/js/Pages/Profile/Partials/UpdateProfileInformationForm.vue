<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                title: 'Profil Diperbarui!',
                text: 'Informasi nama dan email Anda berhasil disimpan.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
};
</script>

<template>
    <section class="max-w-2xl">
        <header class="flex items-start gap-3.5 mb-6">
            <div class="w-10 h-10 rounded-xl bg-shop-primary/10 text-shop-primary flex items-center justify-center font-bold text-base shrink-0 mt-0.5">
                <i class="fa-solid fa-user-pen"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 tracking-tight">
                    Informasi Profil Akun
                </h2>
                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                    Perbarui nama lengkap dan alamat email yang digunakan untuk masuk ke akun Meraki Labs.
                </p>
            </div>
        </header>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="name" value="Nama Lengkap" class="font-semibold text-xs mb-1" />
                <div class="relative">
                    <TextInput
                        id="name"
                        type="text"
                        class="block w-full pl-10 rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-user text-xs"></i>
                    </div>
                </div>
                <InputError class="mt-1 text-xs" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Alamat Email" class="font-semibold text-xs mb-1" />
                <div class="relative">
                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full pl-10 rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm font-mono"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-envelope text-xs"></i>
                    </div>
                </div>
                <InputError class="mt-1 text-xs" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="p-3.5 bg-amber-50 rounded-xl border border-amber-200/80 text-xs text-amber-800">
                <p>
                    Alamat email Anda belum diverifikasi.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="font-bold underline text-shop-primary hover:text-shop-secondary ml-1"
                    >
                        Kirim ulang tautan verifikasi email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-xs font-bold text-emerald-700"
                >
                    Tautan verifikasi baru telah dikirim ke kotak masuk email Anda.
                </div>
            </div>

            <div class="pt-3">
                <PrimaryButton :disabled="form.processing">
                    <i class="fa-solid fa-check text-xs"></i>
                    <span>Simpan Perubahan</span>
                </PrimaryButton>
            </div>
        </form>
    </section>
</template>
