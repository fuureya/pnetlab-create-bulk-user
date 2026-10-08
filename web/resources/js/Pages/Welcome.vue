<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

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
const mobileMenuOpen = ref(false);
const activeHeroTab = ref('ospf');

const faqs = [
    {
        q: "Apakah saya harus menginstal PNETLab sendiri?",
        a: "Tidak perlu. Semua kebutuhan server dan image lab sudah kami siapkan 100%. Anda cukup login melalui web browser dan langsung praktik."
    },
    {
        q: "Apakah bisa digunakan untuk belajar Mikrotik dan Cisco sekaligus?",
        a: "Sangat bisa. Anda bebas menggabungkan berbagai vendor dalam satu canvas topologi untuk simulasi multi-vendor."
    },
    {
        q: "Apakah tersedia bantuan jika mengalami kendala?",
        a: "Ya. Tim technical support kami siap membantu melalui WhatsApp selama masa aktif paket lab Anda."
    },
    {
        q: "Apakah akses dapat digunakan selama 24 jam?",
        a: "Ya. Server lab aktif 24 jam nonstop setiap hari, sehingga Anda bebas belajar kapan saja sesuai waktu luang Anda."
    },
    {
        q: "Apakah cocok untuk persiapan sertifikasi internasional?",
        a: "Sangat cocok. Ribuan praktisi menggunakan lab ini untuk latihan hands-on sertifikasi MTCNA, MTCRE, CCNA, CCNP Enterprise, hingga JNCIA."
    }
];

const topologies = [
    {
        id: "ospf",
        title: "Multi-Vendor OSPF & BGP Routing",
        desc: "Interkoneksi dinamis antara MikroTik RouterOS v7 dan Cisco IOS-XR dengan full routing table.",
        tags: ["MikroTik", "Cisco", "OSPFv3", "BGP"],
        nodes: "6 Nodes",
        difficulty: "Menengah"
    },
    {
        id: "vlan",
        title: "VLAN & Inter-VLAN Routing (Trunking)",
        desc: "Simulasi segmentasi jaringan kantor, 802.1Q trunking, dan Router-on-a-Stick gateway.",
        tags: ["Cisco Switch", "VLAN", "Router-on-a-Stick"],
        nodes: "4 Nodes",
        difficulty: "Pemula"
    },
    {
        id: "firewall",
        title: "MikroTik Firewall Filter & NAT Gateway",
        desc: "Praktik proteksi jaringan, Port Forwarding (Dst-NAT), Masquerade, dan Mangle QoS.",
        tags: ["MikroTik", "Firewall", "NAT", "QoS"],
        nodes: "3 Nodes",
        difficulty: "Pemula - Menengah"
    },
    {
        id: "vpn",
        title: "Enterprise Site-to-Site VPN & IPsec",
        desc: "Membangun tunnel secure antar kantor cabang menggunakan Wireguard, IPsec IKEv2, dan GRE.",
        tags: ["Juniper", "MikroTik", "Wireguard", "IPsec"],
        nodes: "5 Nodes",
        difficulty: "Lanjutan"
    },
    {
        id: "automation",
        title: "Network Automation (Python & Netmiko)",
        desc: "Push konfigurasi massal otomatis ke puluhan router menggunakan script Python dan Ansible.",
        tags: ["Linux", "Python", "Netmiko", "Ansible"],
        nodes: "5 Nodes",
        difficulty: "Lanjutan"
    }
];

const isProcessing = ref(false);

const checkout = async (productId) => {
    isProcessing.value = true;
    try {
        const response = await axios.post('/checkout', { product_id: productId });
        const snapToken = response.data.snap_token;
        
        window.snap.pay(snapToken, {
            onSuccess: function(result){
                window.location.href = '/riwayat-transaksi';
            },
            onPending: function(result){
                window.location.href = '/riwayat-transaksi';
            },
            onError: function(result){
                Swal.fire('Error', 'Pembayaran gagal!', 'error');
            },
            onClose: function(){
                console.log('Customer closed popup without payment');
            }
        });
    } catch (error) {
        if (error.response && error.response.status === 401) {
            window.location.href = '/login';
        } else {
            Swal.fire('Gagal', 'Gagal membuat transaksi, pastikan Anda sudah login.', 'error');
        }
    } finally {
        isProcessing.value = false;
    }
};
</script>

<template>
    <Head title="Meraki Labs - Belajar Networking Tanpa Ribet Setup Lab" />

    <div class="min-h-screen bg-shop-background font-sans text-gray-800 selection:bg-shop-primary selection:text-white relative">
        
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-shop-surface/95 backdrop-blur-md border-b border-gray-200 transition-all">
            <div class="max-w-7xl mx-auto px-4 md:px-8 py-3.5 flex justify-between items-center">
                <!-- Brand Logo -->
                <Link href="/" class="flex items-center gap-2">
                    <img src="/img/logo.png" alt="Meraki Labs" class="h-8 md:h-10">
                </Link>

                <!-- Desktop Navigation Links -->
                <div class="hidden lg:flex gap-7 items-center font-sans font-semibold text-[14px] text-gray-600">
                    <a href="#" class="hover:text-shop-primary transition-colors">Home</a>
                    <a href="#about" class="hover:text-shop-primary transition-colors">About</a>
                    <a href="#how-it-works" class="hover:text-shop-primary transition-colors">Cara Kerja</a>
                    <a href="#topologi" class="hover:text-shop-primary transition-colors">Topologi</a>
                    <a href="#pricing" class="hover:text-shop-primary transition-colors">Pricing</a>
                    <a href="#faq" class="hover:text-shop-primary transition-colors">FAQ</a>
                    
                    <Link 
                        href="/aktivasi-voucher" 
                        class="hover:text-shop-primary font-bold text-gray-700 flex items-center gap-1.5 transition-colors"
                    >
                        <span class="text-shop-primary">⚡</span> Aktivasi
                    </Link>

                    <Link 
                        href="/login" 
                        class="bg-shop-primary hover:bg-shop-secondary text-white px-5 py-2 rounded-full transition-all flex items-center gap-2 shadow-shop-md hover:shadow-shop-hover hover:-translate-y-0.5"
                    >
                        <span>Member Area</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </Link>
                </div>

                <!-- Mobile Hamburger Toggle -->
                <div class="lg:hidden flex items-center gap-2">
                    <Link 
                        href="/login" 
                        class="bg-shop-primary text-white text-xs font-bold px-3 py-1.5 rounded-full"
                    >
                        Login
                    </Link>
                    <button 
                        @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="p-2 rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none"
                        aria-label="Toggle Menu"
                    >
                        <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Drawer Menu -->
            <div v-show="mobileMenuOpen" class="lg:hidden bg-white border-b border-gray-200 px-5 py-4 space-y-3 font-semibold text-gray-700">
                <a @click="mobileMenuOpen = false" href="#" class="block py-1.5 hover:text-shop-primary">Home</a>
                <a @click="mobileMenuOpen = false" href="#about" class="block py-1.5 hover:text-shop-primary">About</a>
                <a @click="mobileMenuOpen = false" href="#how-it-works" class="block py-1.5 hover:text-shop-primary">Cara Kerja</a>
                <a @click="mobileMenuOpen = false" href="#topologi" class="block py-1.5 hover:text-shop-primary">Topologi Lab</a>
                <a @click="mobileMenuOpen = false" href="#pricing" class="block py-1.5 hover:text-shop-primary">Pilihan Paket</a>
                <a @click="mobileMenuOpen = false" href="#faq" class="block py-1.5 hover:text-shop-primary">FAQ</a>
                <div class="pt-3 border-t border-gray-100 flex flex-col gap-2">
                    <Link href="/aktivasi-voucher" class="w-full text-center py-2.5 rounded-xl border border-shop-primary text-shop-primary font-bold">
                        ⚡ Aktivasi Voucher
                    </Link>
                    <Link href="/login" class="w-full text-center py-2.5 rounded-xl bg-shop-primary text-white font-bold">
                        Member Area (Login)
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-4 md:px-8 py-12 md:py-20 lg:py-24 grid lg:grid-cols-12 gap-12 items-center">
            <!-- Left Hero Content -->
            <div class="lg:col-span-6">
                <!-- Badge Notification -->
                <div class="inline-flex items-center gap-2 bg-shop-primary/10 border border-shop-primary/20 px-3.5 py-1.5 rounded-full mb-6 text-xs font-mono font-semibold text-shop-primary">
                    <span class="w-2 h-2 rounded-full bg-shop-primary animate-pulse"></span>
                    <span>PNETLab v6 Virtual Environment Ready</span>
                </div>

                <h1 class="font-poppins text-3xl sm:text-4xl md:text-5xl font-extrabold text-gray-900 leading-[1.15] tracking-[0.01em] mb-6">
                    Belajar Networking <br class="hidden sm:inline">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-shop-primary via-red-600 to-rose-600">Tanpa Ribet</span> Setup Lab Sendiri
                </h1>
                
                <p class="font-sans text-base sm:text-lg text-gray-600 leading-relaxed mb-8 max-w-xl">
                    Praktik langsung <strong class="text-gray-800">MikroTik, Cisco, Juniper, dan Linux Server</strong> siap pakai 24/7. Tingkatkan skill networking & persiapan sertifikasi tanpa perlu laptop spesifikasi dewa.
                </p>

                <!-- Feature Checkmarks -->
                <ul class="space-y-3 mb-10 text-[15px] text-gray-700 font-medium">
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-shop-success/15 flex items-center justify-center text-shop-success shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span>Lab siap pakai instan tanpa installasi QEMU/IOL manual</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-shop-success/15 flex items-center justify-center text-shop-success shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span>Akses online 24 jam nonstop via browser apa saja</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-shop-success/15 flex items-center justify-center text-shop-success shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span>Bebas bikin topologi multi-vendor sesuai kebutuhan</span>
                    </li>
                </ul>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap gap-4 items-center">
                    <a 
                        href="#pricing" 
                        class="bg-shop-primary hover:bg-shop-secondary text-white font-poppins font-bold py-3.5 px-8 rounded-full shadow-shop-md hover:shadow-shop-hover hover:-translate-y-0.5 transition-all duration-200 text-sm sm:text-base"
                    >
                        Mulai Belajar Sekarang 🚀
                    </a>
                    <a 
                        href="#topologi" 
                        class="bg-white hover:bg-gray-50 text-gray-800 border-2 border-gray-200 font-poppins font-bold py-3.5 px-6 rounded-full hover:-translate-y-0.5 transition-all text-sm sm:text-base flex items-center gap-2"
                    >
                        <span>Lihat Topologi</span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right Hero Graphic: High-Tech Interactive Topology Simulator Mockup -->
            <div class="lg:col-span-6 relative">
                <!-- Ambient Glow behind card -->
                <div class="absolute -inset-4 bg-gradient-to-r from-shop-primary/20 via-rose-500/10 to-shop-secondary/20 rounded-3xl blur-2xl"></div>

                <!-- Main Glassmorphic Hardware Simulator Card -->
                <div class="relative bg-[#0D1117] text-white border border-gray-800 rounded-3xl p-5 sm:p-6 shadow-2xl overflow-hidden">
                    
                    <!-- Top Window Bar -->
                    <div class="flex items-center justify-between pb-4 border-b border-gray-800 text-xs font-mono">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                            <span class="text-gray-400 font-medium ml-2">pnetlab-cluster-01.merakilabs.net</span>
                        </div>
                        <div class="flex items-center gap-2 text-emerald-400 font-semibold bg-emerald-950/60 border border-emerald-800/40 px-2.5 py-0.5 rounded-full text-[11px]">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            <span>Online 24/7</span>
                        </div>
                    </div>

                    <!-- Interactive Topology Diagram Canvas -->
                    <div class="my-5 bg-[#161B22] rounded-2xl p-4 sm:p-5 border border-gray-800/90 relative overflow-hidden">
                        <!-- Network Grid Background -->
                        <div 
                            class="absolute inset-0 opacity-[0.06] pointer-events-none" 
                            style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 16px 16px;"
                        ></div>

                        <div class="flex items-center justify-between text-xs text-gray-400 font-mono mb-4">
                            <span>TOPOLOGY CANVAS // ACTIVE</span>
                            <span class="text-shop-tertiary">Multi-Vendor Mesh</span>
                        </div>

                        <!-- Nodes Flow Diagram -->
                        <div class="relative z-10 grid grid-cols-3 gap-3 sm:gap-4 items-center text-center">
                            
                            <!-- Node 1: MikroTik Core -->
                            <div class="bg-gray-900/90 border border-shop-primary/60 rounded-xl p-3 shadow-md hover:border-shop-primary transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-shop-primary/20 text-shop-primary mx-auto flex items-center justify-center font-bold text-xs mb-1">
                                    MT
                                </div>
                                <p class="text-xs font-bold text-white truncate">R1-MikroTik</p>
                                <p class="text-[10px] font-mono text-gray-400">ROS v7.14</p>
                                <span class="inline-block mt-1 text-[9px] bg-emerald-900/60 text-emerald-300 px-1.5 py-0.5 rounded">Active</span>
                            </div>

                            <!-- Link / Pipe Connection -->
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-full h-0.5 bg-gradient-to-r from-shop-primary via-emerald-400 to-blue-500 relative">
                                    <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-2 h-2 rounded-full bg-emerald-400 animate-ping"></div>
                                </div>
                                <span class="text-[9px] font-mono text-gray-400 mt-1">10 Gbps Trunk</span>
                            </div>

                            <!-- Node 2: Cisco L3 Switch -->
                            <div class="bg-gray-900/90 border border-blue-500/60 rounded-xl p-3 shadow-md hover:border-blue-400 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 mx-auto flex items-center justify-center font-bold text-xs mb-1">
                                    CS
                                </div>
                                <p class="text-xs font-bold text-white truncate">SW1-Cisco</p>
                                <p class="text-[10px] font-mono text-gray-400">IOS-XE 17.x</p>
                                <span class="inline-block mt-1 text-[9px] bg-emerald-900/60 text-emerald-300 px-1.5 py-0.5 rounded">Trunk 802.1Q</span>
                            </div>
                        </div>

                        <!-- Secondary Virtual Nodes Row -->
                        <div class="grid grid-cols-3 gap-2 mt-4 pt-3 border-t border-gray-800 text-[11px] font-mono">
                            <div class="bg-gray-900/50 rounded-lg p-2 text-center text-gray-300 border border-gray-800">
                                <span class="text-orange-400 block font-bold text-[10px]">JUNIPER</span>
                                <span>vSRX-GW</span>
                            </div>
                            <div class="bg-gray-900/50 rounded-lg p-2 text-center text-gray-300 border border-gray-800">
                                <span class="text-purple-400 block font-bold text-[10px]">UBUNTU</span>
                                <span>Server-Node</span>
                            </div>
                            <div class="bg-gray-900/50 rounded-lg p-2 text-center text-gray-300 border border-gray-800">
                                <span class="text-cyan-400 block font-bold text-[10px]">DOCKER</span>
                                <span>Net-Auto</span>
                            </div>
                        </div>
                    </div>

                    <!-- Mini Console Simulation Bar -->
                    <div class="bg-black/60 rounded-xl p-3 font-mono text-xs border border-gray-800">
                        <div class="flex items-center justify-between text-[11px] text-gray-500 pb-1.5 mb-1.5 border-b border-gray-900">
                            <span class="text-gray-400">admin@meraki-core:~$ ping 8.8.8.8</span>
                            <span class="text-emerald-400">Latency: 1.18ms</span>
                        </div>
                        <div class="text-gray-300 text-[11px] space-y-0.5">
                            <p class="text-emerald-400">64 bytes from 8.8.8.8: icmp_seq=1 ttl=118 time=1.12 ms</p>
                            <p class="text-gray-400">--- 8.8.8.8 ping statistics: 0% packet loss, cluster ready ---</p>
                        </div>
                    </div>

                    <!-- Telemetry Floating Highlights -->
                    <div class="mt-4 flex flex-wrap items-center justify-between text-xs text-gray-400 pt-2 border-t border-gray-800/80">
                        <div class="flex items-center gap-1.5">
                            <span class="text-shop-tertiary">⚡</span>
                            <span>Low Latency Response</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-emerald-400">✔</span>
                            <span>Dedicated CPU & RAM Pool</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Counter Trust Bar -->
        <section class="bg-white border-y border-gray-200 py-10">
            <div class="max-w-7xl mx-auto px-4 md:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div class="p-4">
                        <p class="font-poppins text-3xl md:text-4xl font-extrabold text-shop-primary mb-1">500+</p>
                        <p class="text-sm font-semibold text-gray-600">Sesi Lab Selesai</p>
                    </div>
                    <div class="p-4">
                        <p class="font-poppins text-3xl md:text-4xl font-extrabold text-gray-900 mb-1">99.9%</p>
                        <p class="text-sm font-semibold text-gray-600">Server Uptime 24/7</p>
                    </div>
                    <div class="p-4">
                        <p class="font-poppins text-3xl md:text-4xl font-extrabold text-shop-secondary mb-1">10+</p>
                        <p class="text-sm font-semibold text-gray-600">Vendor Image Siap Pakai</p>
                    </div>
                    <div class="p-4">
                        <p class="font-poppins text-3xl md:text-4xl font-extrabold text-emerald-600 mb-1">100%</p>
                        <p class="text-sm font-semibold text-gray-600">Web Browser Native</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: How It Works (3 Langkah Praktis) -->
        <section id="how-it-works" class="max-w-7xl mx-auto px-4 md:px-8 py-20">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="font-sans text-[11px] font-bold uppercase tracking-[0.1em] text-shop-primary bg-shop-primary/10 py-1 px-3.5 rounded-full mb-3 inline-block">
                    Alur Praktik
                </span>
                <h2 class="font-poppins text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">
                    Mulai Belajar dalam 3 Langkah Sederhana
                </h2>
                <p class="text-gray-600 text-base">
                    Proses instan tanpa ribet download image bergiga-giga atau pusing setting virtual machine.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-shop-md transition-shadow relative">
                    <div class="w-12 h-12 rounded-xl bg-shop-primary/10 text-shop-primary font-poppins font-extrabold text-xl flex items-center justify-center mb-6">
                        1
                    </div>
                    <h3 class="font-poppins text-xl font-bold text-gray-900 mb-3">Pilih Paket Belajar</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Tentukan durasi akses lab (1, 2, 3, atau 4 minggu) sesuai kebutuhan latihan atau target ujian sertifikasi Anda.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-shop-md transition-shadow relative">
                    <div class="w-12 h-12 rounded-xl bg-shop-primary/10 text-shop-primary font-poppins font-extrabold text-xl flex items-center justify-center mb-6">
                        2
                    </div>
                    <h3 class="font-poppins text-xl font-bold text-gray-900 mb-3">Dapatkan Kode Voucher</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Selesaikan transaksi secara aman dengan QRIS / Bank Transfer via Midtrans. Kredensial akun lab aktif seketika.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-shop-md transition-shadow relative">
                    <div class="w-12 h-12 rounded-xl bg-shop-primary/10 text-shop-primary font-poppins font-extrabold text-xl flex items-center justify-center mb-6">
                        3
                    </div>
                    <h3 class="font-poppins text-xl font-bold text-gray-900 mb-3">Aktivasi & Mulai Lab</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Masukkan kode voucher di halaman Aktivasi, login ke portal PNETLab, dan bangun topologi jaringan Anda langsung di browser.
                    </p>
                </div>
            </div>
        </section>

        <!-- What is Meraki Labs & Tech Stack -->
        <section id="about" class="bg-shop-surface border-y border-gray-200 py-20">
            <div class="max-w-7xl mx-auto px-4 md:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="font-poppins text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">
                        Kenapa Harus Meraki Labs?
                    </h2>
                    <p class="font-sans text-base md:text-lg text-gray-600 leading-relaxed">
                        Platform simulasi jaringan virtual profesional yang memungkinkan Anda membangun, mengelola, dan menguji berbagai skenario enterprise tanpa perlu beli perangkat hardware fisik jutaan rupiah.
                    </p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Tech Stack -->
                    <div class="bg-shop-background border border-gray-200 rounded-2xl p-8 hover:shadow-shop-md transition-shadow">
                        <h3 class="font-poppins text-2xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="text-shop-primary">⚡</span> Teknologi yang Tersedia
                        </h3>
                        <div class="grid grid-cols-2 gap-y-4 gap-x-2 text-[15px] font-medium text-gray-700">
                            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-shop-primary"></span> MikroTik RouterOS v6 & v7</div>
                            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-shop-primary"></span> Cisco IOS & IOS-XR</div>
                            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-shop-primary"></span> Cisco Nexus Switch</div>
                            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-shop-primary"></span> Juniper JunOS vSRX</div>
                            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-shop-primary"></span> Linux Ubuntu & Debian</div>
                            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-shop-primary"></span> Windows Server</div>
                            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-shop-primary"></span> Docker Container Lab</div>
                            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-shop-primary"></span> Network Automation Tools</div>
                        </div>
                    </div>
                    
                    <!-- Suitable For -->
                    <div class="bg-shop-background border border-gray-200 rounded-2xl p-8 hover:shadow-shop-md transition-shadow">
                        <h3 class="font-poppins text-2xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="text-shop-secondary">🎯</span> Sangat Cocok Untuk
                        </h3>
                        <div class="space-y-3.5 text-[15px] font-medium text-gray-700">
                            <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-100 shadow-sm"><span class="text-xl">👨‍🎓</span> Mahasiswa & Pelajar Teknik Jaringan (TKJ/TI)</div>
                            <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-100 shadow-sm"><span class="text-xl">🛠️</span> Network Engineer & System Administrator</div>
                            <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-100 shadow-sm"><span class="text-xl">📜</span> Persiapan Ujian Sertifikasi MikroTik (MTCNA, MTCRE, MTCINE)</div>
                            <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-100 shadow-sm"><span class="text-xl">🎖️</span> Persiapan Ujian Sertifikasi Cisco (CCNA 200-301, CCNP)</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Topologi & Skenario Lab Populer -->
        <section id="topologi" class="max-w-7xl mx-auto px-4 md:px-8 py-20">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="font-sans text-[11px] font-bold uppercase tracking-[0.1em] text-shop-primary bg-shop-primary/10 py-1 px-3.5 rounded-full mb-3 inline-block">
                    Katalog Praktek
                </span>
                <h2 class="font-poppins text-3xl md:text-4xl font-bold text-gray-900 leading-tight mb-4">
                    Skenario Topologi Siap Anda Bangun
                </h2>
                <p class="text-gray-600 text-base">
                    Eksplorasi skenario jaringan enterprise nyata yang biasa diujikan pada sertifikasi industri.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div 
                    v-for="topo in topologies" 
                    :key="topo.id"
                    class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-shop-hover hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between"
                >
                    <div>
                        <div class="flex items-center justify-between text-xs font-mono text-gray-500 mb-3">
                            <span class="bg-gray-100 px-2.5 py-0.5 rounded-full text-gray-700 font-semibold">{{ topo.difficulty }}</span>
                            <span class="text-shop-primary font-bold">{{ topo.nodes }}</span>
                        </div>
                        <h4 class="font-poppins text-lg font-bold text-gray-900 mb-2 leading-snug">{{ topo.title }}</h4>
                        <p class="text-gray-600 text-sm leading-relaxed mb-6">{{ topo.desc }}</p>
                    </div>

                    <div class="flex flex-wrap gap-1.5 pt-4 border-t border-gray-100">
                        <span 
                            v-for="(tag, tIdx) in topo.tags" 
                            :key="tIdx" 
                            class="text-[11px] font-mono bg-shop-background border border-gray-200 px-2 py-0.5 rounded text-gray-600"
                        >
                            {{ tag }}
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="bg-shop-surface border-y border-gray-200 py-20">
            <div class="max-w-7xl mx-auto px-4 md:px-8">
                <div class="text-center mb-16">
                    <span class="font-sans text-[11px] font-bold uppercase tracking-[0.1em] text-shop-primary bg-shop-primary/10 py-1 px-3 rounded-full mb-4 inline-block">Pricing</span>
                    <h2 class="font-poppins text-3xl md:text-4xl font-bold text-gray-900 leading-tight">Pilihan Paket Akses Lab</h2>
                    <p class="font-sans text-base text-gray-500 mt-3">Pilih paket yang sesuai dengan target materi dan durasi belajar Anda.</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6" v-if="products.length > 0">
                    <div 
                        v-for="product in products" 
                        :key="product.id" 
                        class="bg-shop-background rounded-2xl p-6 transition-all duration-200 flex flex-col" 
                        :class="product.is_recommended ? 'border-2 border-shop-primary shadow-shop-md hover:shadow-shop-hover hover:-translate-y-1 relative overflow-hidden bg-white' : 'border border-gray-200 hover:shadow-shop-lg hover:-translate-y-1'"
                    >
                        <div v-if="product.is_recommended" class="absolute top-0 right-0 bg-shop-primary text-white text-[10px] font-bold uppercase tracking-wider py-1 px-3 rounded-bl-lg">
                            Recommended
                        </div>
                        <h3 class="font-poppins text-xl font-bold text-gray-900">{{ product.name }}</h3>
                        <p class="text-[13px] text-gray-500 mt-2 min-h-[40px]">{{ product.description }}</p>
                        
                        <div class="my-6">
                            <span class="font-poppins text-3xl font-extrabold text-shop-primary">{{ product.price }}</span>
                        </div>

                        <ul class="space-y-3.5 mb-8 flex-1 text-[14px] text-gray-700">
                            <li v-for="(feature, idx) in product.features" :key="idx" class="flex items-start gap-2.5">
                                <span class="text-shop-success font-bold">✔</span> 
                                <span>{{ feature }}</span>
                            </li>
                        </ul>

                        <button 
                            @click="checkout(product.id)" 
                            :disabled="isProcessing" 
                            class="block w-full text-center rounded-xl py-3 font-poppins font-bold transition-all disabled:opacity-50 text-sm" 
                            :class="product.is_recommended ? 'bg-shop-primary hover:bg-shop-secondary text-white shadow-shop-md' : 'bg-white hover:bg-gray-50 text-shop-primary border-2 border-shop-primary'"
                        >
                            {{ isProcessing ? 'Memproses Transaksi...' : 'Pilih Paket' }}
                        </button>
                    </div>
                </div>
                <div v-else class="text-center py-10">
                    <p class="text-gray-500 italic text-[15px]">Belum ada paket produk yang tersedia saat ini.</p>
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="bg-gray-950 text-white py-20 relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-80 h-80 bg-shop-primary opacity-25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-shop-secondary opacity-20 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="max-w-7xl mx-auto px-4 md:px-8 relative z-10">
                <div class="text-center mb-16">
                    <h2 class="font-poppins text-3xl md:text-4xl font-bold leading-tight">Kenapa Memilih Lab Kami?</h2>
                    <p class="text-gray-400 text-sm sm:text-base mt-2">Dukungan infrastruktur terbaik untuk menunjang kelancaran simulasi Anda.</p>
                </div>
                
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div class="w-12 h-12 bg-shop-primary text-white rounded-xl flex items-center justify-center text-xl mb-4 shadow-lg shadow-shop-primary/20">🚀</div>
                        <h4 class="font-poppins text-lg font-bold mb-2">Server Stabil & Performa Tinggi</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Dedicated resources untuk mencegah lag saat menyalakan banyak router dan node simulasi sekaligus.</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div class="w-12 h-12 bg-shop-secondary text-white rounded-xl flex items-center justify-center text-xl mb-4 shadow-lg shadow-shop-secondary/20">🔒</div>
                        <h4 class="font-poppins text-lg font-bold mb-2">Akses Aman & Terisolasi</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Tiap akun memiliki workspace lab mandiri tanpa interferensi pengguna lain.</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div class="w-12 h-12 bg-red-600 text-white rounded-xl flex items-center justify-center text-xl mb-4 shadow-lg shadow-red-600/20">🌍</div>
                        <h4 class="font-poppins text-lg font-bold mb-2">Akses Dari Mana Saja</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Cukup gunakan laptop dan koneksi internet, Anda bisa praktikum dari rumah, kafe, atau kampus.</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div class="w-12 h-12 bg-emerald-600 text-white rounded-xl flex items-center justify-center text-xl mb-4">💬</div>
                        <h4 class="font-poppins text-lg font-bold mb-2">Technical Support Siap Bantu</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Mengalami kendala saat konfigurasi atau topologi? Tim teknis kami siap memandu via WhatsApp.</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div class="w-12 h-12 bg-gray-700 text-white rounded-xl flex items-center justify-center text-xl mb-4">⚙️</div>
                        <h4 class="font-poppins text-lg font-bold mb-2">Ekosistem Multi-Vendor</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Mendukung kolaborasi MikroTik, Cisco, Juniper, Linux, dan Windows Server tanpa instalasi ulang.</p>
                    </div>
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 backdrop-blur-sm">
                        <div class="w-12 h-12 bg-amber-500 text-white rounded-xl flex items-center justify-center text-xl mb-4">💰</div>
                        <h4 class="font-poppins text-lg font-bold mb-2">Hemat Jutaan Rupiah</h4>
                        <p class="text-gray-400 text-sm leading-relaxed">Tidak perlu beli router fisik bekas atau rakit PC server belasan juta untuk sekadar belajar.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="bg-shop-surface py-20 border-b border-gray-200 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 md:px-8 mb-16">
                <div class="text-center">
                    <span class="font-sans text-[11px] font-bold uppercase tracking-[0.1em] text-shop-primary bg-shop-primary/10 py-1 px-3.5 rounded-full mb-3 inline-block">
                        Social Proof
                    </span>
                    <h2 class="font-poppins text-3xl md:text-4xl font-bold text-gray-900 leading-tight">Testimoni Member Meraki Labs</h2>
                </div>
            </div>
            
            <div class="w-full relative group" v-if="testimonials.length > 0">
                <!-- Auto-sliding track -->
                <div class="flex gap-6 animate-slide group-hover:[animation-play-state:paused] w-max px-4">
                    <div v-for="(testi, index) in [...testimonials, ...testimonials]" :key="index" class="w-[320px] md:w-[400px] shrink-0 bg-shop-background border border-gray-200 p-8 rounded-2xl relative shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-shop-primary text-4xl absolute top-4 right-6 opacity-25 font-serif">"</div>
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
        <section id="faq" class="max-w-4xl mx-auto px-4 md:px-8 py-20">
            <div class="text-center mb-12">
                <span class="font-sans text-[11px] font-bold uppercase tracking-[0.1em] text-shop-primary bg-shop-primary/10 py-1 px-3.5 rounded-full mb-3 inline-block">
                    FAQ
                </span>
                <h2 class="font-poppins text-3xl md:text-4xl font-bold text-gray-900 leading-tight">Pertanyaan yang Sering Diajukan</h2>
            </div>
            <div class="space-y-4">
                <div v-for="(faq, index) in faqs" :key="index" class="border border-gray-200 rounded-2xl bg-white overflow-hidden shadow-sm">
                    <button @click="openFaq === index ? openFaq = null : openFaq = index" class="w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none hover:bg-gray-50/50 transition-colors">
                        <span class="font-poppins font-semibold text-[16px] text-gray-900 pr-4">{{ faq.q }}</span>
                        <span class="text-shop-primary text-xl font-mono transition-transform duration-200 shrink-0" :class="{ 'rotate-45': openFaq === index }">+</span>
                    </button>
                    <div v-show="openFaq === index" class="px-6 pb-5 text-[15px] text-gray-600 border-t border-gray-100 pt-3">
                        {{ faq.a }}
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action Banner -->
        <section class="bg-gradient-to-r from-shop-primary via-red-600 to-shop-secondary text-white py-20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
                <h2 class="font-poppins text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight mb-5">
                    Siap Menguasai Skill Networking Sekarang?
                </h2>
                <p class="font-sans text-base md:text-lg text-white/90 mb-8 max-w-2xl mx-auto leading-relaxed">
                    Jangan buang waktu berjam-jam untuk error instalasi lab lokal. Fokus pada pemahaman materi dan latihan praktik bersama Meraki Labs.
                </p>
                <div class="flex flex-wrap justify-center gap-3 mb-10 text-xs sm:text-sm font-bold">
                    <span class="bg-white/15 backdrop-blur-sm py-1.5 px-4 rounded-full border border-white/20">✅ MikroTik RouterOS</span>
                    <span class="bg-white/15 backdrop-blur-sm py-1.5 px-4 rounded-full border border-white/20">✅ Cisco IOS & Nexus</span>
                    <span class="bg-white/15 backdrop-blur-sm py-1.5 px-4 rounded-full border border-white/20">✅ Juniper vSRX</span>
                    <span class="bg-white/15 backdrop-blur-sm py-1.5 px-4 rounded-full border border-white/20">✅ Linux Server & Docker</span>
                </div>
                <div class="flex flex-wrap justify-center gap-4">
                    <a 
                        href="#pricing" 
                        class="bg-white text-shop-primary font-poppins font-bold py-3.5 px-8 rounded-full hover:bg-gray-100 shadow-shop-lg hover:-translate-y-0.5 transition-all text-sm sm:text-base"
                    >
                        Pilih Paket Belajar
                    </a>
                    <a 
                        href="https://wa.me/08000000000" 
                        target="_blank" 
                        class="bg-shop-secondary/80 hover:bg-shop-secondary text-white border border-white/30 font-poppins font-bold py-3.5 px-8 rounded-full shadow-shop-lg hover:-translate-y-0.5 transition-all text-sm sm:text-base flex items-center gap-2"
                    >
                        <span>Konsultasi WhatsApp</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Comprehensive Pro Footer -->
        <footer class="bg-gray-900 text-gray-400 pt-16 pb-12 border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 md:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">
                <!-- Col 1: Brand Info -->
                <div class="lg:col-span-2">
                    <Link href="/" class="inline-block mb-4">
                        <img src="/img/logo.png" alt="Meraki Labs" class="h-9 brightness-200 contrast-125">
                    </Link>
                    <p class="text-sm text-gray-400 leading-relaxed mb-6 max-w-sm">
                        Platform simulasi jaringan virtual terdepan berbasis PNETLab. Belajar MikroTik, Cisco, Juniper, dan Linux tanpa kendala spesifikasi komputer.
                    </p>
                    <div class="flex items-center gap-2 text-xs font-mono text-emerald-400 bg-gray-800/80 px-3 py-1.5 rounded-lg w-max border border-gray-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Server Cluster Operational (24/7)</span>
                    </div>
                </div>

                <!-- Col 2: Navigasi Cepat -->
                <div>
                    <h5 class="text-white font-poppins font-bold text-sm mb-4">Navigasi</h5>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="#about" class="hover:text-white transition-colors">Tentang Platform</a></li>
                        <li><a href="#how-it-works" class="hover:text-white transition-colors">Cara Kerja</a></li>
                        <li><a href="#topologi" class="hover:text-white transition-colors">Katalog Topologi</a></li>
                        <li><a href="#pricing" class="hover:text-white transition-colors">Paket & Harga</a></li>
                        <li><a href="#faq" class="hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <!-- Col 3: Portal & Akun -->
                <div>
                    <h5 class="text-white font-poppins font-bold text-sm mb-4">Akses & Member</h5>
                    <ul class="space-y-2.5 text-sm">
                        <li><Link href="/login" class="hover:text-white transition-colors">Member Area (Login)</Link></li>
                        <li><Link href="/register" class="hover:text-white transition-colors">Daftar Akun Baru</Link></li>
                        <li><Link href="/aktivasi-voucher" class="hover:text-white transition-colors">Aktivasi Kode Voucher</Link></li>
                        <li><Link href="/riwayat-transaksi" class="hover:text-white transition-colors">Riwayat Transaksi</Link></li>
                    </ul>
                </div>

                <!-- Col 4: Metode Pembayaran Midtrans -->
                <div>
                    <h5 class="text-white font-poppins font-bold text-sm mb-4">Metode Pembayaran</h5>
                    <p class="text-xs text-gray-400 mb-3">Otomatis & Terverifikasi Instan via Midtrans Snap</p>
                    <div class="flex flex-wrap gap-2 text-xs font-mono text-gray-300">
                        <span class="px-2.5 py-1 rounded bg-gray-800 border border-gray-700">QRIS</span>
                        <span class="px-2.5 py-1 rounded bg-gray-800 border border-gray-700">BCA VA</span>
                        <span class="px-2.5 py-1 rounded bg-gray-800 border border-gray-700">BNI VA</span>
                        <span class="px-2.5 py-1 rounded bg-gray-800 border border-gray-700">BRI VA</span>
                        <span class="px-2.5 py-1 rounded bg-gray-800 border border-gray-700">Mandiri</span>
                        <span class="px-2.5 py-1 rounded bg-gray-800 border border-gray-700">GoPay</span>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 md:px-8 pt-8 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-4">
                <span>&copy; {{ new Date().getFullYear() }} Meraki Labs. Hak cipta dilindungi.</span>
                <span>Powered by PNETLab Cloud Engine</span>
            </div>
        </footer>

        <!-- Floating WhatsApp Support Button -->
        <a 
            href="https://wa.me/08000000000" 
            target="_blank" 
            rel="noopener noreferrer"
            class="fixed bottom-6 right-6 z-40 bg-emerald-600 hover:bg-emerald-500 text-white p-3.5 rounded-full shadow-2xl hover:scale-110 active:scale-95 transition-all duration-200 flex items-center gap-2.5 group"
            title="Chat Admin WhatsApp"
        >
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.288.043.088.072.19.014.305-.058.115-.087.187-.173.289l-.26.309c-.087.094-.179.196-.077.371.101.174.452.744.97 1.206.666.593 1.228.777 1.401.864.173.086.275.072.376-.043.101-.116.433-.506.549-.679.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.044.073.044.42-.1.825z"/>
            </svg>
            <span class="hidden sm:inline font-bold text-xs pr-1">Tanya Admin</span>
        </a>

    </div>
</template>

<style>
/* Auto-slide Marquee Animation */
@keyframes slide {
    0% { transform: translateX(0); }
    100% { transform: translateX(calc(-50% - 12px)); }
}
.animate-slide {
    animation: slide 60s linear infinite;
}
</style>
