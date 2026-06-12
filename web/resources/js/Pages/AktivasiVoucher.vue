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

    <div class="min-h-screen flex flex-col bg-[#FAFAFA] relative overflow-hidden font-sans">
        <!-- Abstract Background Blobs for Visual Punch -->
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-[#D946EF] rounded-full mix-blend-multiply filter blur-[128px] opacity-30 animate-blob"></div>
        <div class="absolute top-[20%] right-[-10%] w-[400px] h-[400px] bg-[#22D3EE] rounded-full mix-blend-multiply filter blur-[128px] opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[600px] h-[600px] bg-[#FACC15] rounded-full mix-blend-multiply filter blur-[128px] opacity-20 animate-blob animation-delay-4000"></div>

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
        <div class="flex-1 flex flex-col sm:justify-center items-center pt-10 sm:pt-0 px-4 relative z-10">
            <!-- Form Card with Elevated Shadow from ShopVibe -->
            <div class="w-full sm:max-w-[440px] mt-6 px-8 py-10 bg-[#FFFFFF] rounded-[24px] border border-white/60 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_14px_32px_rgba(217,70,239,0.12),0_6px_12px_rgba(0,0,0,0.06)] shadow-[0_10px_25px_rgba(0,0,0,0.05),0_6px_10px_rgba(0,0,0,0.03)] relative overflow-hidden">
                
                <!-- Decorative Accent Line -->
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#D946EF] via-[#22D3EE] to-[#FACC15]"></div>

                <div class="mb-10 text-center">
                    <h1 class="text-[32px] font-extrabold text-[#171717] leading-tight mb-3" style="font-family: 'Poppins', sans-serif; letter-spacing: -0.5px;">Aktivasi <br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-[#D946EF] to-[#C026D3]">Voucher Anda</span></h1>
                    <p class="text-[#525252] text-[15px]" style="font-family: 'Nunito', sans-serif;">Masukkan kredensial voucher untuk membuka akses lab simulasi Anda seketika.</p>
                </div>

                <div v-if="$page.props.flash && $page.props.flash.success" class="mb-8 p-4 rounded-[12px] bg-[#DCFCE7] text-[#166534] border border-[#bbf7d0] flex items-start gap-3 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#22C55E] mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span style="font-family: 'Nunito', sans-serif; font-weight: 600;">{{ $page.props.flash.success }}</span>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <label for="username" class="block font-bold text-[13px] text-[#171717] mb-2 uppercase tracking-wide" style="font-family: 'Nunito', sans-serif;">Username</label>
                        <div class="relative group">
                            <input
                                id="username"
                                type="text"
                                class="block w-full h-[48px] px-4 py-2 border-[1.5px] border-[#E5E5E5] rounded-[12px] focus:border-[#D946EF] focus:ring-4 focus:ring-[#D946EF]/15 text-[16px] text-[#171717] placeholder-[#A3A3A3] transition-all duration-200 bg-[#FAFAFA] focus:bg-white"
                                style="font-family: 'Space Mono', monospace;"
                                v-model="form.username"
                                required
                                autofocus
                                placeholder="CTH: VOUCHER-001"
                            />
                        </div>
                        <div v-if="form.errors.username" class="mt-2 text-sm text-[#EF4444] font-medium" style="font-family: 'Nunito', sans-serif;">
                            {{ form.errors.username }}
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block font-bold text-[13px] text-[#171717] uppercase tracking-wide" style="font-family: 'Nunito', sans-serif;">Password</label>
                        </div>
                        <input
                            id="password"
                            type="password"
                            class="block w-full h-[48px] px-4 py-2 border-[1.5px] border-[#E5E5E5] rounded-[12px] focus:border-[#D946EF] focus:ring-4 focus:ring-[#D946EF]/15 text-[16px] text-[#171717] placeholder-[#A3A3A3] transition-all duration-200 bg-[#FAFAFA] focus:bg-white tracking-widest"
                            style="font-family: 'Space Mono', monospace;"
                            v-model="form.password"
                            required
                            placeholder="••••••••"
                        />
                        <div v-if="form.errors.password" class="mt-2 text-sm text-[#EF4444] font-medium" style="font-family: 'Nunito', sans-serif;">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <div class="pt-6">
                        <button
                            type="submit"
                            class="relative w-full flex items-center justify-center bg-[#D946EF] hover:bg-[#C026D3] active:bg-[#A21CAF] text-white px-[28px] h-[52px] rounded-full transition-all duration-300 shadow-[0_8px_20px_-6px_rgba(217,70,239,0.6)] hover:shadow-[0_12px_24px_-6px_rgba(217,70,239,0.7)] group overflow-hidden"
                            style="font-family: 'Nunito', sans-serif; font-weight: 800; font-size: 16px; letter-spacing: 0.5px;"
                            :class="{ 'opacity-60 cursor-not-allowed': form.processing }"
                            :disabled="form.processing"
                        >
                            <!-- Button Highlight Effect -->
                            <div class="absolute inset-0 w-full h-full bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
                            
                            <span class="relative flex items-center gap-2">
                                <span v-if="form.processing">Memproses...</span>
                                <span v-else>AKTIFKAN SEKARANG</span>
                                <svg v-if="!form.processing" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Footer text -->
            <div class="mt-8 text-center text-[#A3A3A3] text-[13px]" style="font-family: 'Nunito', sans-serif;">
                <p>Mengalami kendala? <a href="#" class="text-[#22D3EE] hover:text-[#06B6D4] font-bold transition-colors">Hubungi Support</a></p>
            </div>
        </div>
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
