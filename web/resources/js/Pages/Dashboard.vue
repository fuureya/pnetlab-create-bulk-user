<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    admin_stats: {
        type: Object,
        default: () => ({
            total_vouchers: 0,
            active_vouchers: 0,
            unactive_vouchers: 0,
            expired_vouchers: 0,
            total_users: 0,
            total_transactions: 0,
            total_revenue: 0,
        })
    },
    stats: {
        type: Object,
        default: () => ({
            total_transactions: 0,
            active_vouchers: 0,
            expired_vouchers: 0
        })
    },
    recent_users: {
        type: Array,
        default: () => []
    },
    user_vouchers: {
        type: Array,
        default: () => []
    }
});

const searchQuery = ref('');
const copiedId = ref(null);
const memberVisiblePasswords = ref({});

const filteredRecentUsers = computed(() => {
    if (!searchQuery.value) return props.recent_users;
    const q = searchQuery.value.toLowerCase();
    return props.recent_users.filter(v => 
        (v.username && v.username.toLowerCase().includes(q)) ||
        (v.status && v.status.toLowerCase().includes(q)) ||
        (v.pod_id && v.pod_id.toString().includes(q))
    );
});

const formatRupiah = (val) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val || 0);
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};

const copyCredential = (text, id) => {
    navigator.clipboard.writeText(text);
    copiedId.value = id;
    setTimeout(() => {
        copiedId.value = null;
    }, 2000);
};

const toggleMemberPassword = (id) => {
    memberVisiblePasswords.value[id] = !memberVisiblePasswords.value[id];
};
</script>

<template>
    <Head title="Dashboard - Meraki Labs" />

    <AuthenticatedLayout>
        
        <!-- ================= ADMIN DASHBOARD ================= -->
        <div v-if="$page.props.auth.user.role === 'admin'" class="space-y-6">
            
            <!-- Header Toolbar -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-lg font-bold shrink-0">
                        <i class="fa-solid fa-gauge-high"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-poppins font-extrabold text-gray-900 tracking-tight">
                            Overview Lab & Statistik
                        </h1>
                        <p class="text-sm text-gray-500 mt-0.5">
                            Monitoring ringkas penggunaan voucher, pod PNETLab, dan performa transaksi.
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 sm:flex sm:items-center gap-2.5 sm:gap-3 w-full sm:w-auto shrink-0">
                    <Link 
                        href="/transaksi" 
                        class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 active:scale-[0.98] text-gray-700 border border-gray-200 px-3.5 sm:px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-xs whitespace-nowrap text-center"
                    >
                        <i class="fa-solid fa-receipt text-xs text-gray-500"></i>
                        <span>Semua Transaksi</span>
                    </Link>
                    <Link 
                        :href="route('users')" 
                        class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-shop-primary to-shop-secondary hover:brightness-110 active:scale-[0.98] text-white px-3.5 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all shadow-md shadow-shop-primary/20 hover:shadow-lg whitespace-nowrap text-center"
                    >
                        <i class="fa-solid fa-ticket text-xs"></i>
                        <span>Kelola Voucher Lab</span>
                    </Link>
                </div>
            </div>

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Card 1: Total Voucher -->
                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Voucher</p>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-none">
                            {{ admin_stats.total_vouchers }}
                        </h3>
                        <p class="text-[11px] text-gray-500 mt-1.5 font-medium">
                            <span class="text-emerald-600 font-bold">{{ admin_stats.active_vouchers }} aktif</span>, 
                            <span class="text-amber-600 font-bold">{{ admin_stats.unactive_vouchers }} belum</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                </div>

                <!-- Card 2: Voucher Aktif -->
                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Voucher Aktif</p>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-emerald-600 leading-none">
                            {{ admin_stats.active_vouchers }}
                        </h3>
                        <p class="text-[11px] text-gray-500 mt-1.5 font-medium">Sedang digunakan dalam lab</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>

                <!-- Card 3: Total Pendaftar -->
                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Pendaftar</p>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-none">
                            {{ admin_stats.total_users }}
                        </h3>
                        <p class="text-[11px] text-gray-500 mt-1.5 font-medium">Akun pengguna terdaftar</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>

                <!-- Card 4: Omset Transaksi -->
                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Omset Berhasil</p>
                        <h3 class="text-xl sm:text-2xl font-extrabold text-shop-primary leading-none truncate max-w-[180px]">
                            {{ formatRupiah(admin_stats.total_revenue) }}
                        </h3>
                        <p class="text-[11px] text-gray-500 mt-1.5 font-medium">
                            Dari {{ admin_stats.total_transactions }} total checkout
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                </div>
            </div>

            <!-- Recent Vouchers Table Section -->
            <div class="bg-white border border-gray-200/80 rounded-2xl overflow-hidden shadow-xs">
                <div class="px-6 py-4 border-b border-gray-200/80 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Voucher & User Lab Terbaru</h3>
                        <p class="text-xs text-gray-500">Data voucher terakhir yang terdaftar di sistem.</p>
                    </div>
                    
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <div class="relative w-full sm:w-60">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Cari username / pod..." 
                                class="w-full h-9 pl-9 pr-3 rounded-xl border border-gray-200 bg-gray-50/50 text-xs focus:bg-white focus:border-shop-primary focus:ring-1 focus:ring-shop-primary transition-all"
                            />
                        </div>
                        <Link 
                            :href="route('users')" 
                            class="text-xs font-bold text-shop-primary hover:text-shop-secondary whitespace-nowrap px-3 py-2 rounded-xl hover:bg-shop-primary/10 transition-colors inline-flex items-center gap-1.5"
                        >
                            <span>Lihat Semua</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </Link>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-200 text-gray-500 font-mono text-[11px] uppercase">
                                <th class="px-6 py-3.5">Username</th>
                                <th class="px-6 py-3.5">Password</th>
                                <th class="px-6 py-3.5">Pod ID</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5">Durasi</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="voucher in filteredRecentUsers" :key="voucher.id" class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-6 py-3.5 font-mono font-bold text-gray-900">
                                    {{ voucher.username }}
                                </td>
                                <td class="px-6 py-3.5 font-mono text-gray-600">
                                    <div class="flex items-center gap-1.5">
                                        <span>{{ voucher.password || '-' }}</span>
                                        <button 
                                            v-if="voucher.password"
                                            @click="copyCredential(`User: ${voucher.username} | Pass: ${voucher.password}`, voucher.id)"
                                            class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-shop-primary hover:bg-shop-primary/10 transition-colors"
                                            title="Salin kredensial"
                                        >
                                            <i v-if="copiedId === voucher.id" class="fa-solid fa-check text-emerald-600"></i>
                                            <i v-else class="fa-solid fa-copy text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5 font-mono text-gray-700">
                                    <span class="px-2.5 py-0.5 rounded-lg bg-gray-100 font-bold text-xs border border-gray-200">Pod {{ voucher.pod_id }}</span>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span v-if="voucher.status === 'aktif'" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-circle text-[6px]"></i> Aktif
                                    </span>
                                    <span v-else-if="voucher.status === 'expired'" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-circle text-[6px]"></i> Expired
                                    </span>
                                    <span v-else class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-solid fa-circle text-[6px]"></i> Belum Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-gray-500 font-mono">
                                    {{ voucher.duration_days }} Hari
                                </td>
                                <td class="px-6 py-3.5 text-right font-medium">
                                    <Link :href="route('users')" class="text-shop-primary hover:text-shop-secondary font-bold text-xs inline-flex items-center gap-1">
                                        <span>Kelola</span>
                                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="filteredRecentUsers.length === 0">
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">
                                    Tidak ada data voucher yang cocok.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- ================= MEMBER / USER DASHBOARD ================= -->
        <div v-else class="space-y-6">
            
            <!-- Welcome Header Hero -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200/80 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-shop-primary to-shop-secondary text-white flex items-center justify-center font-extrabold text-2xl shadow-md shadow-shop-primary/20 shrink-0">
                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-poppins font-extrabold text-gray-900 tracking-tight">
                                Halo, {{ $page.props.auth.user.name }}
                            </h1>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                <i class="fa-solid fa-graduation-cap text-[10px] text-gray-500"></i>
                                Member Lab
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                            Selamat datang di portal member Meraki Labs. Kelola akun voucher simulasi dan akses lab Anda di sini.
                        </p>
                    </div>
                </div>

                <!-- Action Buttons: Always side-by-side (never stacked/numpuk) -->
                <div class="grid grid-cols-2 sm:flex sm:items-center gap-2.5 sm:gap-3 w-full sm:w-auto shrink-0">
                    <Link 
                        href="/aktivasi-voucher" 
                        class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 active:scale-[0.98] text-gray-700 border border-gray-200 px-3.5 sm:px-4 py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-xs transition-all whitespace-nowrap text-center"
                    >
                        <i class="fa-solid fa-key text-xs text-shop-primary"></i>
                        <span>Aktivasi Voucher</span>
                    </Link>

                    <Link 
                        href="/#pricing" 
                        class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-shop-primary to-shop-secondary hover:brightness-110 active:scale-[0.98] text-white px-3.5 sm:px-5 py-2.5 rounded-xl font-bold text-xs sm:text-sm shadow-md shadow-shop-primary/20 hover:shadow-lg transition-all whitespace-nowrap text-center"
                    >
                        <i class="fa-solid fa-cart-shopping text-xs"></i>
                        <span>Beli Paket Baru</span>
                    </Link>
                </div>
            </div>

            <!-- User KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Pembelian</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 leading-none">{{ stats.total_transactions }}</h3>
                        <p class="text-[11px] text-gray-400 mt-1">Transaksi tercatat</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </div>

                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Voucher Aktif</p>
                        <h3 class="text-2xl font-extrabold text-emerald-600 leading-none">{{ stats.active_vouchers }}</h3>
                        <p class="text-[11px] text-gray-400 mt-1">Siap digunakan di PNETLab</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>

                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Voucher Expired</p>
                        <h3 class="text-2xl font-extrabold text-rose-600 leading-none">{{ stats.expired_vouchers }}</h3>
                        <p class="text-[11px] text-gray-400 mt-1">Masa aktif habis</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>
            </div>

            <!-- User Content: Voucher Saya & Riwayat Transaksi -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                
                <!-- Voucher Saya (7 cols) -->
                <div class="lg:col-span-7 bg-white border border-gray-200/90 rounded-2xl overflow-hidden shadow-xs">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-ticket text-shop-primary text-sm"></i>
                            <h3 class="font-bold text-gray-900 text-sm">Voucher Lab Saya</h3>
                        </div>
                        <Link href="/aktivasi-voucher" class="text-xs font-bold text-shop-primary hover:underline flex items-center gap-1">
                            <span>Aktivasi Voucher</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </Link>
                    </div>

                    <div v-if="user_vouchers && user_vouchers.length > 0" class="divide-y divide-gray-100">
                        <div 
                            v-for="v in user_vouchers" 
                            :key="v.id" 
                            class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-gray-50/50 transition-colors"
                        >
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-gray-900 text-sm">{{ v.username }}</span>
                                    <span class="px-2 py-0.5 rounded-lg bg-gray-100 font-mono text-[11px] font-bold text-gray-600 border border-gray-200">
                                        Pod {{ v.pod_id }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2 text-xs font-mono text-gray-600">
                                    <span>Pass:</span>
                                    <span v-if="!memberVisiblePasswords[v.id]">••••••••</span>
                                    <span v-else class="font-bold text-gray-900">{{ v.password }}</span>

                                    <button 
                                        @click="toggleMemberPassword(v.id)" 
                                        class="w-6 h-6 rounded flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors"
                                        title="Lihat / Sembunyikan Password"
                                    >
                                        <i class="fa-solid text-[10px]" :class="memberVisiblePasswords[v.id] ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>

                                    <button 
                                        @click="copyCredential(`User: ${v.username} | Pass: ${v.password}`, v.id)"
                                        class="w-6 h-6 rounded flex items-center justify-center text-gray-400 hover:text-shop-primary hover:bg-shop-primary/10 transition-colors"
                                        title="Salin Kredensial"
                                    >
                                        <i v-if="copiedId === v.id" class="fa-solid fa-check text-emerald-600 text-[10px]"></i>
                                        <i v-else class="fa-solid fa-copy text-[10px]"></i>
                                    </button>
                                </div>

                                <div class="flex items-center gap-2 pt-0.5">
                                    <span 
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold" 
                                        :class="{
                                            'bg-emerald-50 text-emerald-700 border border-emerald-200': v.status === 'aktif',
                                            'bg-rose-50 text-rose-700 border border-rose-200': v.status === 'expired',
                                            'bg-amber-50 text-amber-700 border border-amber-200': v.status === 'belum aktif',
                                        }"
                                    >
                                        <i class="fa-solid fa-circle text-[6px]"></i>
                                        <span>{{ v.status }}</span>
                                    </span>

                                    <span class="text-[11px] text-gray-400 font-mono" v-if="v.status === 'aktif'">
                                        Hingga {{ formatDate(v.expired_at) }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-start sm:self-center">
                                <Link 
                                    v-if="v.status === 'belum aktif'"
                                    :href="`/aktivasi-voucher?username=${encodeURIComponent(v.username)}`" 
                                    class="text-xs font-bold bg-shop-primary hover:bg-shop-secondary text-white py-2 px-3.5 rounded-xl shadow-xs transition-all flex items-center gap-1.5"
                                >
                                    <i class="fa-solid fa-bolt text-[10px]"></i>
                                    <span>Aktifkan Sekarang</span>
                                </Link>
                                <span 
                                    v-else-if="v.status === 'aktif'"
                                    class="text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200 flex items-center gap-1.5"
                                >
                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                    <span>Siap Digunakan</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="p-8 text-center text-gray-400">
                        <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400 text-2xl mb-3 mx-auto">
                            <i class="fa-solid fa-ticket"></i>
                        </div>
                        <p class="font-medium text-gray-600 text-sm">Anda belum memiliki voucher lab.</p>
                        <p class="text-xs text-gray-400 mt-0.5">Beli paket lab untuk mulai praktikum simulasi topologi jaringan.</p>
                        <Link href="/#pricing" class="inline-flex items-center gap-1.5 mt-4 text-xs font-bold text-shop-primary hover:underline">
                            <span>Pesan Paket Lab Sekarang</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </Link>
                    </div>
                </div>

                <!-- Info & Transaksi Ringkas (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <!-- Box Riwayat -->
                    <div class="bg-white border border-gray-200/90 rounded-2xl overflow-hidden shadow-xs">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-receipt text-shop-primary text-sm"></i>
                                <h3 class="font-bold text-gray-900 text-sm">Riwayat Pembayaran</h3>
                            </div>
                            <Link href="/riwayat-transaksi" class="text-xs font-bold text-shop-primary hover:underline flex items-center gap-1">
                                <span>Lihat Semua</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </Link>
                        </div>
                        <div class="p-6 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-xl mx-auto mb-3">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </div>
                            <p class="text-sm text-gray-800 font-bold">
                                Total {{ stats.total_transactions }} Transaksi
                            </p>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                Cek status pembayaran Midtrans dan dapatkan struk pembelian di menu riwayat transaksi.
                            </p>
                            <Link 
                                href="/riwayat-transaksi" 
                                class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-700 rounded-xl text-xs font-bold transition-colors"
                            >
                                <span>Buka Riwayat Transaksi</span>
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </Link>
                        </div>
                    </div>

                    <!-- Box Status Server PNETLab -->
                    <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 text-white shadow-xs">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                                </span>
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Server Online</span>
                            </div>
                            <span class="text-[11px] text-gray-400 font-mono">PNETLab v5.x</span>
                        </div>
                        <h4 class="text-sm font-bold">Lab Virtual Siap Digunakan</h4>
                        <p class="text-xs text-gray-300 mt-1 leading-relaxed">
                            Server beroperasi 24/7 dengan resource CPU & RAM dedicated untuk simulasi router, switch, dan firewall Anda.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </AuthenticatedLayout>
</template>
