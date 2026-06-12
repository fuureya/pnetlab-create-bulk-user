<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    username: '',
    password: '',
});

const submit = () => {
    form.post(route('aktivasi.activate'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Aktivasi Voucher" />

    <div class="min-h-screen bg-shop-background font-sans text-gray-800 selection:bg-shop-primary selection:text-white flex flex-col">
        
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-shop-surface/90 backdrop-blur-md border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 md:px-8 py-4 flex justify-between items-center">
                <Link href="/" class="font-poppins text-2xl font-extrabold text-gray-900 tracking-tight">
                    PNET<span class="text-shop-primary">Lab</span>
                </Link>

                <!-- Right Links -->
                <div class="hidden md:flex gap-8 items-center font-sans font-semibold text-[15px] text-gray-600">
                    <Link href="/" class="hover:text-shop-primary transition">Home</Link>
                    <Link href="/#about" class="hover:text-shop-primary transition">About</Link>
                    <Link href="/#pricing" class="hover:text-shop-primary transition">Pricing</Link>
                    <Link href="/aktivasi-voucher" class="text-shop-primary transition">Aktivasi</Link>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 relative flex flex-col justify-center items-center px-4 py-12 overflow-hidden">
            <!-- Abstract Background Blobs -->
            <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-shop-primary rounded-full mix-blend-multiply filter blur-[128px] opacity-30 animate-blob"></div>
            <div class="absolute top-[20%] right-[-10%] w-[400px] h-[400px] bg-shop-secondary rounded-full mix-blend-multiply filter blur-[128px] opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-[-10%] left-[20%] w-[600px] h-[600px] bg-shop-tertiary rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-blob animation-delay-4000"></div>

            <div class="w-full sm:max-w-[440px] bg-shop-surface rounded-2xl border border-gray-200 p-8 md:p-10 shadow-shop-md hover:shadow-shop-hover transition-all duration-300 relative z-10">
                <div class="text-center mb-8">
                    <h1 class="font-poppins text-3xl font-extrabold text-gray-900 leading-tight mb-2 tracking-tight">Aktivasi Voucher</h1>
                    <p class="font-sans text-[15px] text-gray-600">Masukkan kredensial voucher untuk membuka akses lab simulasi Anda.</p>
                </div>

                <div v-if="$page.props.flash && $page.props.flash.success" class="mb-6 p-4 rounded-xl bg-shop-success/10 text-shop-success border border-shop-success/20 flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-sans font-semibold text-[14px]">{{ $page.props.flash.success }}</span>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="username" class="block font-sans font-bold text-[13px] text-gray-900 mb-2 uppercase tracking-wide">Username</label>
                        <input
                            id="username"
                            type="text"
                            class="block w-full h-12 px-4 border-gray-300 rounded-xl focus:border-shop-primary focus:ring-shop-primary focus:ring-opacity-50 text-gray-900 placeholder-gray-400 font-mono"
                            v-model="form.username"
                            required
                            autofocus
                            placeholder="CTH: VOUCHER-001"
                        />
                        <div v-if="form.errors.username" class="mt-2 text-sm text-shop-error font-medium">
                            {{ form.errors.username }}
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block font-sans font-bold text-[13px] text-gray-900 mb-2 uppercase tracking-wide">Password</label>
                        <input
                            id="password"
                            type="password"
                            class="block w-full h-12 px-4 border-gray-300 rounded-xl focus:border-shop-primary focus:ring-shop-primary focus:ring-opacity-50 text-gray-900 placeholder-gray-400 font-mono tracking-widest"
                            v-model="form.password"
                            required
                            placeholder="••••••••"
                        />
                        <div v-if="form.errors.password" class="mt-2 text-sm text-shop-error font-medium">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <div class="pt-4">
                        <button
                            type="submit"
                            class="w-full inline-block bg-shop-primary text-white font-sans font-bold py-[16px] px-[36px] rounded-full hover:bg-[#C026D3] shadow-shop-md hover:shadow-shop-hover hover:-translate-y-0.5 transition-all duration-200 text-[16px] disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Memproses...</span>
                            <span v-else>Aktifkan Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>

<style>
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
  animation: blob 7s infinite;
}
.animation-delay-2000 {
  animation-delay: 2s;
}
.animation-delay-4000 {
  animation-delay: 4s;
}
</style>
