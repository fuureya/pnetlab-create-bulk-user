<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const showPassword = ref(false);
const showPasswordConfirmation = ref(false);
</script>

<template>
    <GuestLayout
        title="Buat Akun Baru"
        subtitle="Daftar akun sekarang untuk mulai praktikum di simulator lab Meraki Labs."
        active-page="register"
    >
        <Head title="Daftar Akun Baru" />

        <form @submit.prevent="submit" class="space-y-4">
            <!-- Name Field -->
            <div>
                <InputLabel for="name" value="Nama Lengkap" class="font-semibold text-gray-700 text-sm mb-1.5" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7 7z" />
                        </svg>
                    </div>
                    <TextInput
                        id="name"
                        type="text"
                        class="block w-full pl-11 h-12 rounded-xl border-gray-300 focus:border-shop-primary focus:ring-shop-primary text-gray-900 placeholder:text-gray-400 font-sans"
                        placeholder="Nama lengkap Anda"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                </div>
                <InputError class="mt-1.5" :message="form.errors.name" />
            </div>

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
                        autocomplete="username"
                    />
                </div>
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <!-- Password Field -->
            <div>
                <InputLabel for="password" value="Kata Sandi" class="font-semibold text-gray-700 text-sm mb-1.5" />
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
                        placeholder="Minimal 8 karakter"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />
                    <button 
                        type="button" 
                        @click="showPassword = !showPassword" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-700 transition-colors focus:outline-none"
                        tabindex="-1"
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

            <!-- Password Confirmation Field -->
            <div>
                <InputLabel for="password_confirmation" value="Konfirmasi Kata Sandi" class="font-semibold text-gray-700 text-sm mb-1.5" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <TextInput
                        id="password_confirmation"
                        :type="showPasswordConfirmation ? 'text' : 'password'"
                        class="block w-full pl-11 pr-11 h-12 rounded-xl border-gray-300 focus:border-shop-primary focus:ring-shop-primary text-gray-900 placeholder:text-gray-400 font-sans"
                        placeholder="Ulangi kata sandi"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                    <button 
                        type="button" 
                        @click="showPasswordConfirmation = !showPasswordConfirmation" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-700 transition-colors focus:outline-none"
                        tabindex="-1"
                    >
                        <svg v-if="!showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
            </div>

            <!-- Submit Button with Loading State -->
            <div class="pt-3">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full h-12 flex items-center justify-center gap-2 rounded-xl font-poppins font-bold text-base text-white bg-shop-primary hover:bg-shop-secondary shadow-shop-md hover:shadow-shop-hover transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed hover:-translate-y-0.5 active:translate-y-0 group"
                >
                    <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>{{ form.processing ? 'Membuat Akun...' : 'Daftar Sekarang' }}</span>
                    <svg v-if="!form.processing" class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>

            <!-- Footer Login CTA -->
            <div class="pt-4 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-600 font-sans">
                    Sudah memiliki akun?
                    <Link 
                        :href="route('login')" 
                        class="font-bold text-shop-primary hover:text-shop-secondary hover:underline transition-colors ml-1"
                    >
                        Masuk di Sini
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>
