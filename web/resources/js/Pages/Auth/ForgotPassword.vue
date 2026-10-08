<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout
        title="Lupa Kata Sandi? 🔒"
        subtitle="Masukkan alamat email akun Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi."
        active-page="forgot-password"
    >
        <Head title="Lupa Kata Sandi" />

        <div v-if="status" class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ status }}</span>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <InputLabel for="email" value="Alamat Email Terdaftar" class="font-semibold text-gray-700 text-sm mb-1.5" />
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
                    <span>{{ form.processing ? 'Mengirim Tautan...' : 'Kirim Link Reset Password' }}</span>
                    <svg v-if="!form.processing" class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>

            <div class="pt-4 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-600 font-sans">
                    Ingat kata sandi Anda?
                    <Link 
                        :href="route('login')" 
                        class="font-bold text-shop-primary hover:text-shop-secondary hover:underline transition-colors ml-1"
                    >
                        Kembali ke Login
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>
