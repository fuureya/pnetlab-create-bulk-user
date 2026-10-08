<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import Swal from 'sweetalert2';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const page = usePage();

onMounted(() => {
    if (page.props.flash && page.props.flash.error) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian',
            text: page.props.flash.error,
            confirmButtonText: 'Oke',
            confirmButtonColor: '#BF070F'
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('verified') === '1') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Email Anda telah berhasil diverifikasi. Silakan login.',
            confirmButtonText: 'Oke',
            confirmButtonColor: '#BF070F'
        });
    }
});
</script>

<template>
    <GuestLayout
        title="Selamat Datang Kembali 👋"
        subtitle="Masuk ke akun Meraki Labs Anda untuk mengakses dashboard dan mengelola simulasi lab."
        active-page="login"
    >
        <Head title="Masuk ke Akun" />

        <!-- Status Alert -->
        <div 
            v-if="status" 
            class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2"
        >
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ status }}</span>
        </div>

        <!-- Voucher Quick Activation Banner -->
        <div class="mb-6 p-3.5 bg-gradient-to-r from-shop-primary/5 via-shop-primary/10 to-transparent border border-shop-primary/20 rounded-xl flex items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-shop-primary/10 flex items-center justify-center text-shop-primary text-sm font-bold shrink-0">
                    ⚡
                </div>
                <div>
                    <p class="font-bold text-gray-900 leading-tight">Punya Voucher Lab?</p>
                    <p class="text-gray-500 text-[11px]">Buka akses lab tanpa tunggu lama</p>
                </div>
            </div>
            <Link
                :href="route('aktivasi.index')"
                class="font-bold text-shop-primary hover:text-shop-secondary hover:underline shrink-0 text-xs inline-flex items-center gap-1 bg-white py-1.5 px-3 rounded-lg border border-shop-primary/20 shadow-sm"
            >
                Aktivasi →
            </Link>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="submit" class="space-y-5">
            <!-- Email Field -->
            <div>
                <InputLabel for="email" value="Alamat Email" class="font-semibold text-gray-700 text-sm mb-1.5" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full pl-11 h-12 rounded-xl border-gray-300 focus:border-shop-primary focus:ring-shop-primary text-gray-900 placeholder:text-gray-400 font-sans"
                        placeholder="nama@email.com"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                </div>
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <!-- Password Field -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <InputLabel for="password" value="Kata Sandi" class="font-semibold text-gray-700 text-sm" />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs font-semibold text-shop-primary hover:text-shop-secondary hover:underline transition-colors focus:outline-none"
                    >
                        Lupa kata sandi?
                    </Link>
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <TextInput
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        class="block w-full pl-11 pr-11 h-12 rounded-xl border-gray-300 focus:border-shop-primary focus:ring-shop-primary text-gray-900 placeholder:text-gray-400 font-sans"
                        placeholder="Masukkan kata sandi"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />
                    <button 
                        type="button" 
                        @click="showPassword = !showPassword" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-700 transition-colors focus:outline-none"
                        tabindex="-1"
                        :title="showPassword ? 'Sembunyikan Kata Sandi' : 'Tampilkan Kata Sandi'"
                    >
                        <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center cursor-pointer select-none">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2.5 text-sm font-medium text-gray-600">Ingat saya di perangkat ini</span>
                </label>
            </div>

            <!-- Submit Button with Loading State -->
            <div class="pt-2">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full h-12 flex items-center justify-center gap-2 rounded-xl font-poppins font-bold text-base text-white bg-shop-primary hover:bg-shop-secondary shadow-shop-md hover:shadow-shop-hover transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed hover:-translate-y-0.5 active:translate-y-0 group"
                >
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>{{ form.processing ? 'Memverifikasi Akun...' : 'Masuk Sekarang' }}</span>
                    <svg v-if="!form.processing" class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>

            <!-- Footer Register CTA -->
            <div class="pt-4 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-600 font-sans">
                    Belum memiliki akun?
                    <Link 
                        :href="route('register')" 
                        class="font-bold text-shop-primary hover:text-shop-secondary hover:underline transition-colors ml-1"
                    >
                        Daftar Akun Baru
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>
