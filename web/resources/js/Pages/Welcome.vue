<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    products: {
        type: Array,
        default: () => []
    },
    testimonials: {
        type: Array,
        default: () => []
    }
});

const openFaq = ref(null);

const faqs = [
    {
        q: "Apakah saya harus menginstal PNETLab sendiri?",
        a: "Tidak. Semua kebutuhan sudah kami siapkan dan dapat langsung digunakan."
    },
    {
        q: "Apakah bisa digunakan untuk belajar Mikrotik dan Cisco sekaligus?",
        a: "Ya. Anda bebas membuat berbagai topologi sesuai kebutuhan pembelajaran."
    },
    {
        q: "Apakah tersedia bantuan jika mengalami kendala?",
        a: "Ya. Support tersedia selama masa aktif paket."
    },
    {
        q: "Apakah akses dapat digunakan selama 24 jam?",
        a: "Ya. Lab dapat diakses kapan saja selama paket masih aktif."
    },
    {
        q: "Apakah cocok untuk persiapan sertifikasi?",
        a: "Sangat cocok. Banyak peserta menggunakan lab ini untuk persiapan MTCNA, MTCRE, CCNA, CCNP, JNCIA, dan sertifikasi lainnya."
    }
];
</script>

<template>
    <Head title="Meraki Labs - Belajar Networking Tanpa Ribet" />

    <div class="min-h-screen bg-shop-background font-sans text-gray-800 selection:bg-shop-primary selection:text-white">
        
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-shop-surface/90 backdrop-blur-md border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 md:px-8 py-4 flex justify-between items-center">
                <Link href="/">
                    <img src="/img/logo.png" alt="Meraki Labs" class="h-8 md:h-10">
                </Link>

                <!-- Right Links -->
                <div class="hidden md:flex gap-6 lg:gap-8 items-center font-sans font-semibold text-[15px] text-gray-600">
                    <a href="#" class="hover:text-shop-primary transition">Home</a>
                    <a href="#about" class="hover:text-shop-primary transition">About</a>
                    <a href="#pricing" class="hover:text-shop-primary transition">Pricing</a>
                    <Link href="/aktivasi-voucher" class="hover:text-shop-primary transition">Aktivasi</Link>
                    <Link href="/login" class="bg-shop-primary hover:bg-shop-secondary text-white px-5 py-2 rounded-full transition-colors flex items-center gap-2 shadow-sm">
                        Member Area
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-4 md:px-8 py-16 md:py-24 lg:py-32 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="font-poppins text-[40px] md:text-[56px] font-extrabold text-gray-900 leading-[1.1] tracking-[0.02em] mb-6">
                    Belajar Networking <span class="text-shop-primary">Tanpa Ribet</span> Setup Lab
                </h1>
                <p class="font-sans text-[18px] text-gray-600 leading-[1.6] mb-8">
                    Praktik langsung Mikrotik, Cisco, Juniper, Linux Server, dan teknologi jaringan lainnya. Tingkatkan kemampuan networking Anda menggunakan Meraki Labs siap pakai tanpa perlu PC spesifikasi tinggi.
                </p>
                <ul class="space-y-3 mb-10 text-[16px] text-gray-700 font-medium">
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-shop-success/20 flex items-center justify-center text-shop-success">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        Lab siap digunakan tanpa konfigurasi awal
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-shop-success/20 flex items-center justify-center text-shop-success">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        Akses online 24 jam dari mana saja
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-shop-success/20 flex items-center justify-center text-shop-success">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        Mendukung berbagai vendor dan OS
                    </li>
                </ul>
                <a href="#pricing" class="inline-block bg-shop-primary text-white font-sans font-bold py-[14px] px-[36px] rounded-full hover:bg-[#C026D3] shadow-shop-md hover:shadow-shop-hover hover:-translate-y-0.5 transition-all duration-200 text-[16px]">
                    Mulai Belajar Sekarang
                </a>
            </div>
            <div class="relative">
                <div class="absolute inset-0 bg-shop-tertiary/20 blur-3xl rounded-full"></div>
                <!-- Placeholder for Hero Image/Graphic -->
                <div class="relative bg-shop-surface border border-gray-200 rounded-2xl shadow-shop-lg p-4 rotate-2 hover:rotate-0 transition-transform duration-500">
                    <div class="bg-gray-100 rounded-xl aspect-video flex flex-col items-center justify-center text-gray-400 overflow-hidden relative">
                        <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        <span class="font-mono text-sm tracking-widest font-bold">MERAKILABS_SIMULATION</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- What is Meraki Labs -->
        <section id="about" class="bg-shop-surface border-y border-gray-200 py-20">
            <div class="max-w-7xl mx-auto px-4 md:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="font-poppins text-[40px] font-bold text-gray-900 leading-[1.2] tracking-[0.01em] mb-4">Apa Itu Meraki Labs?</h2>
                    <p class="font-sans text-[18px] text-gray-600 leading-[1.6]">
                        Platform simulasi jaringan profesional yang memungkinkan Anda membangun, mengelola, dan menguji berbagai topologi jaringan secara virtual tanpa harus memiliki perangkat fisik yang mahal.
                    </p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Tech Stack -->
                    <div class="bg-shop-background border border-gray-200 rounded-[24px] p-8 hover:shadow-shop-md transition-shadow">
                        <h3 class="font-poppins text-[26px] font-semibold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="text-shop-secondary">⚡</span> Teknologi yang Tersedia
                        </h3>
                        <div class="grid grid-cols-2 gap-y-4 gap-x-2 text-[15px] font-medium text-gray-700">
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-shop-primary"></span> Mikrotik RouterOS</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-shop-primary"></span> Cisco IOS & IOS XR</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-shop-primary"></span> Cisco Nexus</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-shop-primary"></span> Juniper JunOS</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-shop-primary"></span> Linux Server</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-shop-primary"></span> Windows Server</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-shop-primary"></span> Docker Environment</div>
                        </div>
                    </div>
                    
                    <!-- Suitable For -->
                    <div class="bg-shop-background border border-gray-200 rounded-[24px] p-8 hover:shadow-shop-md transition-shadow">
                        <h3 class="font-poppins text-[26px] font-semibold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="text-shop-tertiary">🎯</span> Cocok Untuk Siapa?
                        </h3>
                        <div class="space-y-3 text-[15px] font-medium text-gray-700">
                            <div class="flex items-center gap-3 p-2 bg-white rounded-lg border border-gray-100 shadow-sm"><span class="text-xl">👨‍🎓</span> Mahasiswa & Pelajar IT</div>
                            <div class="flex items-center gap-3 p-2 bg-white rounded-lg border border-gray-100 shadow-sm"><span class="text-xl">🛠️</span> Network Engineer & SysAdmin</div>
                            <div class="flex items-center gap-3 p-2 bg-white rounded-lg border border-gray-100 shadow-sm"><span class="text-xl">📜</span> Persiapan Sertifikasi MTCNA, MTCRE</div>
                            <div class="flex items-center gap-3 p-2 bg-white rounded-lg border border-gray-100 shadow-sm"><span class="text-xl">🎖️</span> Persiapan Sertifikasi CCNA, CCNP, JNCIA</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing -->
        <section id="pricing" class="max-w-7xl mx-auto px-4 md:px-8 py-20">
            <div class="text-center mb-16">
                <span class="font-sans text-[11px] font-bold uppercase tracking-[0.1em] text-shop-primary bg-shop-primary/10 py-1 px-3 rounded-full mb-4 inline-block">Pricing</span>
                <h2 class="font-poppins text-[40px] font-bold text-gray-900 leading-[1.2] tracking-[0.01em]">Pilihan Paket Akses Lab</h2>
                <p class="font-sans text-[16px] text-gray-500 mt-3">Pilih paket yang sesuai dengan kebutuhan belajar dan target Anda.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6" v-if="products.length > 0">
                <div v-for="product in products" :key="product.id" class="bg-shop-surface rounded-2xl p-6 transition-all duration-200 flex flex-col" :class="product.is_recommended ? 'border-2 border-shop-primary shadow-shop-md hover:shadow-shop-hover hover:-translate-y-1 relative overflow-hidden' : 'border border-gray-200 hover:shadow-shop-lg hover:-translate-y-1'">
                    <div v-if="product.is_recommended" class="absolute top-0 right-0 bg-shop-primary text-white text-[10px] font-bold uppercase tracking-wider py-1 px-3 rounded-bl-lg">Recommended</div>
                    <h3 class="font-poppins text-[22px] font-bold text-gray-900">{{ product.name }}</h3>
                    <p class="text-[13px] text-gray-500 mt-2 min-h-[40px]">{{ product.description }}</p>
                    <div class="my-6">
                        <span class="font-poppins text-[32px] font-extrabold text-shop-secondary">{{ product.price }}</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1 text-[14px] text-gray-700">
                        <li v-for="(feature, idx) in product.features" :key="idx" class="flex items-start gap-3">
                            <span class="text-shop-success">✔</span> {{ feature }}
                        </li>
                    </ul>
                    <Link href="/aktivasi-voucher" class="block w-full text-center rounded-full py-[10px] font-bold transition" :class="product.is_recommended ? 'bg-shop-primary text-white hover:bg-[#C026D3] py-[12px]' : 'bg-transparent text-shop-primary border-2 border-shop-primary hover:bg-[#FDF4FF]'">Pilih Paket</Link>
                </div>
            </div>
            <div v-else class="text-center py-10">
                <p class="text-gray-500 italic text-[15px]">Belum ada paket produk yang tersedia saat ini.</p>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="bg-gray-900 text-white py-20 relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-shop-primary opacity-20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-shop-secondary opacity-10 rounded-full blur-3xl"></div>
            
            <div class="max-w-7xl mx-auto px-4 md:px-8 relative z-10">
                <div class="text-center mb-16">
                    <h2 class="font-poppins text-[40px] font-bold leading-[1.2] tracking-[0.01em]">Kenapa Memilih Lab Kami?</h2>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div class="w-12 h-12 bg-shop-tertiary text-gray-900 rounded-full flex items-center justify-center text-2xl mb-4 shadow-lg shadow-shop-tertiary/20">🚀</div>
                        <h4 class="font-poppins text-[20px] font-semibold mb-2">Server Stabil & Cepat</h4>
                        <p class="text-gray-400 text-[14px] leading-relaxed">Belajar dengan nyaman menggunakan server yang dioptimalkan untuk kebutuhan simulasi networking.</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div class="w-12 h-12 bg-shop-secondary text-gray-900 rounded-full flex items-center justify-center text-2xl mb-4 shadow-lg shadow-shop-secondary/20">🔒</div>
                        <h4 class="font-poppins text-[20px] font-semibold mb-2">Akses Aman & Private</h4>
                        <p class="text-gray-400 text-[14px] leading-relaxed">Setiap pengguna mendapatkan akses yang aman untuk praktik dan eksperimen secara mandiri.</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div class="w-12 h-12 bg-shop-primary text-white rounded-full flex items-center justify-center text-2xl mb-4 shadow-lg shadow-shop-primary/20">🌍</div>
                        <h4 class="font-poppins text-[20px] font-semibold mb-2">Akses Dari Mana Saja</h4>
                        <p class="text-gray-400 text-[14px] leading-relaxed">Gunakan laptop Anda dan akses lab kapan saja selama terhubung ke internet.</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div class="w-12 h-12 bg-shop-success text-white rounded-full flex items-center justify-center text-2xl mb-4">💬</div>
                        <h4 class="font-poppins text-[20px] font-semibold mb-2">Support Teknis</h4>
                        <p class="text-gray-400 text-[14px] leading-relaxed">Tim support siap membantu apabila Anda mengalami kendala selama masa penggunaan.</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div class="w-12 h-12 bg-white text-gray-900 rounded-full flex items-center justify-center text-2xl mb-4">⚙️</div>
                        <h4 class="font-poppins text-[20px] font-semibold mb-2">Multi Vendor</h4>
                        <p class="text-gray-400 text-[14px] leading-relaxed">Mendukung pembelajaran Mikrotik, Cisco, Juniper, Linux Server, dan lainnya.</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div class="w-12 h-12 bg-shop-warning text-white rounded-full flex items-center justify-center text-2xl mb-4">💰</div>
                        <h4 class="font-poppins text-[20px] font-semibold mb-2">Hemat Biaya</h4>
                        <p class="text-gray-400 text-[14px] leading-relaxed">Tidak perlu membeli perangkat fisik yang mahal hanya untuk belajar dan praktik.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="bg-shop-surface py-20 border-b border-gray-200 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 md:px-8 mb-16">
                <div class="text-center">
                    <h2 class="font-poppins text-[40px] font-bold text-gray-900 leading-[1.2]">Testimoni Pengguna</h2>
                </div>
            </div>
            
            <div class="w-full relative group" v-if="testimonials.length > 0">
                <!-- Auto-sliding track -->
                <div class="flex gap-6 animate-slide group-hover:[animation-play-state:paused] w-max px-4">
                    <!-- Duplicate array to create seamless loop -->
                    <div v-for="(testi, index) in [...testimonials, ...testimonials]" :key="index" class="w-[320px] md:w-[400px] shrink-0 bg-shop-background border border-gray-200 p-8 rounded-2xl relative shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-shop-tertiary text-4xl absolute top-4 right-6 opacity-30 font-serif">"</div>
                        <p class="text-[15px] text-gray-700 italic mb-6 relative z-10 h-[65px] overflow-hidden line-clamp-3">{{ testi.content }}</p>
                        <div class="flex items-center gap-3">
                            <div :class="'w-10 h-10 rounded-full flex items-center justify-center font-bold shrink-0 ' + 
                                (testi.color_theme === 'primary' ? 'bg-shop-primary/10 text-shop-primary' :
                                testi.color_theme === 'secondary' ? 'bg-shop-secondary/10 text-shop-secondary' :
                                testi.color_theme === 'success' ? 'bg-[#10B981]/10 text-[#10B981]' :
                                testi.color_theme === 'warning' ? 'bg-[#F59E0B]/10 text-[#F59E0B]' :
                                'bg-shop-info/10 text-shop-info')"
                            >
                                {{ testi.name.charAt(0).toUpperCase() }}
                            </div>
                            <div class="overflow-hidden">
                                <h5 class="font-bold text-[14px] text-gray-900 truncate">{{ testi.name }}</h5>
                                <span class="text-[12px] text-gray-500 truncate block">{{ testi.role }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="text-center py-10">
                <p class="text-gray-500 italic text-[15px]">Belum ada testimoni.</p>
            </div>
        </section>

        <!-- FAQ -->
        <section class="max-w-4xl mx-auto px-4 md:px-8 py-20">
            <div class="text-center mb-12">
                <h2 class="font-poppins text-[40px] font-bold text-gray-900 leading-[1.2]">Pertanyaan Sering Diajukan</h2>
            </div>
            <div class="space-y-4">
                <div v-for="(faq, index) in faqs" :key="index" class="border border-gray-200 rounded-2xl bg-white overflow-hidden">
                    <button @click="openFaq === index ? openFaq = null : openFaq = index" class="w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none">
                        <span class="font-poppins font-semibold text-[16px] text-gray-900">{{ faq.q }}</span>
                        <span class="text-shop-primary text-xl font-mono transition-transform duration-200" :class="{ 'rotate-45': openFaq === index }">+</span>
                    </button>
                    <div v-show="openFaq === index" class="px-6 pb-5 text-[15px] text-gray-600">
                        {{ faq.a }}
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer CTA -->
        <section class="bg-shop-primary text-white py-24 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-shop-primary to-[#C026D3]"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            
            <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
                <h2 class="font-poppins text-[40px] md:text-[48px] font-extrabold leading-[1.1] mb-6">Siap Meningkatkan Skill Networking Anda?</h2>
                <p class="font-sans text-[18px] text-white/90 mb-10 max-w-2xl mx-auto">
                    Jangan habiskan waktu untuk instalasi dan konfigurasi lab yang rumit. Fokus pada pembelajaran dan praktik menggunakan Meraki Labs yang sudah siap digunakan.
                </p>
                <div class="flex flex-wrap justify-center gap-3 mb-10 text-[14px] font-bold">
                    <span class="bg-white/20 py-1.5 px-4 rounded-full">✅ Mikrotik</span>
                    <span class="bg-white/20 py-1.5 px-4 rounded-full">✅ Cisco</span>
                    <span class="bg-white/20 py-1.5 px-4 rounded-full">✅ Juniper</span>
                    <span class="bg-white/20 py-1.5 px-4 rounded-full">✅ Linux Server</span>
                </div>
                <a href="https://wa.me/08000000000" target="_blank" class="inline-block bg-white text-shop-primary font-poppins font-bold py-[16px] px-[42px] rounded-full hover:bg-gray-50 shadow-shop-lg hover:shadow-shop-overlay hover:-translate-y-1 transition-all duration-200 text-[18px]">
                    Pesan Paket Sekarang
                </a>
            </div>
        </section>
        
        <footer class="bg-gray-900 text-gray-500 text-center py-6 text-[14px]">
            &copy; {{ new Date().getFullYear() }} Meraki Labs. All rights reserved.
        </footer>
    </div>
</template>

<style>
/* Auto-slide Marquee Animation */
@keyframes slide {
    0% { transform: translateX(0); }
    100% { transform: translateX(calc(-50% - 12px)); } /* -50% shifts by exactly half the total duplicated width. 12px accounts for half the 24px gap */
}
.animate-slide {
    animation: slide 60s linear infinite;
}
</style>
