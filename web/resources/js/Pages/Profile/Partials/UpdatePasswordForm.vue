<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const showPassword = ref(false);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            Swal.fire({
                title: 'Password Berhasil Diubah!',
                text: 'Kata sandi akun Anda telah diperbarui dengan aman.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section class="max-w-2xl">
        <header class="flex items-start gap-3.5 mb-6">
            <div class="w-10 h-10 rounded-xl bg-shop-primary/10 text-shop-primary flex items-center justify-center font-bold text-base shrink-0 mt-0.5">
                <i class="fa-solid fa-lock"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 tracking-tight">
                    Keamanan & Kata Sandi
                </h2>
                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                    Pastikan akun Anda terlindungi dengan menggunakan kata sandi yang panjang, unik, dan acak.
                </p>
            </div>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-4">
            <div>
                <InputLabel for="current_password" value="Kata Sandi Saat Ini" class="font-semibold text-xs mb-1" />
                <div class="relative">
                    <TextInput
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        :type="showPassword ? 'text' : 'password'"
                        class="block w-full pr-10 rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm"
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />
                    <button 
                        type="button" 
                        @click="showPassword = !showPassword" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-700 transition-colors"
                    >
                        <i class="fa-solid text-xs" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <InputError :message="form.errors.current_password" class="mt-1 text-xs" />
            </div>

            <div>
                <InputLabel for="password" value="Kata Sandi Baru" class="font-semibold text-xs mb-1" />
                <div class="relative">
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        class="block w-full pr-10 rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm"
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                </div>
                <InputError :message="form.errors.password" class="mt-1 text-xs" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Konfirmasi Kata Sandi Baru" class="font-semibold text-xs mb-1" />
                <div class="relative">
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        :type="showPassword ? 'text' : 'password'"
                        class="block w-full pr-10 rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm"
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                </div>
                <InputError :message="form.errors.password_confirmation" class="mt-1 text-xs" />
            </div>

            <div class="pt-3">
                <PrimaryButton :disabled="form.processing">
                    <i class="fa-solid fa-shield-halved text-xs"></i>
                    <span>Perbarui Kata Sandi</span>
                </PrimaryButton>
            </div>
        </form>
    </section>
</template>
