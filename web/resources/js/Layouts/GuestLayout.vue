<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        default: '',
    },
    subtitle: {
        type: String,
        default: '',
    },
    activePage: {
        type: String,
        default: 'login', // 'login', 'register', 'forgot-password'
    },
});
</script>

<template>
    <div class="min-h-screen w-full flex flex-col lg:flex-row bg-[#FBFBFB] font-sans text-gray-800 selection:bg-shop-primary selection:text-white">
        
        <!-- Left Showcase Panel (Visible on lg screens) -->
        <div class="hidden lg:flex lg:w-1/2 xl:w-5/12 flex-col justify-between relative overflow-hidden bg-gradient-to-br from-[#0B0F17] via-[#111726] to-[#1a0a0d] text-white p-10 xl:p-14 border-r border-gray-800/80">
            <!-- Ambient Glowing Blobs -->
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-shop-primary/25 rounded-full blur-[110px] pointer-events-none"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-shop-secondary/30 rounded-full blur-[120px] pointer-events-none"></div>
            <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-shop-tertiary/10 rounded-full blur-[90px] pointer-events-none"></div>

            <!-- Tech Network Grid Pattern Overlay -->
            <div 
                class="absolute inset-0 opacity-[0.06] pointer-events-none" 
                style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"
            ></div>

            <!-- Top Header on Left Panel -->
            <div class="relative z-10">
                <Link href="/" class="inline-flex items-center gap-3 group">
                    <img src="/img/logo.png" alt="Meraki Labs" class="h-10 transition-transform group-hover:scale-105">
                </Link>

                <div class="mt-4">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-mono font-medium bg-white/10 border border-white/15 text-gray-200 backdrop-blur-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        PNETLab Cloud Lab Platform
                    </span>
                </div>
            </div>

            <!-- Center Content Showcase -->
            <div class="relative z-10 my-auto py-8">
                <h1 class="font-poppins text-3xl xl:text-4xl font-extrabold tracking-tight leading-[1.2] mb-4">
                    Praktik Jaringan Nyata <br class="hidden xl:inline">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-400 via-shop-tertiary to-rose-200">
                        Tanpa Beban Hardware.
                    </span>
                </h1>
                <p class="text-gray-400 text-sm xl:text-base leading-relaxed mb-8 max-w-lg">
                    Akses topologi Mikrotik, Cisco IOS, Juniper, dan Linux Server siap pakai 24/7 langsung dari browser tanpa khawatir RAM laptop kepanasan.
                </p>

                <!-- Interactive Mockup Telemetry Card -->
                <div class="bg-white/[0.04] border border-white/10 rounded-2xl p-5 shadow-2xl backdrop-blur-md mb-6 max-w-lg">
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-white/10 text-xs font-mono text-gray-400">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-500/80 inline-block"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-yellow-500/80 inline-block"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500/80 inline-block"></span>
                            <span class="text-gray-300 font-medium ml-1">meraki-node-core:~$</span>
                        </div>
                        <span class="text-emerald-400 font-semibold flex items-center gap-1.5 text-[11px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                            Online Cluster
                        </span>
                    </div>
                    
                    <div class="space-y-2 text-xs font-mono">
                        <div class="flex items-center justify-between text-gray-300">
                            <span class="text-gray-400">⚡ Engine Simulator:</span>
                            <span class="text-white font-medium">PNETLab v6 Ready</span>
                        </div>
                        <div class="flex items-center justify-between text-gray-300">
                            <span class="text-gray-400">🌐 Vendor Tersedia:</span>
                            <span class="text-rose-300 font-medium">MikroTik, Cisco, Juniper</span>
                        </div>
                        <div class="flex items-center justify-between text-gray-300">
                            <span class="text-gray-400">⏱️ Aksesibilitas:</span>
                            <span class="text-emerald-400 font-medium">24/7 Nonstop Online</span>
                        </div>
                    </div>
                </div>

                <!-- Tech Badges -->
                <div class="flex flex-wrap gap-2 text-xs font-medium text-gray-300">
                    <span class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 hover:border-shop-primary/50 transition-colors">#MikrotikMTCNA</span>
                    <span class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 hover:border-shop-primary/50 transition-colors">#CiscoCCNA</span>
                    <span class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 hover:border-shop-primary/50 transition-colors">#JunipervSRX</span>
                    <span class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 hover:border-shop-primary/50 transition-colors">#LinuxDocker</span>
                </div>
            </div>

            <!-- Bottom Left Footer -->
            <div class="relative z-10 pt-6 border-t border-gray-800/80 flex items-center justify-between text-xs text-gray-400">
                <span>© 2026 Meraki Labs. Hak Cipta Dilindungi.</span>
                <span class="font-mono text-[11px] text-gray-500">v2.4.0</span>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="w-full lg:w-1/2 xl:w-7/12 min-h-screen flex flex-col justify-between px-6 sm:px-12 lg:px-16 py-8 sm:py-10">
            
            <!-- Top Navigation Bar -->
            <div class="w-full max-w-md mx-auto flex items-center justify-between pb-4">
                <Link 
                    href="/" 
                    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-shop-primary transition-colors py-2 px-3 rounded-xl hover:bg-gray-100 group"
                >
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Beranda</span>
                </Link>

                <!-- Page Switcher -->
                <div class="text-sm text-gray-600">
                    <template v-if="activePage === 'login'">
                        Belum punya akun?
                        <Link :href="route('register')" class="font-bold text-shop-primary hover:text-shop-secondary hover:underline transition-colors ml-1">
                            Daftar
                        </Link>
                    </template>
                    <template v-else-if="activePage === 'register'">
                        Sudah punya akun?
                        <Link :href="route('login')" class="font-bold text-shop-primary hover:text-shop-secondary hover:underline transition-colors ml-1">
                            Masuk
                        </Link>
                    </template>
                    <template v-else>
                        Ingat akun Anda?
                        <Link :href="route('login')" class="font-bold text-shop-primary hover:text-shop-secondary hover:underline transition-colors ml-1">
                            Masuk
                        </Link>
                    </template>
                </div>
            </div>

            <!-- Form Content Box -->
            <div class="w-full max-w-md mx-auto my-auto py-6">
                <!-- Mobile Logo (Shown only on small screens) -->
                <div class="lg:hidden flex flex-col items-center mb-8 text-center">
                    <Link href="/">
                        <img src="/img/logo.png" alt="Meraki Labs" class="h-10 mb-2">
                    </Link>
                    <span class="text-xs font-mono text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        Cloud Lab Simulator
                    </span>
                </div>

                <!-- Form Header -->
                <div v-if="title" class="mb-6">
                    <h2 class="font-poppins text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight leading-tight mb-2">
                        {{ title }}
                    </h2>
                    <p v-if="subtitle" class="text-sm sm:text-base text-gray-600 leading-relaxed font-sans">
                        {{ subtitle }}
                    </p>
                </div>
                <slot name="header" />

                <!-- Form Card -->
                <div class="bg-white border border-gray-200/90 rounded-2xl p-7 sm:p-9 shadow-shop-md hover:shadow-shop-lg transition-shadow duration-300">
                    <slot />
                </div>
            </div>

            <!-- Bottom Support / Voucher Footer -->
            <div class="w-full max-w-md mx-auto pt-6 text-xs text-gray-500 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-gray-200/70 mt-4">
                <Link 
                    :href="route('aktivasi.index')" 
                    class="hover:text-shop-primary font-semibold flex items-center gap-1.5 transition-colors"
                >
                    <span class="text-shop-primary">⚡</span>
                    <span>Aktivasi Kode Voucher</span>
                </Link>

                <a 
                    href="https://wa.me/08000000000" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="hover:text-shop-primary font-medium flex items-center gap-1.5 transition-colors"
                >
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.288.043.088.072.19.014.305-.058.115-.087.187-.173.289l-.26.309c-.087.094-.179.196-.077.371.101.174.452.744.97 1.206.666.593 1.228.777 1.401.864.173.086.275.072.376-.043.101-.116.433-.506.549-.679.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.044.073.044.42-.1.825z"/>
                    </svg>
                    <span>Bantuan CS Lab</span>
                </a>
            </div>

        </div>

    </div>
</template>
